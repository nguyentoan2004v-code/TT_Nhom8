from datetime import datetime
import json
import os
import sys
from scrapers.vnexpress_scraper import get_articles as vne
from scrapers.haituh_scraper import get_articles as haitu
from scrapers.tuoitre_scraper import get_articles as tt
from db import save_article, get_conn, get_category_id_by_name, add_article_category

# Fix UTF-8 encoding on Windows
if sys.platform.startswith('win'):
    import io
    sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
    sys.stderr = io.TextIOWrapper(sys.stderr.buffer, encoding='utf-8')

KEYWORDS = {
    "Thể thao": [

        "bóng đá", "v-league", "hlv", "cầu thủ", "đội tuyển", "trận đấu", "bàn thắng", "cup", 
        "vận động viên", "giải đấu", "fifa", "sea games", "bóng rổ", "bóng chuyền", "quần vợt", 
        "câu lạc bộ", "clb", "tuyển thủ", "sân vận động", "số 10", "tiền vệ", "đội bóng", "trận hòa", "đối thủ",
        "pickleball", "padel", "teqball", "var", "việt vị", "chuyển nhượng", "hat-trick", 
        "ghi bàn", "kiến tạo", "thẻ đỏ", "thẻ vàng", "huy chương", "thế vận hội", "olympic"
    ],
    "Kinh doanh": [
        "chứng khoán", "ngân hàng", "tỷ giá", "doanh nghiệp", "kinh tế", "lãi suất", "bất động sản", 
        "xăng dầu", "tài chính", "thị trường", "khoản vay", "tập đoàn", "lạm phát", "gdp", "cổ phiếu", 
        "trái phiếu", "vàng sjc", "đấu giá", "xuất khẩu", "nhập khẩu", "thương mại", "ceo", "phá sản"
    ],
    "Công nghệ": [
        "smartphone", "ai", "trí tuệ nhân tạo", "apple", "samsung", "công nghệ", "ứng dụng", "phần mềm", 
        "virus", "chatgpt", "iphone", "google", "metaverse", "chip", "máy tính", "laptop", "blockchain", 
        "kỹ thuật số", "an ninh mạng", "hacker", "mạng xã hội", "nền tảng", "algorithm", "thuật toán"
    ],
    "Pháp luật": [
        "công an", "bắt giữ", "khởi tố", "tội phạm", "án mạng", "tòa án", "vi phạm", "trộm cắp", 
        "điều tra", "cảnh sát", "xét xử", "tù", "ma túy", "tham nhũng", "bị cáo", "bị can", 
        "luật sư", "hình sự", "dân sự", "tranh chấp", "lừa đảo"
    ],
    "Giáo dục": [
        "học sinh", "sinh viên", "đại học", "trường học", "thi tốt nghiệp", "bộ giáo dục", "thầy cô", 
        "học phí", "tuyển sinh", "tiến sĩ", "bằng cấp", "giáo viên", "đào tạo", "du học", "học bổng", "sách giáo khoa"
    ],
    "Sức khỏe": [
        "bệnh viện", "bác sĩ", "ung thư", "covid", "sức khỏe", "y tế", "thuốc", "phẫu thuật", 
        "virus", "dinh dưỡng", "ngộ độc", "vacxin", "khám bệnh", "bệnh nhân", "cấp cứu", 
        "dịch bệnh", "đột quỵ", "tiểu đường", "thực phẩm chức năng"
    ],
    "Giải trí": [
        "hoa hậu", "ca sĩ", "diễn viên", "showbiz", "nghệ sĩ", "phim", "nhạc", "concert", 
        "người mẫu", "sao việt", "thảm đỏ", "truyền hình", "mv", "liveshow", "rạp phim", "drama"
    ],
  
    "Đời sống": [
        "du lịch", "ẩm thực", "gia đình", "tình yêu", "mẹo vặt", "thời trang", "làm đẹp", "hôn nhân", 
        "nhà cửa", "món ngon", "địa điểm", "check-in"
    ],
    "Chính trị - Xã hội": [
        "chính phủ", "thủ tướng", "bộ trưởng", "nghị định", "quốc hội", "tổng bí thư", "bão", "lũ", 
        "giao thông", "nhà nước", "thời tiết", "chủ tịch", "tỉnh", "thành phố", "ủy ban", "đề xuất", 
        "quy hoạch", "dự án", "cao tốc"
    ]
}
# ----------------------------------

def classify_news(title, content_snippet=""):
    """Hàm tự động đoán chủ đề bài báo dựa trên Tiêu đề và 1 phần Nội dung"""
    text = (title + " " + content_snippet).lower()
    for category, keys in KEYWORDS.items():
        for key in keys:
            if key in text:
                return category
    return "Chính trị - Xã hội"

def update_status(status, message=""):
    """Update scrape_status.json for admin dashboard polling"""
    status_file = os.path.join(os.path.dirname(__file__), "scrape_status.json")
    try:
        data = {
            "status": status,
            "timestamp": datetime.now().isoformat(),
        }
        if message:
            data["message"] = message
        with open(status_file, "w", encoding="utf-8") as f:
            json.dump(data, f, indent=2, ensure_ascii=False)
    except Exception as e:
        print(f"Warning: Could not update status: {e}") 

def collect():
    update_status("running", "Scraper starting...")
    print(f"\n--- BẮT ĐẦU QUÉT: {datetime.now()} ---")
    sys.stdout.flush()
    
    conn = None
    total_articles = 0
    
    try:
        conn = get_conn()
        sources = [("VnExpress", vne), ("24h", haitu), ("Tuổi Trẻ", tt)]
        
        for src_name, func in sources:
            print(f"\n>> Đang lấy tin từ: {src_name}...")
            sys.stdout.flush()
            update_status("running", f"Fetching from {src_name}...")
            
            try:
                articles = func()
                src_article_count = 0
                
                for art in articles:
                    try:
                        article_id = save_article(
                            art["title"], art["link"], art["content"], 
                            art["source"], art["published_date"], art["image_url"]
                        )
                        
                        if article_id:
                            snippet = art["content"][:100] if art["content"] else ""
                            cat_name = classify_news(art["title"], snippet)
                            cat_id = get_category_id_by_name(conn, cat_name)
                            if cat_id:
                                add_article_category(conn, article_id, cat_id)
                                src_article_count += 1
                                total_articles += 1
                    except Exception as e:
                        print(f"  ⚠ Lỗi xử lý bài: {e}")
                        sys.stdout.flush()
                        continue
                
                print(f"✓ Lấy được {src_article_count} bài từ {src_name}")
                sys.stdout.flush()
            except Exception as e:
                print(f"✗ Lỗi tại {src_name}: {e}")
                sys.stdout.flush()
                continue
    
    except Exception as e:
        print(f"✗ Lỗi database: {e}")
        sys.stdout.flush()
        update_status("error", f"DB error: {e}")
    
    finally:
        if conn:
            try:
                conn.close()
            except:
                pass
    
    print(f"\n--- HOÀN TẤT (Tổng: {total_articles} bài mới) ---")
    sys.stdout.flush()
    update_status("finished", f"Completed. {total_articles} articles added.")

if __name__ == "__main__":
    collect()