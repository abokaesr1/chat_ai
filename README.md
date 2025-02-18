# **Chat AI Laravel Package**  

## **Overview**  

The **Chat AI Laravel Package** is an intelligent chatbot integration for Laravel applications. It retrieves responses dynamically from multiple sources, ensuring accuracy and efficiency:  

1. **Database** – Checks for an existing response.  
2. **ChatGPT** – If no response is found in the database, it queries OpenAI’s ChatGPT.  
3. **Gemini AI** – If ChatGPT reaches its limit, it switches to Google Gemini.  
4. **Google Search** – If no AI-generated response is available, it fetches relevant search results.  
5. **Self-Learning** – If an answer is not found, it is stored in the database for future queries.  

## **Features**  

- **Multi-source response system** (Database → ChatGPT → Gemini → Google Search).  
- **Self-learning AI** – Stores new questions and answers.  
- **Efficient fallback mechanism** for uninterrupted responses.  
- **Simple API interface** for easy integration.  
- **Optimized for performance** with caching and similarity checks.  

## **Installation**  

### **1. Install via Composer**  

Run the following command to install the package:  

```sh
composer require mohammadsalamat/chat_ai
```  

### **2. Publish Configuration (Optional)**  

```sh
php artisan vendor:publish
```  

This command publishes the migration and view files.  

### **3. Run Migrations**  

```sh
php artisan migrate
```  

This creates a new database table called `questions` to store chatbot interactions.  

### **4. Add API Keys to `.env`**  

Obtain API keys for OpenAI, Gemini, and Google Search, then update your `.env` file:  

```env
CHATGPT_API_KEY=your_chatgpt_api_key
GEMINI_API_KEY=your_gemini_api_key
GOOGLE_SEARCH_API_KEY=your_google_search_api_key
GOOGLE_SEARCH_CX=your_google_search_cx
```  

## **Usage**  

### **1. Use in a Controller or Route**  

#### **Dependency Injection Approach**  

```php
use Salamat\chat_ai\Http\Controllers\ChatAiController;

public function sendMessage(Request $request, ChatAiController $controller)
{
    return $controller->generateText($request);
}
```  

#### **Instantiating the Controller Manually**  

```php
use Salamat\chat_ai\Http\Controllers\ChatAiController;

public function sendMessage(Request $request)
{
    $controller = new ChatAiController();
    return $controller->generateText($request);
}
```  

### **2. Create a POST Route to Send a Message**  

```php
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::post('/sendMessage', [Controller::class, 'sendMessage']);
```  

### **3. Frontend Integration**  

Send an AJAX request to your Laravel endpoint. A custom view is available at `resources/views/chat_ai`, including the following AJAX request:  

```js
fetch('/sendMessage', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token for security
    },
    body: JSON.stringify({
        message: messageText
    })
})
.then(response => response.json())
.then(data => console.log(data));
```  

## **Configuration**  

The package includes a configuration file located at `config/chat_ai.php`, where you can customize settings such as:  

- **API Limits**  
- **Similarity Threshold**  
- **Caching Options**  

## **Contributing**  

Feel free to submit pull requests or open issues for bug fixes and feature requests.  

## **License**  

This package is open-source and available under the [MIT License](LICENSE).  

---
