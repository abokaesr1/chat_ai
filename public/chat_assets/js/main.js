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
        fetch('/generatetext', {
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
                let formattedResponse = data.answers[0].answer

                let botResponse = `
                <div class="message" id="user">
                    <div class="chat-info">
                        <div class="info-details">
                            <img src="https://thumbs.dreamstime.com/b/default-avatar-profile-icon-vector-social-media-user-image-182145777.jpg"
                                class="image-bot" alt="">
                            <span class="info-name">Bot</span>
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
