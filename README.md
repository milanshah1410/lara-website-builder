# Vitara Clone Laravel Starter

This is a minimal Laravel starter to build an AI-driven app generation platform, inspired by [Vitara.ai](https://vitara.ai).

## Features

- Laravel + Sanctum basic setup
- Chat endpoint using OpenAI GPT-4
- Simple Blade-based Chat UI

## Setup

```bash
git clone https://github.com/yourusername/vitara-clone-laravel.git
cd vitara-clone-laravel
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate
# Add OPENAI_API_KEY in .env
php artisan migrate
php artisan serve
