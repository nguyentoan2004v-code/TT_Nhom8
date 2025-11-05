import mysql.connector

def get_conn():
    return mysql.connector.connect(host="localhost", user="root", password="", database="news_db")

def save_article(title, link, content, source):
    conn = get_conn()
    cur = conn.cursor()
    cur.execute("SELECT id FROM articles WHERE link=%s", (link,))
    if not cur.fetchone():
        cur.execute("INSERT INTO articles (title, link, content, source) VALUES (%s,%s,%s,%s)",
                    (title, link, content, source))
        conn.commit()
        print("✅", title)
    cur.close()
    conn.close()
