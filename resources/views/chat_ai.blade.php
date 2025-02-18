<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700,300">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.1.2/css/material-design-iconic-font.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Epilogue:wght@400&family=Finger+Paint&display=swap">
    <title>Chat AI</title>
    <link href="{{ asset('vendor/chat_ai/chat_assets/css/style.css') }}" />
</head>

<body>

    <body>
        <div class="card">
            <div id="header">
                <h1>Chatter box!</h1>
            </div>
            <div class="message-section" id="chatMessages">

            </div>
            <div id="input-section">
                <input id="messageInput" type="text" placeholder="Type a message" autocomplete="off"
                    autofocus="autofocus" />
                <button class="send" id="sendMessageBtn">
                    <div class="circle"><i class="zmdi zmdi-mail-send"></i></div>
                </button>
            </div>
        </div>

        <script src="{{ asset('vendor/chat_ai/chat_assets/js/main.js') }}"></script>
    </body>

</html>
