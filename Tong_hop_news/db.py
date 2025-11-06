import mysql.connector

def get_conn():
    return mysql.connector.connect(host="localhost", user="root", password="", database="news_db")

def save_article(title, link, content, source):
    conn = get_conn()
    cur = conn.cursor()
    cur.execute("SELECT id FROM articles WHERE link=%s", (link,))
    if not cur.fetchone():
        cur.execute("INSERT INTO articles (title, link, content, source_id) VALUES (%s,%s,%s,%s)",
                    (title, link, content, source))
        conn.commit()
        print( title)
    cur.close()
    conn.close()
    
# if __name__ == "__main__":
#     # === THÊM CODE TEST Ở ĐÂY ===
#     print("🔍 TEST SCRAPER TỪ DB.PY:")
#     from scrapers.vnexpress_scraper import get_articles as vne
    
#     articles = vne()
#     print(f"Số bài từ VNExpress: {len(articles)}")
#     for i, art in enumerate(articles[:2]):
#         print(f"Title: '{art['title']}'")
#         print(f"Link: {art['link']}")
#         print("---")
    

#     conn = get_conn()
#     cur = conn.cursor()
#     cur.execute("SELECT id, title, link FROM articles ORDER BY id DESC LIMIT 10")
#     rows = cur.fetchall()

#     print("\n📚 10 bài gần nhất trong cơ sở dữ liệu:\n")
#     for i, (id, title, link) in enumerate(rows, 1):
#         print(f"{i}. {title}\n   {link}\n")

#     cur.close()
#     conn.close()
