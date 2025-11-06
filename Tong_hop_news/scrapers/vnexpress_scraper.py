import requests
from bs4 import BeautifulSoup

def get_articles():
    url = "https://vnexpress.net"
    response = requests.get(url)
    soup = BeautifulSoup(response.content, 'html.parser')
    articles = []
    
    # Tìm tất cả thẻ h2, h3 có class chứa 'title'
    title_tags = soup.find_all(['h1','h2', 'h3'], class_=lambda x: x and 'title' in x)
    
    for tag in title_tags[:10]:  # Lấy 10 bài đầu
        link_tag = tag.find('a')
        if link_tag:
            title = link_tag.get('title') or link_tag.text.strip()
            link = link_tag.get('href')
            
            if link and not link.startswith('http'):
                link = 'https://vnexpress.net' + link
            
            if title and link:
                articles.append({
                    'title': title,
                    'link': link,
                    'content': '',
                    'source': 1
                })
    
    return articles