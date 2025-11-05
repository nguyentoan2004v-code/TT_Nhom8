import requests
from bs4 import BeautifulSoup

def get_articles():
    url = "https://tuoitre.vn/"
    soup = BeautifulSoup(requests.get(url).text, "html.parser")
    data = []
    for a in soup.select("article a[href]")[:5]:
        title = a.get_text(strip=True)
        link = a["href"]
        if link.startswith("https"):
            content = get_content(link)
            data.append({"title": title, "link": link, "content": content, "source": "Tuổi Trẻ"})
    return data

def get_content(url):
    try:
        soup = BeautifulSoup(requests.get(url).text, "html.parser")
        return "\n".join(p.get_text(strip=True) for p in soup.select("p"))
    except:
        return ""
