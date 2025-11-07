import requests
from bs4 import BeautifulSoup

def get_articles():
    url = "https://tuoitre.vn/"
    response = requests.get(url)
    soup = BeautifulSoup(response.content, 'html.parser')
    articles = []
    
    # Thử với class box-title-text
    title_tags = soup.find_all(['h1','h2', 'h3'], class_='box-title-text')
    
    for tag in title_tags[:5]:
        link_tag = tag.find('a')
        if link_tag:
            title = link_tag.get('title') or link_tag.text.strip()
            link = link_tag.get('href')
            
            if link and not link.startswith('http'):
                link = 'https://tuoitre.vn' + link
            
            if title and link:
                articles.append({
                    'title': title,
                    'link': link,
                    'content': get_article_content(link),
                    'source': 3
                })
    
    return articles

def get_article_content(article_url):
    try:
        response = requests.get(article_url)
        soup = BeautifulSoup(response.content, 'html.parser')
        
        content_div = soup.find('div', class_='detail-content')
        if content_div:
            return content_div.text.strip()
        else:
            return "Không có nội dung"
    except:
        return "Lỗi khi lấy nội dung"