# CS251_Project

### 1️⃣ Clone Repo จาก Branch `dev`
```bash
git clone -b dev https://github.com/your-username/your-repo.git
```

### 2️⃣ ตรวจสอบ Branch ปัจจุบัน
```bash
git branch
```

---

## ⚡️ การทำงานประจำวัน (Daily Workflow)


### 🔹 สร้าง Branch ใหม่ (Feature Branch)
เพื่อความเป็นระเบียบ ทุกฟีเจอร์หรือการแก้ไข ควรสร้าง branch ใหม่:
```bash
git branch ชื่อใหม่
git checkout ชื่อbranch
```

---

### 🔹 เพิ่มไฟล์ (Add) และ Commit
หลังจากแก้ไขโค้ด:
```bash
git add .
git commit -m "สรุปสิ่งที่แก้ไข"
```

---

### 🔹 ส่งงานขึ้น Remote (Push)
ถ้าเป็นการ push ครั้งแรกของ branch:
```bash
git push -u origin ชื่อbranch
```
ครั้งถัดไปใช้แค่:
```bash
git push
```

---

## 📝 สรุปคำสั่งที่ใช้บ่อย

| การทำงาน         | คำสั่งตัวอย่าง                          |
|------------------|-------------------------------------------|
| Clone dev        | `git clone -b dev [URL_REPO]`             |
| Pull             | `git pull origin dev`                     |
| New Branch       | `git branch feature-xyz`             |
| Add              | `git add .`                               |
| Commit           | `git commit -m "message"`                 |
| Push             | `git push -u origin feature-xyz`          |
| Checkout         | `git checkout dev`                        |
| Merge            | `git merge feature-xyz`                   |

---

## 🎯 หมายเหตุ
- **Branch หลัก:**
  - `main` = สำหรับ production (ห้าม push ตรง)
  - `dev` = สำหรับ development
- ทุกการแก้ไข ควรทำใน feature branch แล้วค่อย merge เข้า `dev`
- Sync ข้อมูลบ่อย ๆ เพื่อหลีกเลี่ยง conflict

---

