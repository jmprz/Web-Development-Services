# 🖥️ Web Development Services - EARIST Manila

A streamlined administrative tool built with **Laravel** to manage office posting requests and deadlines.
It features a real-time reminder system with status tracking, urgent deadline alerts, and automated PDF generation for requests.

---

## 🌟 Features

### 📊 Dynamic Dashboard
- Summary cards for **Total**, **Pending**, and **Completed** requests

### ⏰ Office Reminders
- Create, edit, and delete office tasks
- **Mark as Done** toggle with visual feedback (strikethrough / opacity)
- Intelligent **urgent alerts** for deadlines approaching within **3 days**

### 📄 Request Management
- Full CRUD for posting requests
- Automated status updates:
  - Received
  - Processing
  - Completed

### 🧾 PDF Export
- Generate professional PDF documents for any posting request

### 🎨 Modern UI
- Fully responsive design using **Tailwind CSS**
- Interactive components powered by **Alpine.js**

---

## 🛠️ Tech Stack

- **Framework:** Laravel 11
- **Frontend:** Blade, Alpine.js, Tailwind CSS
- **Database:** MySQL / SQLite
- **Icons:** Heroicons
- **PDF Engine:** DomPDF / Barryvdh Laravel DomPDF

---

## 🚀 Getting Started

### ✅ Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL (or any supported database)

---

## 📦 Installation

### 1️⃣ Clone the Repository
```
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
```

### 2️⃣ Install Dependencies
```
composer install
npm install
```

### 3️⃣ Environment Setup
```
cp .env.example .env
```

Configure your database in the .env file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=root
DB_PASSWORD=
```

### 4️⃣ Generate Application Key
```
php artisan key:generate
```

### 5️⃣ Run Migrations
```
php artisan migrate
```

### 6️⃣ Compile Frontend Assets
```
npm run dev
```

### 7️⃣ Start the Development Server
```
php artisan serve
```

---

## 📁 Project Structure

app/Http/Controllers/
- ReminderController.php
- PostingRequestController.php

resources/views/
- dashboard/
- reminders/

routes/
- web.php

database/migrations/
- reminders_table.php
- posting_requests_table.php

---

## 📸 Usage

- **Dashboard:** Overview of all office activity
- **Reminders Tab:** Create reminders, set deadlines, and mark tasks as done
- **Urgent Alerts:** Alerts appear when deadlines are within 3 days

---

## 📄 License

This project is open-sourced software licensed under the [MIT License](LICENSE).

---


