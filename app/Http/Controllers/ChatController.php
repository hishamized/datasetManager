<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function showChatsPage()
    {
        $userId = Auth::id();

        $chats = Chat::where('initiator_id', $userId)
            ->orWhere('target_id', $userId)
            ->get();

        return view('chat.chats', compact('chats'));
    }

    public function showChatbox($chat_id)
    {
        $chat = Chat::where('id', $chat_id)->first();
        $messages = Message::where('chat_id', $chat->id)->get();

        return view('chat.chatbox', [
            'chat_id' => $chat->id,
            'chat' => $chat,
            'messages' => $messages
        ]);
    }



    public function startChat(Request $request)
    {
        $validatedData = $request->validate([
            'sender_id' => 'required|int',
            'identifier' => [
                'required',
                'string',
            ],
        ]);

        $user = filter_var($validatedData['identifier'], FILTER_VALIDATE_EMAIL)
            ? User::where('email', $validatedData['identifier'])->first()
            : User::where('username', $validatedData['identifier'])->first();


        if ($user && $validatedData['sender_id'] == $user->id) {
            return redirect()->route('showChatsPage')->with('error', "You cannot chat with yourself!");
        }

        if ($user) {

            $chat = Chat::where('initiator_id', $validatedData['sender_id'])
                ->where('target_id', $user->id)
                ->first();

            if (!$chat) {

                $chat = Chat::create([
                    'initiator_id' => $validatedData['sender_id'],
                    'target_id' => $user->id,
                ]);
            }


            $messages = Message::where("chat_id", $chat->id)->get();

            return view('chat.chatbox', [
                'chat' => $chat,
                'chat_id' => $chat->id,
                'messages' => $messages,
            ]);
        }

        return redirect()->route('showChatsPage')->with('error', "No such user was found! Please check username and email.");
    }


    public function sendMessage(Request $request)
    {
        $validatedData = $request->validate([
            "chat_id" => 'required|int',
            "sender_id" => 'required|int',
            "message" => 'required|string'
        ]);

        $message = new Message();
        $message->chat_id = $validatedData["chat_id"];
        $message->sender_id = $validatedData["sender_id"];
        $message->message = $validatedData["message"];
        $message->save();

        return response()->json([
            'message' => $message->message,
            'sender' => $message->sender_id,
            'created_at' => $message->created_at->diffForHumans(),
        ]);
    }

    public function refreshMessages($chat_id)
    {
        $messages = Message::where('chat_id', $chat_id)
            ->with('user')
            ->get();
        return response()->json([
            'messages' => $messages
        ]);
    }

    public function markSeen(Request $request)
    {
        $validated = $request->validate([
            'message_id' => 'required|exists:messages,id',
        ]);

        $message = Message::find($validated['message_id']);

        if ($message && !$message->seen) {
            $message->seen = true;
            $message->save();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Message not found or already seen']);
    }

}
