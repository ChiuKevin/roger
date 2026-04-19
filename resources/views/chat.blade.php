<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotes Chat</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        #quote-list-container,
        #quote-pros-container {
            background-color: #f4f4f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #quote-list li,
        #quote-pros li {
            cursor: pointer;
            transition: transform 0.2s ease-in-out, background-color 0.2s ease-in-out;
        }

        #quote-list li,
        #quote-pros li {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        li:hover {
            background-color: #f0f0f0;
            transform: translateY(-2px);
        }

        #chat-box {
            margin-top: 40px;
            display: none;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        #messages {
            border: 1px solid #ccc;
            height: 200px;
            overflow-y: auto;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .message {
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            word-wrap: break-word;
            display: block;
        }

        .message.me {
            text-align: right;
            background-color: #dcf8c6;
            margin-left: auto;
        }

        .message.other {
            text-align: left;
            background-color: #f1f0f0;
            margin-right: auto;
        }

        #message-input {
            width: calc(100% - 100px);
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 15px;
        }

        #send-btn {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        #send-btn:hover {
            background-color: #0056b3;
        }

        #error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div id="user"></div>
    <div style="display: flex; justify-content: space-between; max-width: 800px; margin: 0 auto;">
        <div id="quote-list-container" style="width: 40%;">
            <h1>Quotes List</h1>
            <ul id="quote-list"></ul>
        </div>

        <div id="quote-pros-container" style="width: 40%;">
            <h1>Quote Pros</h1>
            <ul id="quote-pros"></ul>
        </div>
    </div>

    <div id="chat-box">
        <h2>Chat: <span id="current-quote"></span></h2>
        <div id="error-message"></div>
        <div id="messages"></div>
        <input type="file" id="file" style="margin-top: 15px;">
        <div id="file-preview" style="display: none; margin-top: 10px;">
            <img id="preview-img" src="" alt="Preview" style="max-width: 200px; max-height: 200px;" />
        </div>
        <input type="text" id="message-input" placeholder="Type your message">
        <button id="send-btn">Send</button>
    </div>

    <script>
        let ws = null;
        let currentQuote = null;
        let currentQuotePro = null;
        let jwtToken = null;
        let quotePros = null;
        const number = Math.floor(Math.random() * 100);
        const user = `user${number}`;
        document.getElementById('user').innerHTML = `Logged in as: ${user}`;
        let heartBeatInterval;

        async function loginAndFetchJWT() {
            try {
                const response = await fetch(`${window.location.origin}/consumer/auth/email`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'region': 'tw',
                        'locale': 'zh_tw'
                    },
                    body: JSON.stringify({
                        email: `${user}@gmail.com`,
                        password: 'password'
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed to authenticate');
                }

                const data = await response.json();
                jwtToken = data.data.token;
                fetchQuotes();
            } catch (error) {
                showError('Error authenticating. Please try again.');
                console.error('Error authenticating:', error);
            }
        }

        async function fetchQuotes() {
            try {
                const response = await fetch(`${window.location.origin}/consumer/user/quotes`, {
                    headers: {
                        'Authorization': `Bearer ${jwtToken}`
                    }
                });
                if (!response.ok) {
                    throw new Error('Failed to fetch quotes');
                }

                const quotes = await response.json();
                const quoteList = document.getElementById('quote-list');
                quotes.data.list.forEach(quote => {
                    const li = document.createElement('li');
                    li.textContent = quote.id;
                    li.setAttribute('data-id', quote.id);
                    li.addEventListener('click', () => fetchQuote(quote.id));
                    quoteList.appendChild(li);
                });
            } catch (error) {
                showError('Failed to fetch quotes. Please try again.');
                console.error('Error fetching quotes:', error);
            }
        }

        async function fetchQuote(quoteId) {
            try {
                const response = await fetch(`${window.location.origin}/consumer/user/quotes/${quoteId}`, {
                    headers: {
                        'Authorization': `Bearer ${jwtToken}`
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch single quote');
                }

                const quoteData = await response.json();
                currentQuote = quoteData.data;
                quotePros = currentQuote.quote_pros;

                const quoteProsContainer = document.getElementById('quote-pros');
                quoteProsContainer.innerHTML = '';

                if (quotePros && quotePros.length > 0) {
                    quotePros.forEach(quotePro => {
                        const li = document.createElement('li');
                        li.textContent = quotePro.pro_id;
                        li.setAttribute('data-id', quotePro.id);
                        li.addEventListener('click', () => {
                            currentQuotePro = quotePro;
                            openWebSocket(quotePro.id);
                        });
                        quoteProsContainer.appendChild(li);
                    });
                }
            } catch (error) {
                showError('Failed to load quote details. Please try again.');
                console.error('Error fetching single quote:', error);
            }
        }

        function openWebSocket(quoteProId) {
            document.getElementById('current-quote').textContent = currentQuotePro.id;
            document.getElementById('chat-box').style.display = 'block';
            document.getElementById('messages').innerHTML = '';

            if (ws) {
                ws.close();
            }

            ws = new WebSocket(`wss://${window.location.host}/ws?token=${jwtToken}`);

            ws.onopen = () => {
                console.log('Connected to WebSocket server for quote_pro:', quoteProId);
                ws.send(JSON.stringify({
                    quote_pro_id: quoteProId,
                    action: 'join',
                    message: 'join'
                }));

                markMessagesAsRead(quoteProId);

                heartBeatInterval = setInterval(() => {
                    ws.send(JSON.stringify({
                        action: 'heartbeat'
                    }));
                }, 30000);
            };

            ws.onmessage = (event) => {
                const data = JSON.parse(event.data);

                if (!data.isMe && data.action !== 'message_read') {
                    markMessagesAsRead(currentQuotePro.id);
                }

                if (data.action === 'message_read') {
                    updateMessageAsRead(data.reader_id);
                } else {
                    appendMessage(data.message, data.isMe ? 'me' : data.username, data.isMe, data.is_read, data.type);
                }
            };

            ws.onerror = (error) => {
                showError('WebSocket error occurred.');
                console.error('WebSocket error:', error);
            };

            ws.onclose = () => {
                console.log('Disconnected from WebSocket server');
                clearInterval(heartBeatInterval);
            };
        }

        function appendMessage(message, senderName, isMe, isRead, type) {
            const messagesDiv = document.getElementById('messages');
            const messageElement = document.createElement('div');
            let content = '';

            switch (type) {
                case 2:
                    content = `<img src="${message}" alt="Image" style="max-width: 200px; max-height: 200px;">`;
                    break;
                case 3:
                    content = `<video controls style="max-width: 200px; max-height: 200px;">
                        <source src="${message}" type="video/mp4">
                        Your browser does not support the video tag.
                      </video>`;
                    break;
                case 4:
                    content = `<a href="${message}" target="_blank">View File</a>`;
                    break;
                default:
                    content = message;
            }

            const readStatus = isRead && isMe ? '✓已讀' : '';
            messageElement.innerHTML = `${senderName}: <br>${content} ${readStatus}`;

            messageElement.classList.add('message', isMe ? 'me' : 'other');

            messagesDiv.appendChild(messageElement);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        async function uploadFileToS3(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('path', 'chat/quote');

            const fileInfo = getFileType(file.name);

            if (!fileInfo.apiUrl) {
                showError('Unsupported file type. Please upload an image or video.');
                return null;
            }

            try {
                const response = await fetch(fileInfo.apiUrl, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${jwtToken}`
                    },
                    body: formData,
                });

                if (response.ok) {
                    const data = await response.json();
                    return data.data.url;
                } else {
                    throw new Error('Failed to upload file');
                }
            } catch (error) {
                console.error('Error uploading file:', error);
                showError('File upload failed. Please try again.');
                return null;
            }
        }

        async function sendMessage() {
            const messageInput = document.getElementById('message-input');
            const message = messageInput.value.trim();
            const fileInput = document.getElementById('file');
            const file = fileInput.files[0];

            if (file) {
                const fileInfo = getFileType(file.name);
                const url = await uploadFileToS3(file);

                if (url) {
                    ws.send(JSON.stringify({
                        quote_pro_id: currentQuotePro.id,
                        action: 'message',
                        message: url,
                        type: fileInfo.type
                    }));
                }
            }

            if (message !== '') {
                ws.send(JSON.stringify({
                    quote_pro_id: currentQuotePro.id,
                    action: 'message',
                    message: message,
                    type: 1
                }));
                messageInput.value = '';
            }

            fileInput.value = '';
            document.getElementById('file-preview').style.display = 'none';

            document.getElementById('send-btn').disabled = false;
        }

        function getFileType(fileName) {
            const extension = fileName.split('.').pop().toLowerCase();
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            const videoExtensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'mkv', 'webm'];

            if (imageExtensions.includes(extension)) {
                return {
                    type: 2,
                    apiUrl: `${window.location.origin}/web/images`
                };
            } else if (videoExtensions.includes(extension)) {
                return {
                    type: 3,
                    apiUrl: `${window.location.origin}/web/videos`
                };
            } else {
                return {
                    type: 4,
                    apiUrl: null
                };
            }
        }

        function markMessagesAsRead(quoteProId) {
            if (ws && currentQuotePro) {
                ws.send(JSON.stringify({
                    action: 'read',
                    quote_pro_id: quoteProId
                }));
            }
        }

        function updateMessageAsRead(readerId) {
            const messagesDiv = document.getElementById('messages');
            const messageElements = messagesDiv.getElementsByClassName('message me');

            Array.from(messageElements).forEach((msgElem) => {
                if (!msgElem.innerHTML.includes('✓已讀')) {
                    msgElem.innerHTML += ' ✓已讀';
                }
            });
        }

        function showError(errorMsg) {
            const errorMessageDiv = document.getElementById('error-message');
            errorMessageDiv.textContent = errorMsg;
            setTimeout(() => {
                errorMessageDiv.textContent = '';
            }, 3000);
        }

        loginAndFetchJWT();

        document.getElementById('file').addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        document.getElementById('preview-img').src = e.target.result;
                        document.getElementById('file-preview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    document.getElementById('file-preview').style.display = 'none';
                }
                document.getElementById('send-btn').disabled = false;
            }
        });

        document.getElementById('send-btn').addEventListener('click', sendMessage);

        document.getElementById('message-input').addEventListener('keyup', (event) => {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage();
            }
        });
    </script>

</body>

</html>