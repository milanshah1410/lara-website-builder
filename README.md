# Vitara Clone Laravel Starter

This is a minimal Laravel starter to build an AI-powered website generation platform — inspired by [Vitara.ai](https://vitara.ai).

---

## ✨ Features

- 🔐 Laravel Breeze authentication with Sanctum
- 💬 Chat-based UI powered by OpenAI (GPT-4/GPT-4o)
- 🧠 Dynamic Blade view generator
- 🎨 Tailwind CSS integration
- 📂 Modular structure with layouts, partials, pages
- 📦 File system-based storage for generated sites
- 🧰 Easy to extend for SaaS or commercial use

---

## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/milanshah1410/lara-website-builder.git
cd lara-website-builder
```

### 2. Install Dependencies
```bash
composer install
npm install && npm run dev
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
Then add your OpenAI key to .env:
OPENAI_API_KEY=sk-xxxxxxx
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Serve the App
```bash
php artisan serve
```
Visit http://127.0.0.1:8000 in your browser.

🧪 Try a Sample Prompt
Try this in the chat box:

A 4-page sweet shop website with Home, About, Menu, and Contact pages.
The system will generate Blade templates, Tailwind CSS, and JavaScript interactivity automatically.

📁 Directory Structure

    resources/
    ├── views/
    │   ├── layouts/
    │   ├── partials/
    │   ├── pages/
    │   └── chat.blade.php
    routes/
    ├── web.php
    app/
    ├── Http/
    │   ├── Controllers/
    │   │   └── ChatController.php
    storage/
    ├── app/
    │   └── generated/{project_id}/


📦 Tech Stack
Laravel 11

Laravel Breeze

Tailwind CSS (CDN or compiled)

OpenAI GPT-4 or GPT-4o

JavaScript (vanilla)

Blade templating


🔐 Authentication
Authentication is handled using Laravel Breeze with Sanctum. Register/login is required before accessing the AI generator.


🧠 Prompt Format Tips
Use natural language or describe your website like:

"A SaaS landing page with pricing, features, and contact form."

"A restaurant site with home, about, menu, and reservation pages."

✅ License
MIT License — feel free to modify, extend, and deploy.

📬 Contact
[Milan Shah] - [milanshah1410@gmail.com]
