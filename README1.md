# AI Chatbot with Laravel 12, PHP 8, Tailwind, Vanilla JS & Replicate

## Project Title

AI Chatbot Web App

## Description

A web-based AI chatbot built using **Laravel 12**, **PHP 8**, **TailwindCSS (via CDN)**, and **Vanilla JavaScript**. The AI is powered by **Replicate** using the `ibm-granite/granite-3.3-8b-instruct` model. This project provides a modern chat interface, authentication system, and conversation management features.

🔗 **Project URL:** [Demo Project](http://your-project-url.com)

## Technologies Used

- **Backend:** Laravel 12 (PHP 8)
- **Frontend:** Blade Templates, TailwindCSS (via CDN), Vanilla JavaScript
- **Database:** MySQL (or any Laravel-supported DB)
- **AI Integration:** Replicate API (`ibm-granite/granite-3.3-8b-instruct`)

## Features

- 🔐 **Authentication System**: Register & Login with custom forms.
- 💬 **Chat Interface**: Responsive and modern chat UI built with Blade + Tailwind.
- 📜 **Infinite Scroll**: Load older messages seamlessly while scrolling.
- ✨ **Conversation Management**: Create, select, and delete conversations.
- 📝 **Simple Context**: Sends only the latest user message (no memory, since the model cannot handle arrays).
- 🔒 **Protected API**: All API endpoints require authentication.

## Folder Structure (Important Parts)

```bash
app/
 ├── Enums/
 │   └── UserMessages.php
 ├── Http/Controllers/
 │   ├── AuthController.php
 │   ├── ConversationController.php
 │   └── MessageController.php
 ├── Models/
 │   ├── Conversation.php
 │   ├── Message.php
 │   └── User.php
 ├── Services/
 │   └── ReplicateServices.php

public/
 ├── index.php
 ├── script.js
 └── style.css

resources/views/
 ├── components/
 │   ├── input.blade.php
 │   └── sidebar.blade.php
 ├── layouts/app.blade.php
 ├── chat.blade.php
 ├── login.blade.php
 └── register.blade.php
```

## Routes Example

```php
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

## Setup Instructions

```bash
# 1. Clone the repository
git clone https://github.com/your-username/your-repo.git
cd your-repo

# 2. Install dependencies
composer install

# 3. Copy .env file
cp .env.example .env

# 4. Generate key
php artisan key:generate

# 5. Run migrations
php artisan migrate

# 6. Start the server
php artisan serve
```

## AI Support Explanation

The app integrates with **Replicate API** using the model `ibm-granite/granite-3.3-8b-instruct`. Since the model does **not support arrays**, only the latest user message is sent as input (no multi-turn memory). The backend handles requests via a custom `ReplicateServices.php`, which manages API calls and returns responses to the frontend chat UI.
