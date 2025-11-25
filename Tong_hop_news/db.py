import mysql.connector

def get_conn():
    return mysql.connector.connect(host="localhost", user="root", password="", database="news_db")
def get_or_create_source_id(conn, source_name):
    """Lấy ID nguồn báo, nếu chưa có thì tạo mới"""
    cur = conn.cursor()
    cur.execute("SELECT id FROM sources WHERE name = %s", (source_name,))
    result = cur.fetchone()
    if result:
        source_id = result[0]
    else:
        cur.execute("INSERT INTO sources (name) VALUES (%s)", (source_name,))
        conn.commit()
        source_id = cur.lastrowid
    cur.close()
    return source_id
def get_category_id_by_name(conn, cat_name):
    """Tìm ID của danh mục dựa trên tên (Ví dụ: Thể thao -> 5)"""
    cur = conn.cursor()
    cur.execute("SELECT id FROM categories WHERE name = %s", (cat_name,))
    result = cur.fetchone()
    cur.close()
    return result[0] if result else None
def add_article_category(conn, article_id, category_id):
    """Lưu mối quan hệ: Bài viết X thuộc Danh mục Y"""
    if not article_id or not category_id: return
    cur = conn.cursor()
    try:
        cur.execute("SELECT * FROM article_category WHERE article_id=%s AND category_id=%s", (article_id, category_id))
        if not cur.fetchone():
            cur.execute("INSERT INTO article_category (article_id, category_id) VALUES (%s, %s)", (article_id, category_id))
            conn.commit()
    except Exception as e:
        print(f"Lỗi gắn nhãn: {e}")
    cur.close()
def save_article(title, link, content, source_name, published_date):
    """Lưu bài báo và QUAN TRỌNG: Trả về ID của bài viết đó"""
    conn = get_conn()
    source_id = get_or_create_source_id(conn, source_name)
    article_id = None
    if source_id is None:
        conn.close()
        return None
    cur = conn.cursor()
    cur.execute("SELECT id FROM articles WHERE link=%s", (link,))
    result = cur.fetchone()
    if result:
        article_id = result[0]
        print(f"--- [ĐÃ CÓ] {title[:50]}...") 
    else:
        try:
            cur.execute("INSERT INTO articles (title, link, content, source_id, published_date) VALUES (%s,%s,%s,%s,%s)",
                        (title, link, content, source_id, published_date)) 
            conn.commit()
            article_id = cur.lastrowid
            print(f"✅ [MỚI] {title[:50]}...")
        except Exception as e:
            print(f"Lỗi lưu bài: {e}")
    cur.close()
    conn.close()
    return article_id 