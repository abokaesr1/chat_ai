<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.1.2/css/material-design-iconic-font.min.css">

<style>
    * {
        font-size: 17px;
        font-family: "Montserrat", sans-serif;
    }

    html {
        --scrollbarBG: #fff;
        --thumbBG: #90a4ae;
    }

    body {
        background: #ccc;
    }

    body .card {
        height: 45vw;
        width: 35vw;
        background-color: white;
        margin-left: 30vw;
        margin-top: 5vw;
        box-shadow: 2vw 2vw 12vw 3vw #ccc;
    }

    body .card #header {
        height: 5vw;
        background: linear-gradient(280deg, rgba(193, 69, 151, 1) 0%, rgba(81, 0, 88, 1) 62%);
        padding: 0vw;
    }

    body .card #header h1 {
        color: #fff;
        font-size: 2vw;
        font-family: "Finger Paint", cursive;
        padding: 1vw;
    }

    body .card .message-section::-webkit-scrollbar {
        width: 10px;
    }

    body .card .message-section {
        height: 32vw;
        padding: 0 2.5vw;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--thumbBG) var(--scrollbarBG);
    }

    body .card .message-section::-webkit-scrollbar-track {
        background: var(--scrollbarBG);
    }

    body .card .message-section::-webkit-scrollbar-thumb {
        background-color: var(--thumbBG);
        border-radius: 6px;
        border: 3px solid var(--scrollbarBG);
    }

    body .card .message-section #bot,
    body .card .message-section #user {
        position: relative;
        bottom: 0;
        min-height: 1.5vw;
        border: 0.15vw solid #777;
        background-color: #fff;
        border-radius: 0px 1.5vw 1.5vw 1.8vw;
        padding: 1vw;
        margin: 1.5vw 0;
    }

    body .card .message-section #user {
        border: 1.5px solid #ccc;
        border-radius: 1.5vw 0vw 1.5vw 1.8vw;
        background-color: #f2f2f2;
        float: right;
    }

    body .card .message-section #user #user-response {
        color: #000;
    }

    #bot-response {
        color: #000;
    }

    .chat-info p,
    .chat-info ul,
    .chat-info li,
    .chat-info a,
    .chat-info div {

        font-size: 17px;
        margin: 0;
        padding: 0;
        line-height: 1.3,
            list-style: none;
    }

    body .card .message-section .message {
        color: #484848;
        clear: both;
        line-height: 1.2vw;
        font-size: 1.2vw;
        padding: 8px;
        position: relative;
        margin: 8px 0;
        max-width: 85%;
        word-wrap: break-word;
        z-index: 2;
    }

    body .card #input-section {
        z-index: 1;
        padding: 0 2.5vw;
        display: flex;
        flex-direction: row;
        align-items: flex-end;
        overflow: hidden;
        height: 6vw;
        width: 100%;
    }

    body .card #input-section input {
        color: #484848;
        min-width: 0.5vw;
        outline: none;
        height: 5vw;
        width: 26vw;
        border-top: none;
        border-left: none;
        border-right: none;
        border-bottom: solid #f3f3f3 0.1vw;
    }

    body .card .send {
        background: transparent;
        border: 0;
        cursor: pointer;
        flex: 0 0 auto;
        margin-left: 1.4vw;
        margin-right: 0vw;
        padding: 0;
        position: relative;
        outline: none;
    }

    body .card .send .circle {
        position: relative;
        width: 4.8vw;
        height: 4.8vw;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    body .card .send .circle i {
        font-size: 3vw;
        margin-left: -1vw;
        margin-top: 1vw;
    }

    .info-details {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chat-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .info-details img {
        width: 30px;
        height: auto;
    }

    .info-details .info-name {
        font-size: 15px;
        color: #ccc;
        margin-left: 4px;
    }

    .time {
        font-size: 15px;
        color: #ccc;
    }
</style>
<div class="card">
    <div id="header">
        <h1>CHAT AI</h1>
    </div>
    <div class="message-section" id="chatMessages">

    </div>
    <div id="input-section">
        <input id="messageInput" type="text" placeholder="Type a message" autocomplete="off" autofocus="autofocus" />
        <button class="send" id="sendMessageBtn">
            <div class="circle"><i class="zmdi zmdi-mail-send"></i></div>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('sendMessageBtn').addEventListener('click', sendMessage);

        function sendMessage() {
            let messageInput = document.getElementById('messageInput');
            let messageText = messageInput.value.trim();
            if (!messageText) return;

            let chatMessages = document.getElementById('chatMessages');
            let userMessage = `
            <div class="message" id="bot">
                <div class="chat-info">
                    <div class="info-details">
                        <img src="https://thumbs.dreamstime.com/b/default-avatar-profile-icon-vector-social-media-user-image-182145777.jpg"
                            class="image-bot" alt="">
                        <span class="info-name">User</span>
                    </div>
                    <div class="time">
                        now
                    </div>
                </div>
                <span id="bot-response">${messageText}</span>
            </div>
        `;
            chatMessages.insertAdjacentHTML('beforeend', userMessage);
            messageInput.value = '';
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
                .then(data => {
                    let formattedResponse = data.answers[0].answer;
                    let refrenceAnswer = data.answers[0].refrence;

                    let botResponse = `
                <div class="message" id="user">
                    <div class="chat-info">
                        <div class="info-details">
                            <img src="https://thumbs.dreamstime.com/b/default-avatar-profile-icon-vector-social-media-user-image-182145777.jpg"
                                class="image-bot" alt="">
                            <span class="info-name">${refrenceAnswer}</span>
                        </div>
                        <div class="time">
                            now
                        </div>
                    </div>
                    <span id="user-response">${formattedResponse}</span>
                    </div>
                `;
                    chatMessages.insertAdjacentHTML('beforeend', botResponse);
                })
                .catch(error => console.error('Error:', error));
        }
    });
</script>
