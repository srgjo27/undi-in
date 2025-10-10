<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conversation extends Model
{
    protected $fillable = [
        'title',
        'type',
        'created_by',
        'last_activity'
    ];

    protected $casts = [
        'last_activity' => 'datetime',
    ];

    /**
     * The user who created the conversation
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * All messages in this conversation
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Latest message in the conversation
     */
    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * All participants in this conversation
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot(['joined_at', 'last_read_at', 'is_active'])
            ->withTimestamps();
    }

    /**
     * Active participants only
     */
    public function activeParticipants(): BelongsToMany
    {
        return $this->participants()->wherePivot('is_active', true);
    }

    /**
     * Get the other participant(s) excluding the current user
     */
    public function otherParticipants($userId): BelongsToMany
    {
        return $this->activeParticipants()->where('users.id', '!=', $userId);
    }

    /**
     * Get other participants relationship (for eager loading)
     * This is used for eager loading in queries
     */
    public function otherUsers(): BelongsToMany
    {
        return $this->activeParticipants();
    }

    /**
     * Check if user is participant
     */
    public function hasParticipant($userId): bool
    {
        return $this->activeParticipants()->where('users.id', $userId)->exists();
    }

    /**
     * Get unread messages count for a specific user
     */
    public function getUnreadCount($userId): int
    {
        $participant = $this->participants()->where('users.id', $userId)->first();

        if (!$participant) {
            return 0;
        }

        $lastReadAt = $participant->pivot->last_read_at;

        $query = $this->messages()->where('sender_id', '!=', $userId);

        if ($lastReadAt) {
            $query->where('created_at', '>', $lastReadAt);
        }

        return $query->count();
    }

    /**
     * Mark messages as read for a user
     */
    public function markAsRead($userId): void
    {
        $this->participants()->updateExistingPivot($userId, [
            'last_read_at' => now()
        ]);
    }

    /**
     * Update last activity timestamp
     */
    public function updateLastActivity(): void
    {
        $this->update(['last_activity' => now()]);
    }

    /**
     * Create a private conversation between two users
     */
    public static function createPrivateConversation($user1Id, $user2Id): self
    {
        $conversation = self::create([
            'type' => 'private',
            'created_by' => $user1Id,
            'last_activity' => now()
        ]);

        $conversation->participants()->attach([
            $user1Id => ['joined_at' => now(), 'is_active' => true],
            $user2Id => ['joined_at' => now(), 'is_active' => true]
        ]);

        return $conversation;
    }

    /**
     * Find private conversation between two users
     */
    public static function findPrivateConversation($user1Id, $user2Id): ?self
    {
        return self::where('type', 'private')
            ->whereHas('participants', function ($query) use ($user1Id) {
                $query->where('users.id', $user1Id)->where('is_active', true);
            })
            ->whereHas('participants', function ($query) use ($user2Id) {
                $query->where('users.id', $user2Id)->where('is_active', true);
            })
            ->first();
    }
}
