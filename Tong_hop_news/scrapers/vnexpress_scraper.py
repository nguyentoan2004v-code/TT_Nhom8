import requests
from bs4 import BeautifulSoup

def get_articles():
    url = "https://vnexpress.net"
    response = requests.get(url)
    soup = BeautifulSoup(response.content, 'html.parser')
    articles = []
    
    title_tags = soup.find_all(['h1','h2', 'h3'], class_=lambda x: x and 'title' in x)
    
    for tag in title_tags[:5]: 
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
                    'content': get_article_content(link),
                    'source': 1
                })
    
    return articles
def get_article_content(article_url):
    try:
        response = requests.get(article_url)
        soup = BeautifulSoup(response.content, 'html.parser')
        
        content_div = soup.find('div', class_='fck_detail')
        if content_div:
            return content_div.text.strip()
        else:
            return "Không có nội dung"
    except:
        return "Lỗi khi lấy nội dung"