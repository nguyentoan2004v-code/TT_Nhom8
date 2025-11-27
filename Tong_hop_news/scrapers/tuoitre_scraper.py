import requests
import datetime
from bs4 import BeautifulSoup

def get_articles():
    url = "https://tuoitre.vn/"
    headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0 Safari/537.36'}
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
                    if content:
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
        
        # 1. DỌN RÁC
        for tag in soup.find_all(['script', 'style', 'iframe', 'video']): tag.decompose()
        rac_classes = [
            'VCCorpPlayer', 'relate-container', 'box-relate', 
            'ads', 'banner-ads', 'sponsor', 'detail-content-bottom', 'audioplayer'
        ]
        for div in soup.find_all(class_=rac_classes): div.decompose()

        # 2. LẤY ẢNH & NGÀY
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
        
        # 3. XỬ LÝ NỘI DUNG
        sapo = soup.find('h2', class_='sapo')
        body = soup.find('div', id='main-detail-body') or soup.find('div', class_='detail-content')
        
        full_html = ""
        if sapo: full_html += f'<p style="font-weight:bold; font-size:1.1em;">{sapo.get_text().strip()}</p>'

        if body:
            # Xử lý ảnh đặc thù Tuổi Trẻ
            for div_img in body.find_all('div', class_='VCSortableInPreviewMode'):
                img = div_img.find('img')
                if img:
                    src = img.get('data-original') or img.get('src')
                    # Tìm caption
                    caption_div = div_img.find('div', class_='PhotoCMS_Caption')
                    caption_text = caption_div.get_text().strip() if caption_div else ""
                    
                    # Thay thế khối div phức tạp bằng thẻ figure chuẩn HTML5
                    new_figure = soup.new_tag("figure")
                    new_img = soup.new_tag("img", src=src, **{'class': 'img-fluid rounded'})
                    new_figure.append(new_img)
                    
                    if caption_text:
                        new_cap = soup.new_tag("figcaption", **{'class': 'text-center fst-italic text-muted small mt-2'})
                        new_cap.string = caption_text
                        new_figure.append(new_cap)
                    
                    div_img.replace_with(new_figure)

            # Xóa link rác
            for a in body.find_all('a'): a.unwrap()

            full_html += body.decode_contents()

        return full_html, published_date, image_url
    except: return None, None, None