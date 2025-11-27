import requests
import datetime
from bs4 import BeautifulSoup

def get_articles():
    source_name = "VNExpress"
    url = "https://vnexpress.net/"
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Referer': 'https://www.google.com/'
    }
    
    try:
        response = requests.get(url, headers=headers, timeout=15)
        soup = BeautifulSoup(response.content, 'html.parser')
        articles = []
        
        # Tìm các thẻ chứa link bài viết
        title_tags = soup.find_all(['h1', 'h2', 'h3', 'h4'], class_='title-news')
        
        count = 0
        for tag in title_tags:
            if count >= 6: break
            
            link_tag = tag.find('a') if tag.name != 'a' else tag
            
            if link_tag:
                link = link_tag.get('href')
                title = link_tag.get('title') or link_tag.text.strip()
                
                # Lọc link rác (Video, Podcast, Eclick...)
                if link and 'video' not in link and 'podcast' not in link and 'eclick' not in link:
                    if not link.startswith('http'): 
                        link = 'https://vnexpress.net' + link
                    
                    # Gọi hàm lấy nội dung chi tiết
                    content, published_date, image_url = get_article_content(link, headers)
                    
                    if content:
                        articles.append({
                            'title': title,
                            'link': link,
                            'content': content,
                            'source': 'VnExpress',
                            'published_date': published_date,
                            'image_url': image_url 
                        })
                        count += 1
    
        return articles
    except Exception as e:
        print(f"Lỗi VnExpress: {e}")
        return []

def get_article_content(article_url, headers):
    try:
        response = requests.get(article_url, headers=headers, timeout=10)
        soup = BeautifulSoup(response.content, 'html.parser')
        
        # --- 1. DỌN RÁC HTML ---
        for tag in soup.find_all(['script', 'style', 'iframe', 'video', 'object', 'meta', 'link', 'svg', 'button']): 
            tag.decompose()
            
        rac_classes = [
            'header-content', 'footer-content', 'box_embed_video', 
            'box-related', 'related-news', 'banner-ads', 'box_brief',
            'meta-news', 'box-date', 'breadcrumb', 'width_common', 'box_author'
        ]
        for div in soup.find_all(class_=rac_classes): div.decompose()

        # --- 2. CHIẾN THUẬT SĂN ẢNH ĐẠI DIỆN (4 LỚP) ---
        image_url = None
        
        # Cách 1: Lấy từ Meta OG Image (Chuẩn nhất)
        meta_og = soup.find('meta', property='og:image')
        if meta_og: image_url = meta_og.get('content')
        
        # Cách 2: Lấy từ Twitter Image
        if not image_url:
            meta_tw = soup.find('meta', attrs={'name': 'twitter:image'})
            if meta_tw: image_url = meta_tw.get('content')

        # Cách 3: Lấy từ Schema Itemprop
        if not image_url:
            meta_item = soup.find('meta', itemprop='thumbnailUrl')
            if meta_item: image_url = meta_item.get('content')

        # --- 3. LẤY NGÀY ĐĂNG ---
        published_date = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        date_tag = soup.find('span', class_='date')
        if date_tag:
            try:
                parts = date_tag.text.strip().split(', ')
                if len(parts) >= 2:
                    raw_dt = f"{parts[1]}, {parts[2].split(' (')[0]}"
                    dt = datetime.datetime.strptime(raw_dt, '%d/%m/%Y, %H:%M')
                    published_date = dt.strftime('%Y-%m-%d %H:%M:%S')
            except: pass

        # --- 4. XỬ LÝ NỘI DUNG CHÍNH ---
        full_html = ""
        
        # Lấy Sapo (Tóm tắt đầu bài)
        sapo = soup.find('p', class_='description')
        if sapo:
            for a in sapo.find_all('a'): a.unwrap()
            full_html += f'<p style="font-weight:bold; font-size:1.1em;">{sapo.get_text().strip()}</p>'

        # Lấy Body bài viết
        content_div = soup.find('article', class_='fck_detail') or soup.find('div', class_='fck_detail')
        
        if content_div:
            # Cách 4 (Cuối cùng): Nếu vẫn chưa có ảnh, tìm ảnh đầu tiên trong bài
            if not image_url:
                first_img = content_div.find('img')
                if first_img:
                    image_url = first_img.get('data-src') or first_img.get('src')

            # Xử lý ảnh trong bài (Fix Lazy Load)
            for img in content_div.find_all('img'):
                real_src = img.get('data-src') or img.get('src')
                if real_src:
                    img['src'] = real_src
                    img['class'] = 'img-fluid rounded shadow-sm'
                    # Xóa các thuộc tính thừa
                    for attr in ['width', 'height', 'style', 'onclick', 'data-src']: 
                        if attr in img.attrs: del img[attr]
            
            # Xóa link nội bộ
            for a in content_div.find_all('a'): a.unwrap()

            # Lấy toàn bộ HTML sạch
            full_html += content_div.decode_contents()

        return full_html, published_date, image_url
    except: return None, None, None