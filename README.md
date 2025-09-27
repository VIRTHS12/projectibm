# AI Chatbot — Laravel 12 + PHP 8 + Vanilla JS

> README untuk project chatbot AI yang menggunakan Laravel 12 (PHP 8) sebagai backend, Tailwind CSS (via CDN) untuk styling, dan vanilla JavaScript untuk frontend. Model AI dihosting lewat Replicate: `ibm-granite/granite-3.3-8b-instruct`.

---

## Project title

**AI Chatbot (Laravel 12 + Replicate ibm-granite 8B)**

---

## Description

Aplikasi chat berbasis web yang memungkinkan pengguna berinteraksi dengan model AI (`ibm-granite/granite-3.3-8b-instruct`) via Replicate. Fitur utama meliputi autentikasi (dibuat manual), manajemen percakapan, infinite scroll pada riwayat pesan, dan API yang terproteksi.

> Catatan: Model **tidak mendukung input dalam bentuk array**, sehingga **tidak ada memory conversation** yang kompleks. Chat hanya mengirim **satu pesan terakhir user** sebagai prompt ke model.

---

## Technologies used

- Backend: **Laravel 12**, **PHP 8**
- Frontend: **Vanilla JavaScript**, **Tailwind CSS (via CDN)**
- Authentication: Custom Login & Register (dibuat manual)
- AI provider: **Replicate** (model: `ibm-granite/granite-3.3-8b-instruct`)
- Database: MySQL / SQLite (pilih sesuai kebutuhan)
- HTTP Client: Laravel `Http::` (built-in)

---

## Features

- 🔐 **Sistem Autentikasi**: Register & Login (dibuat manual, tanpa package tambahan).
- 💬 **Antarmuka Chat**: Tampilan modern, responsif, menggunakan Tailwind CSS via CDN.
- 📜 **Infinite Scroll**: Memuat pesan lama otomatis ketika scroll ke atas.
- ✨ **Manajemen Percakapan**: Create, show, delete, clear all.
- 📝 **Konteks Pesan Terakhir**: Model hanya menerima **satu pesan terakhir** user sebagai prompt.
- 🔒 **API Terproteksi**: Hanya bisa diakses user yang login.

---

## Project structure

Struktur folder utama (sesuai project ini):

```
app/
  Enums/
    UserMessages.php
  Http/
    Controllers/
      AuthController.php
      Controller.php
      ConversationController.php
      MessageController.php
  Models/
    Conversation.php
    Message.php
    User.php
  Providers/
  Services/
    ReplicateServices.php

public/
  build/
  index.php
  robots.txt
  script.js
  style.css

resources/
  views/
    components/
      input.blade.php
      sidebar.blade.php
    layouts/
      app.blade.php
    chat.blade.php
    login.blade.php
    register.blade.php
```

---

## Routes

**`routes/web.php`**

```php
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('chat');
    })->name('chat');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('api')->group(function () {
        Route::get('/conversations', [ConversationController::class, 'index']);
        Route::post('/conversations', [ConversationController::class, 'store']);
        Route::get('/conversations/{conversation}', [ConversationController::class, 'show']);
        Route::delete('/conversations/{conversation}', [ConversationController::class, 'destroy']);
        Route::delete('/conversations', [ConversationController::class, 'clearAll']);

        Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
        Route::post('/messages', [MessageController::class, 'store']);
    });
});
```

---

## Setup instructions

1. **Clone repo**

```bash
git clone <repo-url>
cd <repo-folder>
```

2. **Install dependencies**

```bash
composer install
```

3. **Buat `.env`** — copy dari contoh

```bash
cp .env.example .env
php artisan key:generate
```

Tambahkan variabel penting untuk Replicate:

```
REPLICATE_API_TOKEN=your_replicate_api_token_here
REPLICATE_MODEL=ibm-granite/granite-3.3-8b-instruct
```

4. **Database**

```bash
php artisan migrate
```

5. **Jalankan aplikasi**

```bash
php artisan serve
```

---

## AI support explanation

- Karena model tidak menerima array, **tidak ada memory conversation**. Sistem hanya mengirim **satu pesan terakhir user** sebagai input (`prompt`).
- Output model langsung disimpan sebagai pesan dengan role `assistant`.
- Token Replicate tetap disimpan aman di `.env`.

---
