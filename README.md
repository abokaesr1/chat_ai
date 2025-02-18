# Chat AI Laravel Package

## Overview

The **Chat AI Laravel Package** is a smart chatbot integration for Laravel applications. It dynamically retrieves responses from multiple sources, prioritizing efficiency and accuracy:

1. **Database** – Checks for an existing response.
2. **ChatGPT** – If not found in the database, it queries OpenAI's ChatGPT.
3. **Gemini AI** – If ChatGPT reaches its limit, it switches to Google Gemini.
4. **Google Search** – If no AI-generated response is available, it fetches relevant search results.
5. **Self-Learning** – If an answer is not found, it is stored in the database for future queries.

## Features

- **Multi-source response system** (Database → ChatGPT → Gemini → Google Search).
- **Self-learning AI** – Stores new questions and answers.
- **Efficient fallback mechanism** for continuous availability.
- **Simple API interface** for easy integration.
- **Optimized for performance** using caching and similarity checks.

## Installation

### 1. Install via Composer

```sh
composer require mohammadsalamat/chat_ai
```

### 2. Publish Configuration (Optional)

```sh
php artisan vendor:publish --tag=chat-ai-config
```

### 3. Run Migrations

```sh
php artisan migrate
```
This will run the migrations and create a new database table called 'Questions'

### 4. Add API Keys to `.env`

Obtain API keys for OpenAI, Gemini, and Google Search, then update your `.env` file:

```env
CHATGPT_API_KEY=your_chatgpt_api_key
GEMINI_API_KEY=your_gemini_api_key
GOOGLE_SEARCH_API_KEY=your_google_search_api_key
GOOGLE_SEARCH_CX=your_google_search_cx
```

## Usage

### 1. Use in Controller or Route

```php
use Salamat\chat_ai\Http\Controllers\ChatAiController;

public function sendMessage(Request $request, ChatAiController $controller)
    {
        $response = $controller->generateText($request);
        return $response;
    }
```
### OR you can use the normal format

```php
use Salamat\chat_ai\Http\Controllers\ChatAiController;

public function sendMessage(Request $request )
    {
        $controller = new ChatAiController();
        $response = $controller->generateText($request);
        return $response;
    }
```
### Create a Post Route to send a message to the chat

```php

Route::post('/sendMessage', [Controller::class, 'sendMessage']);

```
### 2. Frontend Integration

Make an AJAX request to your Laravel endpoint: you will find a custom view (resources/views/chat_ai) including the following Ajax request.

```js
 fetch('/sendMessage', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token properly placed
                            },

                            body: JSON.stringify({
                                message: messageText
                            })
                        })
.then(response => response.json())
.then(data => console.log(data));
```

## Configuration

The package includes a config file located at `config/chat_ai.php` where you can customize settings such as:

- API Limits
- Similarity Threshold
- Caching Options

## Contributing

Feel free to submit pull requests or open issues for bug fixes and feature requests.

## License

This package is open-source and available under the [MIT License](LICENSE).
