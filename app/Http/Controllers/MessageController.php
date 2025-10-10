<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    /**
     * Display the messaging interface
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's conversations with unread count
        $conversations = $user->conversations()
            ->with([
                'activeParticipants' => function ($query) use ($user) {
                    $query->select('users.id', 'users.name', 'users.email', 'users.role', 'users.phone_number', 'users.address')
                        ->where('users.id', '!=', $user->id);
                },
                'latestMessage.sender'
            ])
            ->get()
            ->map(function ($conversation) use ($user) {
                $conversation->unread_count = $conversation->getUnreadCount($user->id);
                return $conversation;
            });

        // Get all users that current user can chat with (excluding themselves)
        $availableUsers = User::where('id', '!=', $user->id)
            ->where('email_verified_at', '!=', null) // Only active users
            ->select('id', 'name', 'email', 'role', 'phone_number', 'address')
            ->orderBy('name')
            ->get();

        return view('pages.be.chat.index', compact('conversations', 'availableUsers'));
    }

    /**
     * Start a new conversation or get existing private conversation
     */
    public function startConversation(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $currentUserId = Auth::id();
        $targetUserId = $request->user_id;

        if ($currentUserId == $targetUserId) {
            return response()->json(['error' => 'Cannot start conversation with yourself'], 400);
        }

        // Check if private conversation already exists
        $conversation = Conversation::findPrivateConversation($currentUserId, $targetUserId);

        if (!$conversation) {
            // Create new private conversation
            $conversation = Conversation::createPrivateConversation($currentUserId, $targetUserId);
        }

        // Load the conversation with necessary relationships
        $conversation->load([
            'activeParticipants' => function ($query) use ($currentUserId) {
                $query->select('users.id', 'users.name', 'users.email', 'users.role', 'users.phone_number', 'users.address')
                    ->where('users.id', '!=', $currentUserId);
            },
            'messages.sender:id,name'
        ]);

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'active_participants' => $conversation->activeParticipants,
                'messages' => $conversation->messages,
            ],
            'unread_count' => $conversation->getUnreadCount($currentUserId)
        ]);
    }

    /**
     * Get messages for a specific conversation
     */
    public function getMessages(Conversation $conversation): JsonResponse
    {
        $user = Auth::user();

        // Check if user is participant
        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark messages as read
        $conversation->markAsRead($user->id);

        // Get messages with sender info
        $messages = $conversation->messages()
            ->with('sender:id,name,role')
            ->notDeleted()
            ->orderBy('created_at', 'asc')
            ->get();

        $conversation->load([
            'activeParticipants' => function ($query) use ($user) {
                $query->select('users.id', 'users.name', 'users.email', 'users.role', 'users.phone_number', 'users.address')
                    ->where('users.id', '!=', $user->id);
            }
        ]);

        return response()->json([
            'messages' => $messages,
            'conversation' => [
                'id' => $conversation->id,
                'active_participants' => $conversation->activeParticipants,
                'participants' => $conversation->activeParticipants, // For backward compatibility
            ]
        ]);
    }

    /**
     * Send a new message
     */
    public function sendMessage(Request $request, Conversation $conversation): JsonResponse
    {
        $request->validate([
            'content' => 'required|string|max:2000'
        ]);

        $user = Auth::user();

        // Check if user is participant
        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        DB::beginTransaction();
        try {
            // Create new message
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'content' => $request->content,
                'type' => 'text'
            ]);

            // Update conversation last activity
            $conversation->updateLastActivity();

            DB::commit();

            // Load sender relationship
            $message->load('sender:id,name,role');

            return response()->json([
                'message' => $message,
                'success' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }

    /**
     * Edit a message
     */
    public function editMessage(Request $request, Message $message): JsonResponse
    {
        $request->validate([
            'content' => 'required|string|max:2000'
        ]);

        $user = Auth::user();

        // Check if user owns the message
        if ($message->sender_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if message is editable
        if (!$message->isEditable()) {
            return response()->json(['error' => 'Message cannot be edited'], 400);
        }

        $message->editContent($request->content);

        return response()->json([
            'message' => $message->fresh('sender:id,name,role'),
            'success' => true
        ]);
    }

    /**
     * Delete a message
     */
    public function deleteMessage(Message $message): JsonResponse
    {
        $user = Auth::user();

        // Check if user owns the message
        if ($message->sender_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->markAsDeleted();

        return response()->json(['success' => true]);
    }

    /**
     * Get user list for starting new conversations
     */
    public function getUserList(): JsonResponse
    {
        $user = Auth::user();

        $users = User::where('id', '!=', $user->id)
            ->where('email_verified_at', '!=', null) // Only active users
            ->select('id', 'name', 'email', 'role', 'phone_number', 'address')
            ->orderBy('name')
            ->get();

        return response()->json(['users' => $users]);
    }

    /**
     * Search conversations and users
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:1'
        ]);

        $user = Auth::user();
        $query = $request->query;

        // Search in user's conversations
        $conversations = $user->conversations()
            ->with(['activeParticipants' => function ($q) use ($query, $user) {
                $q->select('users.id', 'users.name', 'users.email', 'users.role', 'users.phone_number', 'users.address')
                    ->where('users.id', '!=', $user->id)
                    ->where('users.name', 'like', "%{$query}%");
            }])
            ->get()
            ->filter(function ($conversation) {
                return $conversation->activeParticipants->isNotEmpty();
            });

        // Search available users to start new conversations
        $availableUsers = User::where('id', '!=', $user->id)
            ->where('email_verified_at', '!=', null)
            ->where('name', 'like', "%{$query}%")
            ->select('id', 'name', 'email', 'role', 'phone_number', 'address')
            ->get();

        return response()->json([
            'conversations' => $conversations,
            'users' => $availableUsers
        ]);
    }

    /**
     * Get unread message count for header notification
     */
    public function getUnreadCount(): JsonResponse
    {
        $user = Auth::user();
        $unreadCount = $user->getTotalUnreadMessages();

        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }
}
