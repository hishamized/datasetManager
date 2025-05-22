@extends('layouts.app')

@section('content')
<div class="container mt-4">

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error') || session('failure'))
    <div class="alert alert-danger">
        {{ session('error') ?? session('failure') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <h3 class="mb-2 text-center">{{ $chat->target->username }}</h3>
    <p class="mb-2 text-center">{{ $chat->target->email }}</p>
    <input type="hidden" id="auth_id" value="{{ auth()->user()->id }}">

    <div class="card shadow-sm">
        <div class="card-body" style="height: 400px; overflow-y: auto;" id="messagesContainer">
            @foreach($messages as $message)
            @php
            $isSender = $message->sender_id === auth()->id();
            $alignment = $isSender ? 'text-end' : 'text-start';
            $bgClass = $isSender ? 'bg-primary text-white' : 'bg-light';
            $statusIcon = $message->seen == 1 ? '✅' : '🕓';
            @endphp
            <div class="mb-2 d-flex {{ $isSender ? 'justify-content-end' : 'justify-content-start' }}"
                data-message-id="{{ $message->id }}"
                data-seen="{{ $message->seen }}"
                data-sender-id="{{ $message->sender_id }}">
                <div class="p-2 rounded {{ $bgClass }} w-auto" style="max-width: 70%;">
                    <div class="fw-bold small">{{ $isSender ? 'You' : $message->user->username }}</div>
                    <div>{{ $message->message }}</div>
                    <div class="text-muted small text-end mt-1">
                        {{ $message->created_at->diffForHumans() }} {{ $isSender ? $statusIcon : '' }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="card-footer">
            <form id="sendMessageForm" class="d-flex gap-2">
                <input type="hidden" name="chat_id" value="{{ $chat_id }}">
                <input type="hidden" name="sender_id" value="{{ auth()->user()->id }}">
                <input type="text" name="message" id="message" class="form-control" placeholder="Type your message..." required>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    // Seen code starts
    const observer = new IntersectionObserver((entries, observerInstance) => {
        console.log("Inside observer");
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const msgElement = entry.target;
                const messageId = msgElement.dataset.messageId;
                const senderId = msgElement.dataset.senderId;

                if (senderId != loggedInUserId) {
                    // AJAX call to mark as seen
                    markMessageAsSeen(messageId);
                }

                observerInstance.unobserve(msgElement);
            }
        });
    }, {
        threshold: 1.0 // Fully visible
    });

    function observeUnseenMessages() {
        document.querySelectorAll('[data-seen="0"]').forEach(el => {
            if (el.dataset.senderId != loggedInUserId) {
                observer.observe(el);
            }
        });
    }

    async function markMessageAsSeen(messageId) {
        console.log("Inside ajax async");
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            await fetch("{{ route('chat.markSeen') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token
                },
                body: JSON.stringify({
                    message_id: messageId
                })
            });
        } catch (error) {
            console.error('Failed to mark message as seen:', error);
        }
    }
    // Seen code ends

    document.getElementById('sendMessageForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const chatId = document.querySelector('input[name="chat_id"]').value;
        const senderId = document.querySelector('input[name="sender_id"]').value;
        const message = document.getElementById('message').value;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch("{{ route('chat.sendMessage') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token
                },
                body: JSON.stringify({
                    chat_id: chatId,
                    sender_id: senderId,
                    message: message
                })
            });

            const data = await response.json();

            if (response.ok) {
                document.getElementById('message').value = '';

                const newMessage = `
                    <div class="mb-2 d-flex justify-content-end">
                        <div class="p-2 rounded bg-primary text-white w-auto" style="max-width: 70%;">
                            <div class="fw-bold small">You</div>
                            <div>${data.message}</div>
                            <div class="text-muted small text-end mt-1">${data.created_at} 🕓</div>
                        </div>
                    </div>
                `;

                document.getElementById('messagesContainer').innerHTML += newMessage;
                document.getElementById('messagesContainer').scrollTop = document.getElementById('messagesContainer').scrollHeight;
            } else {
                alert(data.message || "Something went wrong.");
            }
        } catch (err) {
            alert("Failed to send message.");
            console.error(err);
        }
    });

    function timeAgo(date) {
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        const months = Math.floor(days / 30);
        const years = Math.floor(months / 12);

        if (seconds < 60) {
            return `${seconds} second${seconds !== 1 ? 's' : ''} ago`;
        } else if (minutes < 60) {
            return `${minutes} minute${minutes !== 1 ? 's' : ''} ago`;
        } else if (hours < 24) {
            return `${hours} hour${hours !== 1 ? 's' : ''} ago`;
        } else if (days < 30) {
            return `${days} day${days !== 1 ? 's' : ''} ago`;
        } else if (months < 12) {
            return `${months} month${months !== 1 ? 's' : ''} ago`;
        } else {
            return `${years} year${years !== 1 ? 's' : ''} ago`;
        }
    }

    const loggedInUserId = document.getElementById('auth_id').value;

    async function fetchNewMessages() {
        const chatId = document.querySelector('input[name="chat_id"]').value;

        try {
            const response = await fetch(`{{ route('chat.refreshMessages', ['chat_id' => '__ID__']) }}`.replace('__ID__', chatId));
            const data = await response.json();

            const container = document.getElementById('messagesContainer');
            container.innerHTML = '';

            data.messages.forEach(msg => {
                const isSender = msg.sender_id == loggedInUserId;
                const alignment = isSender ? 'justify-content-end' : 'justify-content-start';
                const bgClass = isSender ? 'bg-primary text-white' : 'bg-light';
                const statusIcon = isSender ? (msg.seen ? '✅' : '🕓') : '';
                const messageTime = timeAgo(new Date(msg.created_at));
                const messageDiv = `
                <div class="mb-2 d-flex ${alignment}"
                     data-message-id="${msg.id}"
                     data-seen="${msg.seen}"
                     data-sender-id="${msg.sender_id}">
                    <div class="p-2 rounded ${bgClass} w-auto" style="max-width: 70%;">
                        <div class="fw-bold small">${isSender ? 'You' : (msg.user?.username ?? 'Them')}</div>
                        <div>${msg.message}</div>
                        <div class="text-muted small text-end mt-1">
                            ${messageTime} ${statusIcon}
                        </div>
                    </div>
                </div>
            `;
                container.innerHTML += messageDiv;
            });
            // container.scrollTop = container.scrollHeight;

            setTimeout(() => {
                observeUnseenMessages();
            }, 100);
        } catch (error) {
            console.error('Error fetching messages:', error);
        }
    }


    setInterval(fetchNewMessages, 5000);
    let container_ = document.getElementById('messagesContainer');
    container_.scrollTop = container_.scrollHeight;
</script>
@endsection
