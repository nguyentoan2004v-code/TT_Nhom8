import requests
import datetime
from bs4 import BeautifulSoup

def get_articles():
    url = "https://vnexpress.net/"
    headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0 Safari/537.36'}
    try:
        response = requests.get(url, headers=headers, timeout=15)
        soup = BeautifulSoup(response.content, 'html.parser')
        articles = []
        
        title_tags = soup.find_all(['h1', 'h2', 'h3', 'h4'], class_='title-news')
        count = 0
        for tag in title_tags:
            if count >= 6: break
            link_tag = tag if tag.name == 'a' else tag.find('a')
            if link_tag:
                link = link_tag.get('href')
                title = link_tag.get('title') or link_tag.text.strip()
                if link and 'video' not in link and 'podcast' not in link:
                    content, published_date, image_url = get_article_content(link, headers)
                    if content:
                        articles.append({
                            'title': title, 'link': link, 'content': content,
                            'source': 'vnexpress', 'published_date': published_date, 'image_url': image_url
                        })
                        count += 1
        return articles
    except: return []

def get_article_content(article_url, headers):
    try:
        response = requests.get(article_url, headers=headers, timeout=10)
        soup = BeautifulSoup(response.content, 'html.parser')
        
        # DỌN RÁC
        for tag in soup.find_all(['script', 'style', 'iframe', 'video', 'object']): tag.decompose()
        for div in soup.find_all('div', class_=['box_embed_video', 'header-content', 'footer-content']): div.decompose()

        # NGÀY
        published_date = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        date_tag = soup.find('span', class_='date')
        if date_tag:
            try:
                # Xử lý ngày tùy biến
                published_date = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S') 
            except: pass

        # NỘI DUNG HTML + ẢNH
        content_div = soup.find('article', class_='fck_detail') or soup.find('div', class_='fck_detail')
        image_url = None
        html_content = ""

        if content_div:
            first_img = content_div.find('img')
            if first_img: image_url = first_img.get('data-src') or first_img.get('src')

            for element in content_div.find_all(['p', 'figure', 'h2', 'h3', 'ul']):
                if element.name == 'figure': # Ảnh trong bài
                    img = element.find('img')
                    cap = element.find('figcaption')
                    if img:
                        src = img.get('data-src') or img.get('src')
                        caption = cap.get_text().strip() if cap else ""
                        html_content += f'<figure class="article-image"><img src="{src}" alt="{caption}"><figcaption>{caption}</figcaption></figure>'
                elif element.name in ['p', 'h2', 'h3', 'ul']: # Văn bản
                    if element.get_text().strip():
                        html_content += str(element)

        return html_content, published_date, image_url
    except: return None, None, None