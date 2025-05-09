<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Http\Resources\UserResource;
class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);
    
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);
    
        $message->load('sender', 'receiver');
        event(new MessageSent($message));
    
       return response()->json([
            'message' => $message,
            'sender_name' => $message->sender->name,
            'receiver_name' => $message->receiver->name,
        ]);
    }
    
    
    public function index($receiver_id)
    {
        $user_id = auth()->id();
    
        $messages = Message::with(['sender', 'receiver']) 
            ->where(function ($query) use ($user_id, $receiver_id) {
                $query->where('sender_id', $user_id)->where('receiver_id', $receiver_id);
            })->orWhere(function ($query) use ($user_id, $receiver_id) {
                $query->where('sender_id', $receiver_id)->where('receiver_id', $user_id);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    
        return response()->json($messages);
    }
    public function conversations()
    {
        $userId = auth()->id();
    
        $conversations = Message::with('sender', 'receiver')->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id == $userId
                    ? $message->receiver_id
                    : $message->sender_id;
            });
    
        $users = [];
    
        foreach ($conversations as $userMessages) {
            $firstMessage = $userMessages->first();
            $otherUser = $firstMessage->sender_id == $userId ? $firstMessage->receiver : $firstMessage->sender;
            $users[] = [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'last_message' => $userMessages->last()->body,
                'last_time' => $userMessages->last()->created_at,
            ];
        }
    
        return response()->json($users);
    }
}
