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
            link = 'https://tuoitre.vn' + link_tag.get('href') if not link_tag.get('href').startswith('http') else link_tag.get('href')
            title = link_tag.get('title') or link_tag.text.strip()
            
            if 'video' not in link and 'podcast' not in link:
                content, published_date, image_url = get_article_content(link, headers)
                if content:
                    articles.append({
                        'title': title, 'link': link, 'content': content,
                        'source': 'tuoitre', 'published_date': published_date, 'image_url': image_url
                    })
                    count += 1
        return articles
    except: return []

def get_article_content(article_url, headers):
    try:
        response = requests.get(article_url, headers=headers, timeout=10)
        soup = BeautifulSoup(response.content, 'html.parser')
        
        # DỌN RÁC
        for tag in soup.find_all(['script', 'style', 'iframe', 'video']): tag.decompose()
        for div in soup.find_all('div', class_=['VCCorpPlayer', 'VCSortableInPreviewMode', 'relate-container']): div.decompose()

        published_date = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        
        content_div = soup.find('div', id='main-detail-body') or soup.find('div', class_='detail-content')
        image_url = None
        html_content = ""

        if content_div:
            first_img = content_div.find('img')
            if first_img: image_url = first_img.get('data-original') or first_img.get('src')

            for element in content_div.find_all(['p', 'div', 'h2', 'h3'], recursive=False):
                if element.name == 'div' and element.find('img'): # Ảnh
                    img = element.find('img')
                    if img:
                        src = img.get('data-original') or img.get('src')
                        html_content += f'<figure class="article-image"><img src="{src}"></figure>'
                elif element.name in ['p', 'h2', 'h3']: # Văn bản
                    if element.get_text().strip():
                        html_content += str(element)

        return html_content, published_date, image_url
    except: return None, None, None