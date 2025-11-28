import requests
import datetime
from bs4 import BeautifulSoup

def get_articles():
    url = "https://tuoitre.vn/"
    headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'}
    try:
        response = requests.get(url, headers=headers, timeout=15)
        soup = BeautifulSoup(response.content, 'html.parser')
        articles = []
        
        links = [a for item in soup.find_all(['div', 'li'], class_=['box-category-item', 'box-li']) if (a := item.find('a'))]
        
        count = 0
        for link_tag in links:
            if count >= 6: break
            href = link_tag.get('href')
            title = link_tag.get('title') or link_tag.text.strip()
            
            if href:
                link = 'https://tuoitre.vn' + href if not href.startswith('http') else href
                if 'video' not in link and 'podcast' not in link:
                    content, published_date, image_url = get_article_content(link, headers)
                    if content and len(content) > 100:
                        articles.append({
                            'title': title, 'link': link, 'content': content,
                            'source': 'Tuổi Trẻ', 'published_date': published_date, 'image_url': image_url
                        })
                        count += 1
        return articles
    except: return []

def get_article_content(article_url, headers):
    try:
        response = requests.get(article_url, headers=headers, timeout=10)
        soup = BeautifulSoup(response.content, 'html.parser')
        
        # 1. DỌN RÁC TRIỆT ĐỂ
        for tag in soup.find_all(['script', 'style', 'iframe', 'video', 'audio', 'object', 'ads', 'button', 'input']): tag.decompose()
        rac_classes = ['VCCorpPlayer', 'relate-container', 'box-relate', 'ads', 'banner-ads', 'sponsor', 
                       'detail-content-bottom', 'audioplayer', 'box-tin-tai-tro', 'news-relate-bot', 'tin-tuong-tu', 
                       'VCSocialShare', 'right-tool-detail', 'VCPaywall', 'box-tin-can-biet']
        for div in soup.find_all(class_=rac_classes): div.decompose()

        # 2. LẤY ẢNH BÌA & NGÀY
        image_url = None
        meta_img = soup.find('meta', property='og:image')
        if meta_img: image_url = meta_img.get('content')

        published_date = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        date_tag = soup.find('div', attrs={'data-role': 'publishdate'})
        if date_tag:
            try:
               dt = datetime.datetime.strptime(date_tag.text.strip().split('GMT')[0].strip(), '%d/%m/%Y %H:%M')
               published_date = dt.strftime('%Y-%m-%d %H:%M:%S')
            except: pass
        
        # 3. XỬ LÝ NỘI DUNG FULL (Sapo + Body)
        sapo = soup.find('h2', class_='sapo')
        body = soup.find('div', id='main-detail-body') or soup.find('div', class_='detail-content')
        
        full_html = ""
        if sapo: full_html += f'<p style="font-weight:bold; font-size:1.1em;">{sapo.get_text().strip()}</p>'

        if body:
            # Lấy tất cả các phần tử con cấp 1 (p, div, figure...)
            children = body.find_all(['p', 'div', 'figure'], recursive=False)
            
            # --- THÊM LOGIC: CẮT BỎ CÁC PHẦN TỬ CUỐI CÙNG (GÂY LỖI) ---
            # Xóa 3 phần tử cuối cùng nếu chúng là ảnh/figure/div ngắn ngủi (khu vực nhúng quảng cáo)
            for i in range(1, 4): 
                if len(children) >= i:
                    el = children[-i] # Lấy phần tử từ cuối lên
                    is_image_block = el.name in ['figure'] or el.find('img')
                    is_too_short = el.name == 'p' and len(el.get_text().strip()) < 50
                    
                    if is_image_block or is_too_short:
                        el.decompose()
            # -----------------------------------------------------------

            # Fix ảnh trong bài
            for div_img in body.find_all('div', class_='VCSortableInPreviewMode'):
                img = div_img.find('img')
                if img:
                    src = img.get('data-original') or img.get('src')
                    caption_tag = div_img.find('div', class_='PhotoCMS_Caption')
                    caption = caption_tag.get_text().strip() if caption_tag else ""
                    
                    new_figure = soup.new_tag("figure")
                    new_img = soup.new_tag("img", src=src, **{'class': 'img-fluid rounded'})
                    new_figure.append(new_img)
                    if caption:
                        new_cap = soup.new_tag("figcaption")
                        new_cap.string = caption
                        new_figure.append(new_cap)
                    
                    div_img.replace_with(new_figure) 
                else:
                    div_img.decompose() 

            # Xóa link và thẻ P rỗng
            for a in body.find_all('a'): a.unwrap()
            for p in body.find_all('p'):
                if not p.get_text().strip() and not p.find():
                    p.decompose()

            full_html += body.decode_contents()

        return full_html, published_date, image_url
    except: return None, None, None