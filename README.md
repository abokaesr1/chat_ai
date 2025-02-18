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
composer require salamat/chat-ai
```

### 2. Publish Configuration (Optional)

```sh
php artisan vendor:publish --tag=chat-ai-config
```

### 3. Run Migrations

```sh
php artisan migrate
```

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
use Salamat\ChatAi\ChatAiService;

$chat = new ChatAiService();
$response = $chat->getResponse('What is Laravel?');

return response()->json($response);
```

### 2. Frontend Integration

Make an AJAX request to your Laravel endpoint:

```js
fetch('/chat-ai/query', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ message: 'What is Laravel?' })
})
.then(response => response.json())
.then(data => console.log(data));
```

## Styling

### 1. Publish Public Assets

```sh
php artisan vendor:publish --tag=public
```

### 2. Include the CSS in Blade Template

```blade
<link rel="stylesheet" href="{{ asset('vendor/chat_ai/style.css') }}">
```

### 3. Example CSS (`style.css`)

```css
.chat-container {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    max-width: 600px;
    margin: auto;
}

.chat-message {
    background-color: #007bff;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}

.chat-message.user {
    background-color: #28a745;
}
```

Config# Chat AI Laravel Package




\## Overview



The \*\*Chat AI Laravel Package\*\* is a smart chatbot integration for Laravel applications. It dynamically retrieves responses from multiple sources, prioritizing efficiency and accuracy:



1\. \*\*Database\*\* – Checks for an existing response.

2\. \*\*ChatGPT\*\* – If not found in the database, it queries OpenAI's ChatGPT.

3\. \*\*Gemini AI\*\* – If ChatGPT reaches its limit, it switches to Google Gemini.

4\. \*\*Google Search\*\* – If no AI-generated response is available, it fetches relevant search results.

5\. \*\*Self-Learning\*\* – If an answer is not found, it is stored in the database for future queries.



\## Features



\- \*\*Multi-source response system\*\* (Database → ChatGPT → Gemini → Google Search).

\- \*\*Self-learning AI\*\* – Stores new questions and answers.

\- \*\*Efficient fallback mechanism\*\* for continuous availability.

\- \*\*Simple API interface\*\* for easy integration.

\- \*\*Optimized for performance\*\* using caching and similarity checks.



\## Installation



\### 1. Install via Composer



\`\`\`sh

composer require salamat/chat-ai

\`\`\`



\### 2. Publish Configuration (Optional)



\`\`\`sh

php artisan vendor\:publish --tag=chat-ai-config

\`\`\`



\### 3. Run Migrations



\`\`\`sh

php artisan migrate

\`\`\`



\### 4. Add API Keys to \`.env\`



Obtain API keys for OpenAI, Gemini, and Google Search, then update your \`.env\` file:



\`\`\`env

CHATGPT\_API\_KEY=your\_chatgpt\_api\_key

GEMINI\_API\_KEY=your\_gemini\_api\_key

GOOGLE\_SEARCH\_API\_KEY=your\_google\_search\_api\_key

GOOGLE\_SEARCH\_CX=your\_google\_search\_cx

\`\`\`



\## Usage



\### 1. Use in Controller or Route



\`\`\`php

use Salamat\ChatAi\ChatAiService;



\$chat = new ChatAiService();

\$response = \$chat->getResponse('What is Laravel?');



return response()->json(\$response);

\`\`\`



\### 2. Frontend Integration



Make an AJAX request to your Laravel endpoint:



\`\`\`js

fetch('/chat-ai/query', {

&#x20;   method: 'POST',

&#x20;   headers: {'Content-Type': 'application/json'},

&#x20;   body: JSON.stringify({ message: 'What is Laravel?' })

})

.then(response => response.json())

.then(data => console.log(data));

\`\`\`



\## Styling



\### 1. Publish Public Assets



\`\`\`sh

php artisan vendor\:publish --tag=public

\`\`\`



\### 2. Include the CSS in Blade Template



\## Configuration



The package includes a config file located at \`config/chat\_ai.php\` where you can customize settings such as:



\- API Limits

\- Similarity Threshold

\- Caching Options



\## Contributing



Feel free to submit pull requests or open issues for bug fixes and feature requests.



\## License



This package is open-source and available under the [MIT License]\(LICENSE).



Feel free to submit pull requests or open issues for bug fixes and feature requests.

## License

This package is open-source and available under the [MIT License](LICENSE).

