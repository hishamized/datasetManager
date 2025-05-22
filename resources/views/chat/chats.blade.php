@extends('layouts.app')

@section('content')

{{-- Session Messages --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if (session('failure'))
    <div class="alert alert-danger">
        {{ session('failure') }}
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

<div class="container mt-4">
    <h1 class="text-center mb-4">Your Chats</h1>

    {{-- Chat Form --}}
    <div class="mb-4">
        <h3>Start a New Chat</h3>
        <form action="{{ route('chats.startChat') }}" method="post" class="bg-light p-4 rounded shadow">
            @csrf
            <input type="hidden" name="sender_id" value="{{ auth()->user()->id }}">

            <div class="mb-3">
                <label for="identifier" class="form-label">Enter username or email of the user to chat with</label>
                <input type="text" id="identifier" name="identifier" class="form-control" placeholder="Username or Email" required>
            </div>

            <button type="submit" name="chatnow" class="btn btn-primary">Start Chat</button>
        </form>
    </div>

    {{-- Chats List --}}
    <h3 class="mt-4">Active Chats</h3>
    <div class="row">
        @foreach ($chats as $chat)
            @php
                // Determine the other user (target or initiator)
                $otherUser = ($chat->initiator_id != Auth::id()) ? $chat->initiator : $chat->target;
            @endphp

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-light">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title mb-0">{{ $otherUser->username }}</h5>
                            <small class="text-muted">{{ $chat->messages->isNotEmpty() ? $chat->messages->last()->created_at->diffForHumans() : 'No messages yet' }}</small>
                        </div>
                        <p class="card-text text-truncate">
                            {{-- Display last message (if any) --}}
                            @if ($chat->messages->isNotEmpty())
                                {{ $chat->messages->last()->message }}
                            @else
                                No messages yet
                            @endif
                        </p>
                        <a href="{{ route('chat.showChatbox', $chat->id) }}" class="btn btn-primary btn-sm mt-auto">Open Chat</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
