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
    print(f"[{source_name}] Đang kết nối tới {url}...")
    try:
        response = requests.get(url, headers=headers, timeout=15)
        soup = BeautifulSoup(response.content, 'html.parser')
        articles = []
        title_tags = soup.find_all(['h1', 'h2', 'h3', 'h4'], class_='title-news')
        if not title_tags:
            title_tags = soup.find_all('a', attrs={'data-medium': True})
        count = 0
        for tag in title_tags:
            if count >= 6: break 
            link_tag = tag if tag.name == 'a' else tag.find('a') 
            if link_tag:
                title = link_tag.get('title') or link_tag.text.strip()
                link = link_tag.get('href')
                if link and not link.startswith('http'):
                   link = 'https://vnexpress.net' + link
                if 'video' in link or 'podcast' in link: continue
                if title and link:
                    content, published_date = get_article_content(link, headers)
                    if content:
                        articles.append({
                            'title': title,
                            'link': link,
                            'content': content,
                            'source': 'vnexpress',
                            'published_date': published_date
                        })
                        count += 1
        return articles
    except Exception as e:
        print(f"[{source_name}] Lỗi: {e}")
        return []
def get_article_content(article_url, headers):
    try:
        response = requests.get(article_url, headers=headers, timeout=10)
        soup = BeautifulSoup(response.content, 'html.parser')
        published_date = None
        date_tag = soup.find('span', class_='date')
        if date_tag:
            date_text = date_tag.text.strip() 
            try:
                parts = date_text.split(', ')
                if len(parts) >= 2:
                    date_time_str = f"{parts[1]}, {parts[2].split(' (')[0]}"
                    dt_object = datetime.datetime.strptime(date_time_str, '%d/%m/%Y, %H:%M')
                    published_date = dt_object.strftime('%Y-%m-%d %H:%M:%S')
            except:
                published_date = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        else:
            published_date = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        content_div = soup.find('article', class_='fck_detail') or soup.find('div', class_='fck_detail')
        if content_div:
            content = content_div.get_text(separator='\n', strip=True)
            return content, published_date
        return None, None  
    except:
        return None, None