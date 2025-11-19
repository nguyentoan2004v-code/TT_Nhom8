import requests
from bs4 import BeautifulSoup

def get_articles():
    url = "https://www.24h.com.vn/"
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.75 Safari/537.36'
    }
    response = requests.get(url)
    soup = BeautifulSoup(response.content, 'html.parser')
    articles = []
    
    
    title_tags = soup.find_all(['h1','h2', 'h3'], class_='lh-100')
    
    for tag in title_tags[:5]:
        link_tag = tag.find('a')
        if link_tag:
            title = link_tag.get('title') or link_tag.text.strip()
            link = link_tag.get('href')
            
            if link and not link.startswith('http'):
                link = 'https://www.24h.com.vn' + link
            
            if title and link:
                articles.append({
                    'title': title,
                    'link': link,
                    'content': get_article_content(link),
                    'source': ''
                })
    
    return articles

def get_article_content(article_url):
    try:
        response = requests.get(article_url)
        soup = BeautifulSoup(response.content, 'html.parser')
        
     
        content_div = soup.find('div', class_='cms-body') or \
                      soup.find('article') or \
                      soup.find('div', class_='content-detail')
        
        if content_div:
            return content_div.get_text(separator='\n', strip=True)
        else:
            return "Không có nội dung"
    except :
        return "Lỗi khi lấy nội dung"
