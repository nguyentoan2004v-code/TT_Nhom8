import time, schedule
from datetime import datetime
from scrapers.vnexpress_scraper import get_articles as vne
from scrapers.laodong_scraper import get_articles as ld
from scrapers.tuoitre_scraper import get_articles as tt
from db import save_article

def collect():
    print(f"\n {datetime.now()}")
    for src, func in [("VNExpress", vne), ("Lao Động", ld), ("Tuổi Trẻ", tt)]:
        print(f" {src}")
        
        for art in func():
            
            save_article(art["title"], art["link"], art["content"], art["source"])

collect()
schedule.every().day.at("08:00").do(collect)
print(" Chờ đến 08:00 mỗi ngày để tự động lấy tin...")
while True:
    schedule.run_pending()
    time.sleep(30)
