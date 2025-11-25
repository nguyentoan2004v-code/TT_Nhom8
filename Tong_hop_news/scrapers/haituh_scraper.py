import requests
import datetime
from bs4 import BeautifulSoup

def get_articles():
    source_name = "24h"
    url = "https://www.24h.com.vn/"
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Referer': 'https://www.google.com/'
    }
    print(f"[{source_name}] Đang kết nối tới {url}...")
    try:
        response = requests.get(url, headers=headers, timeout=15)
        soup = BeautifulSoup(response.content, 'html.parser')
        articles = []
        candidate_links = []
        headings = soup.find_all(['h2', 'h3', 'h4']) 
        for h in headings:
            a = h.find('a')
            if a and a.get('href'):
                candidate_links.append(a)
        if len(candidate_links) < 5:
            links_with_title = soup.find_all('a', attrs={'title': True})
            candidate_links.extend(links_with_title)
        unique_links = []
        seen = set()
        count = 0
        for link_tag in candidate_links:
            if count >= 6: break
            href = link_tag.get('href')
            title = link_tag.get('title') or link_tag.text.strip()
            if href and '.html' in href and title and len(title) > 15:
                if href not in seen and 'du-bao-thoi-tiet' not in href and 'lich-thi-dau' not in href:
                    seen.add(href)
                    if not href.startswith('http'):
                        href = 'https://www.24h.com.vn' + href
                    content, published_date = get_article_content(href, headers)
                    if content: 
                        articles.append({
                            'title': title,
                            'link': href,
                            'content': content,
                            'source': '24h',
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
        date_tag = soup.find('time') or soup.find('div', class_='cate-24h-foot-arti-deta-cre-post')
        if date_tag:
            date_text = date_tag.text.strip() 
            try:
                parts = date_text.split(',')
                raw_date = parts[-1] if len(parts) > 1 else date_text
                clean_date = raw_date.replace('ngày', '').split('(GMT')[0].strip()
                dt_object = datetime.datetime.strptime(clean_date, '%d/%m/%Y %I:%M %p')
                published_date = dt_object.strftime('%Y-%m-%d %H:%M:%S')
            except :
                published_date = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        else:
            published_date = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        content_div = soup.find('article', id='article_body') or \
                      soup.find('div', id='article_body') or \
                      soup.find('div', class_='ws-bor') or \
                      soup.find('div', class_='cate-24h-foot-arti-deta-content')
        if content_div:
            content = content_div.get_text(separator='\n', strip=True)
            return content, published_date
        return None, None
    except :
        return None, None