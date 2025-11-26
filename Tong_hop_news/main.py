from datetime import datetime
from scrapers.vnexpress_scraper import get_articles as vne
from scrapers.haituh_scraper import get_articles as haitu
from scrapers.tuoitre_scraper import get_articles as tt
from db import save_article, get_conn, get_category_id_by_name, add_article_category

# TỪ ĐIỂN PHÂN LOẠI
KEYWORDS = {
    "Thể thao": ["bóng đá", "v-league", "hlv", "cầu thủ", "đội tuyển", "seagames", "world cup", "thể thao", "trận đấu", "bàn thắng", "messi", "ronaldo"],
    "Giáo dục": ["học sinh", "sinh viên", "đại học", "giáo dục", "trường học", "thi tốt nghiệp", "bộ giáo dục", "thầy cô", "học phí"],
    "Pháp luật": ["công an", "bắt giữ", "khởi tố", "tội phạm", "án mạng", "tòa án", "vi phạm", "trộm cắp", "điều tra", "cảnh sát", "xét xử", "tù"],
    "Kinh doanh": ["chứng khoán", "ngân hàng", "tỷ giá", "doanh nghiệp", "kinh tế", "lãi suất", "thị trường", "vàng", "bất động sản", "xăng dầu", "usd", "tài chính"],
    "Công nghệ": ["smartphone", "ai", "trí tuệ nhân tạo", "apple", "samsung", "công nghệ", "ứng dụng", "phần mềm", "virus", "chatgpt", "iphone"],
    "Sức khỏe": ["bệnh viện", "bác sĩ", "ung thư", "covid", "sức khỏe", "y tế", "thuốc", "phẫu thuật", "virus", "dinh dưỡng", "ngộ độc"],
    "Giải trí": ["hoa hậu", "ca sĩ", "diễn viên", "showbiz", "nghệ sĩ", "phim", "nhạc", "concert", "người mẫu"],
    "Thế giới": ["mỹ", "nga", "ukraine", "trung quốc", "tổng thống", "quân sự", "liên hợp quốc", "chiến sự", "israel", "bầu cử"]
}

def classify_news(title):
    if not title: return "Chính trị - Xã hội"
    title_lower = title.lower()
    for category, keys in KEYWORDS.items():
        for key in keys:
            if key in title_lower:
                return category
    return "Chính trị - Xã hội"

def collect():
    print(f"\n--- BẮT ĐẦU QUÉT: {datetime.now()} ---")
    conn = get_conn()
    
    for src_name, func in [("VNExpress", vne), ("24h", haitu), ("Tuổi Trẻ", tt)]:
        print(f"\n>> Đang lấy tin từ: {src_name}...")
        try:
            articles = func()
            print(f"   -> Tìm thấy {len(articles)} tin tiềm năng.")
            
            for art in articles:
                # Lưu bài (truyền image_url)
                article_id = save_article(
                    art["title"], art["link"], art["content"], 
                    art["source"], art["published_date"], art["image_url"]
                )
                
                # Phân loại
                if article_id:
                    cat_name = classify_news(art["title"])
                    cat_id = get_category_id_by_name(conn, cat_name)
                    if cat_id:
                        add_article_category(conn, article_id, cat_id)
        except Exception as e:
            print(f"Lỗi nguồn {src_name}: {e}")

    conn.close()
    print("\n--- HOÀN TẤT ---")

if __name__ == "__main__":
    collect()