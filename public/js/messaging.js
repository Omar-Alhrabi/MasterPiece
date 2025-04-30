/**
 * Messaging System JavaScript Utilities
 */

// Initialize messaging functionality
function initMessaging() {
    // Auto-scroll chat to bottom
    scrollChatToBottom();
    
    // Message form submission
    initMessageForm();
    
    // Real-time updates (if applicable)
    // Note: For real-time functionality, you would need to implement
    // WebSockets or a polling mechanism here
    
    // Initialize message search
    initMessageSearch();
}

// Scroll chat container to bottom
function scrollChatToBottom() {
    const chatMessages = document.getElementById('chat-messages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

// Initialize message form
function initMessageForm() {
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.querySelector('#messageForm input[name="message"]');
    
    if (messageForm && messageInput) {
        // Auto-submit form on enter
        messageInput.addEventListener('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                messageForm.submit();
            }
        });
        
        // Focus on input when page loads
        messageInput.focus();
        
        // Handle form submission
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (messageInput.value.trim() === '') {
                return;
            }
            
            // Show sending indicator
            const formAction = messageForm.getAttribute('action');
            const formData = new FormData(messageForm);
            
            // Temporarily disable input
            messageInput.disabled = true;
            
            // Create a temporary message element
            const tempMessage = createTempMessage(messageInput.value);
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.appendChild(tempMessage);
            scrollChatToBottom();
            
            // Clear input
            messageInput.value = '';
            
            // Send message via AJAX
            fetch(formAction, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Message sent successfully
                    // Wait for a moment to simulate sending
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    // Handle error
                    tempMessage.querySelector('.message-content').classList.add('bg-danger');
                    tempMessage.querySelector('.message-time').innerHTML = '<small class="text-white-50">Failed to send</small>';
                }
            })
            .catch(error => {
                // Handle network error
                tempMessage.querySelector('.message-content').classList.add('bg-danger');
                tempMessage.querySelector('.message-time').innerHTML = '<small class="text-white-50">Failed to send</small>';
            })
            .finally(() => {
                // Re-enable input
                messageInput.disabled = false;
                messageInput.focus();
            });
        });
    }
}

// Create a temporary message element
function createTempMessage(messageText) {
    const messageItem = document.createElement('div');
    messageItem.className = 'message-item mb-3 sender';
    
    messageItem.innerHTML = `
        <div class="message-content bg-primary text-white p-3 rounded">
            <div class="message-text">${messageText}</div>
            <div class="message-time text-right mt-1">
                <small class="text-white-50">
                    Sending... <i class="fas fa-circle-notch fa-spin ml-1"></i>
                </small>
            </div>
        </div>
    `;
    
    return messageItem;
}

// Initialize message search
function initMessageSearch() {
    const searchInput = document.getElementById('message-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const messageItems = document.querySelectorAll('.message-item');
            
            messageItems.forEach(item => {
                const messageText = item.querySelector('.message-text').textContent.toLowerCase();
                if (messageText.includes(searchTerm) || searchTerm === '') {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initMessaging();
});