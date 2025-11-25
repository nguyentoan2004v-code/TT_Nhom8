import requests
import datetime
from bs4 import BeautifulSoup

def get_articles():
    source_name = "Tuổi Trẻ"
    url = "https://tuoitre.vn/"
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Referer': 'https://www.google.com/'
    }
    print(f"[{source_name}] Đang kết nối tới {url}...")
    try:
        response = requests.get(url, headers=headers, timeout=15)
        soup = BeautifulSoup(response.content, 'html.parser')
        articles = []
        links = []
        box_items = soup.find_all(['div', 'li'], class_=['box-category-item', 'box-li'])
        for item in box_items:
            a = item.find('a')
            if a: links.append(a)
        if not links:
             h3s = soup.find_all('h3')
             for h3 in h3s:
                 a = h3.find('a')
                 if a: links.append(a)
        count = 0
        for link_tag in links:
            if count >= 6: break
            title = link_tag.get('title') or link_tag.text.strip()
            link = link_tag.get('href')
            if link:
                if not link.startswith('http'):
                    link = 'https://tuoitre.vn' + link
                if 'video' in link or 'podcast' in link or 'media' in link: continue
                if title:
                    content, published_date = get_article_content(link, headers)
                    if content:
                        articles.append({
                            'title': title,
                            'link': link,
                            'content': content,
                            'source': 'tuoitre',
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
        date_tag = soup.find('div', attrs={'data-role': 'publishdate'})
        if date_tag:
            date_text = date_tag.text.strip()
            try:
               date_time_str = date_text.split('GMT')[0].strip()
               dt_object = datetime.datetime.strptime(date_time_str, '%d/%m/%Y %H:%M')
               published_date = dt_object.strftime('%Y-%m-%d %H:%M:%S')
            except:
                published_date = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        else:
            published_date = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        content_div = soup.find('div', id='main-detail-body') or \
                      soup.find('div', class_='detail-content') or \
                      soup.find('div', class_='detail-cmain')             
        if content_div:
            content = content_div.get_text(separator='\n', strip=True)
            return content, published_date
        return None, None
    except:
        return None, None