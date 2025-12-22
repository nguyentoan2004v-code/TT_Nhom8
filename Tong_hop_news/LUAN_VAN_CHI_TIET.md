**Database Schemas**

- **articles**

| Column | Type | Null | Default | Constraints | Description |
|---|---:|:---:|:---:|---|---|
| id | bigint unsigned | NO | AUTO_INCREMENT | PRIMARY KEY | Article PK |
| title | varchar(500) | NO | — | — | Article title |
| link | varchar(191) | YES | NULL | UNIQUE | Source URL (unique) |
| content | longtext | YES | NULL | — | Full HTML/text content |
| image_url | varchar(500) | YES | NULL | — | Main image URL |
| published_date | datetime | YES | NULL | — | Published timestamp |
| source_id | bigint unsigned | YES | NULL | FK -> sources.id | Source reference |
| is_visible | tinyint(1)/boolean | NO | 1 / true | — | Visibility flag |
| loves | int | NO | 0 | — | Like/love counter |
| created_at | timestamp | YES | NULL | — | Created at (Laravel) |
| updated_at | timestamp | YES | NULL | — | Updated at (Laravel) |

- **sources**

| Column | Type | Null | Default | Constraints | Description |
|---|---:|:---:|:---:|---|---|
| id | bigint unsigned | NO | AUTO_INCREMENT | PRIMARY KEY | Source PK |
| name | varchar(255) | NO | — | — | Source name (e.g., VNExpress) |

- **categories**

| Column | Type | Null | Default | Constraints | Description |
|---|---:|:---:|:---:|---|---|
| id | bigint unsigned | NO | AUTO_INCREMENT | PRIMARY KEY | Category PK |
| name | varchar(255) | NO | — | — | Category name |

- **article_category** (pivot)

| Column | Type | Null | Default | Constraints | Description |
|---|---:|:---:|:---:|---|---|
| article_id | bigint unsigned | NO | — | PK (composite), FK -> articles.id | Article FK |
| category_id | bigint unsigned | NO | — | PK (composite), FK -> categories.id | Category FK |

- **comments**

| Column | Type | Null | Default | Constraints | Description |
|---|---:|:---:|:---:|---|---|
| id | bigint unsigned | NO | AUTO_INCREMENT | PRIMARY KEY | Comment PK |
| article_id | bigint unsigned | NO | — | FK -> articles.id | Parent article |
| parent_id | bigint unsigned | YES | NULL | — | Parent comment (thread) |
| name | varchar(255) | NO | — | — | Author display name |
| content | text | NO | — | — | Comment body |
| likes | int | NO | 0 | — | Likes count |
| loves | int | NO | 0 | — | Loves count |
| created_at | timestamp | YES | NULL | — | Created at |
| updated_at | timestamp | YES | NULL | — | Updated at |

- **users**

| Column | Type | Null | Default | Constraints | Description |
|---|---:|:---:|:---:|---|---|
| id | bigint unsigned | NO | AUTO_INCREMENT | PRIMARY KEY | User PK |
| name | varchar(255) | NO | — | — | Full name |
| email | varchar(255) | NO | — | UNIQUE | Login email |
| email_verified_at | timestamp | YES | NULL | — | Verification time |
| password | varchar(255) | NO | — | — | Hashed password |
| remember_token | varchar(100) | YES | NULL | — | Remember me token |
| created_at | timestamp | YES | NULL | — | Created at |
| updated_at | timestamp | YES | NULL | — | Updated at |

- **password_reset_tokens**

| Column | Type | Null | Default | Constraints | Description |
|---|---:|:---:|:---:|---|---|
| email | varchar(255) | NO | — | PRIMARY KEY | Email (key for reset) |
| token | varchar(255) | NO | — | — | Reset token |
| created_at | timestamp | YES | NULL | — | Token creation time |

- **sessions**

| Column | Type | Null | Default | Constraints | Description |
|---|---:|:---:|:---:|---|---|
| id | varchar(...) | NO | — | PRIMARY KEY | Session id |
| user_id | bigint unsigned | YES | NULL | INDEX, FK optional | Linked user |
| ip_address | varchar(45) | YES | NULL | — | IP address |
| user_agent | text | YES | NULL | — | Browser UA |
| payload | longtext | NO | — | — | Session payload |
| last_activity | int | NO | — | INDEX | Last active timestamp |

----

If you want, I can now append these tables into a specific section inside the report or generate a PDF export. Reply which you prefer and I will continue.

**Các hệ thống tương tự**

1. Google News — Hệ tổng hợp tin tức lớn, phân loại theo chủ đề và nguồn, cung cấp luồng tiêu đề và tóm tắt. Tham khảo cho giao diện tổng hợp, bộ lọc và phân loại theo chủ đề.
2. NewsAPI (newsapi.org) — API thương mại cho phép truy vấn tin theo từ khóa, nguồn, thời gian; hữu ích làm nền tảng tham khảo cho endpoint cung cấp tin đã lọc.
3. Feedly / Inoreader — Trình đọc RSS và hệ thống tập trung nguồn, hỗ trợ folder/labeling; tham chiếu cho cách tổ chức nguồn và UX quản lý nhiều nguồn.
4. Newspaper3k / Scrapy / Diffbot — Thư viện và dịch vụ trích xuất nội dung (content extraction) tự động dùng trong nhiều hệ thống thu thập tin; hữu ích cho kỹ thuật tách sapo, thân bài, ảnh.
5. Các trang/portal tin tức nhỏ (ví dụ: VNExpress, TuoiTre) — Là đối tượng mục tiêu và cũng mô hình thực tế để so sánh kết quả trích xuất và độ ổn định parser.

**Cơ sở lý thuyết**

- Web scraping (Thu thập dữ liệu web):
	- HTTP/HTTPS: hiểu các phương thức (GET/POST), header, cookies và trạng thái phản hồi.
	- Politeness: tôn trọng `robots.txt`, giới hạn tốc độ (rate limiting), chèn delays, và sử dụng user-agent hợp lý.
	- Kỹ thuật thu thập: sử dụng `requests`/`httpx` để lấy trang, và `BeautifulSoup`/XPath để phân tích DOM.

- Trích xuất nội dung (Content extraction):
	- Sapo/title/image/body: nhận diện phần tóm tắt (lead), ảnh đại diện và phần bài viết chính bằng selector, heuristics hoặc mô hình ML.
	- Làm sạch HTML: loại bỏ script/style, giải mã entity, xử lý đoạn HTML rời rạc.

- Thiết kế cơ sở dữ liệu và chỉ mục:
	- Chuẩn hóa (normalization): tách `sources`, `categories`, `articles` để tránh lặp dữ liệu.
	- Ràng buộc duy nhất (`UNIQUE(link)`) để ngăn bài trùng lặp — thực tế đã áp dụng trong dự án.
	- Chỉ mục (index) trên `source_id`, `published_date` để truy vấn nhanh theo nguồn/ngày.

- Phân loại và trích xuất thông tin (NLP cơ bản):
	- Phương pháp rule-based: khớp từ khóa (như `KEYWORDS` trong `main.py`) — đơn giản, hiệu quả với tập từ khóa chuẩn.
	- Học máy có giám sát: TF–IDF + Naive Bayes / SVM cho phân lớp chủ đề; yêu cầu dữ liệu gán nhãn để huấn luyện.
	- Mô hình ngôn ngữ hiện đại: embeddings (Word2Vec/BERT) + classifier (logistic/MLP) cho độ chính xác cao hơn và khả năng tổng quát tốt hơn.
	- Topic modeling (LDA) để khám phá chủ đề tiềm ẩn trong tập bài viết lớn.

- Đánh giá mô hình và chất lượng:
	- Các chỉ số: accuracy, precision, recall, F1-score cho phân loại; độ phủ (coverage) và tỉ lệ trùng lặp cho thu thập.
	- Kiểm thử end-to-end: chạy scraper trên tập nguồn mẫu, so sánh số bài thu được, tỉ lệ lỗi trích xuất, và so sánh thủ công một số mẫu.

- Kiến trúc hệ thống:
	- Mô hình điển hình: Crawler → Parser/Extractor → DB (persistence) → Service/API → Frontend (MVC như Laravel).
	- Thành phần bổ trợ: hàng đợi (Redis/RabbitMQ) để xử lý bất đồng bộ, scheduler (cron) cho thu thập định kỳ, worker để scale theo số nguồn.

- Bảo mật, đạo đức và pháp lý:
	- Bảo mật kết nối DB (không lưu mật khẩu plaintext trong repo), xác thực/ủy quyền cho phần admin.
	- Tuân thủ bản quyền & robots.txt; ghi nguồn; không tái xuất bản nội dung có bản quyền khi không có quyền.

- Khả năng mở rộng và vận hành (Scalability & Ops):
	- Tăng song song hóa bộ thu thập bằng worker pools; dùng batching và incremental fetch (theo `published_date` / etag) để giảm tải.
	- Giám sát: logs, health checks, alerting cho lỗi parser và tỷ lệ lỗi mạng.

---

Phần này có thể được di chuyển vào Chương 2 (Nền tảng lý thuyết) của `LUAN_VAN_CHI_TIET.md` nếu bạn muốn tổ chức báo cáo theo cấu trúc chuẩn. Bạn có muốn tôi:

- (A) Chèn nội dung này vào vị trí cụ thể trong file hiện tại (vui lòng cho biết vị trí/tiêu đề chương),
- (B) Tạo một file riêng `CHUONG_2_NEN_TANG_LY_THUYET.md` và liên kết vào báo cáo, hay
- (C) Xuất toàn bộ báo cáo ra PDF ngay bây giờ?

