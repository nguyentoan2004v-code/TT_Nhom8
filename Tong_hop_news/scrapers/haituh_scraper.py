import requests
import datetime
from bs4 import BeautifulSoup

def get_articles():
    url = "https://www.24h.com.vn/"
    headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0 Safari/537.36'}
    try:
        response = requests.get(url, headers=headers, timeout=15)
        soup = BeautifulSoup(response.content, 'html.parser')
        articles = []
        
        candidates = [h.find('a') for h in soup.find_all(['h2', 'h3', 'h4']) if h.find('a')]
        seen = set()
        count = 0
        
        for link_tag in candidates:
            if count >= 6: break
            href = link_tag.get('href')
            title = link_tag.get('title') or link_tag.text.strip()
            
            if href and '.html' in href and len(title) > 15 and href not in seen:
                seen.add(href)
                if not href.startswith('http'): href = 'https://www.24h.com.vn' + href
                
                content, published_date, image_url = get_article_content(href, headers)
                if content:
                    articles.append({
                        'title': title, 'link': href, 'content': content,
                        'source': '24h', 'published_date': published_date, 'image_url': image_url
                    })
                    count += 1
        return articles
    except: return []

def get_article_content(article_url, headers):
    try:
        response = requests.get(article_url, headers=headers, timeout=10)
        soup = BeautifulSoup(response.content, 'html.parser')
        
        # DỌN RÁC (Quan trọng cho 24h)
        for tag in soup.find_all(['script', 'style', 'iframe', 'video']): tag.decompose()
        for div in soup.find_all('div', class_=['viewVideoPlay', 'video-content', 'banner-ads', 'baiviet-lienquan']): div.decompose()

        published_date = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        
        content_div = soup.find('article', id='article_body') or soup.find('div', id='article_body')
        image_url = None
        html_content = ""

        if content_div:
            first_img = content_div.find('img')
            if first_img: image_url = first_img.get('data-src') or first_img.get('src')

            # Quét nội dung
            for element in content_div.descendants:
                if element.name == 'p':
                    img = element.find('img')
                    if img: # Nếu p chứa ảnh
                        src = img.get('data-src') or img.get('src')
                        html_content += f'<figure class="article-image"><img src="{src}"></figure>'
                    elif element.get_text().strip(): # Nếu p chứa chữ
                        html_content += str(element)
                elif element.name in ['h2', 'h3']:
                    html_content += str(element)

        return html_content, published_date, image_url
    except: return None, None, None