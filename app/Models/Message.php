<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'content',
        'type',
        'metadata',
        'edited_at',
        'is_deleted'
    ];

    protected $casts = [
        'metadata' => 'array',
        'edited_at' => 'datetime',
        'is_deleted' => 'boolean',
    ];

    /**
     * The conversation this message belongs to
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * The user who sent this message
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Scope for non-deleted messages
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }

    /**
     * Mark message as deleted
     */
    public function markAsDeleted(): void
    {
        $this->update(['is_deleted' => true]);
    }

    /**
     * Check if message is editable (within 5 minutes and not deleted)
     */
    public function isEditable(): bool
    {
        return !$this->is_deleted &&
            $this->created_at->diffInMinutes(now()) <= 5;
    }

    /**
     * Edit message content
     */
    public function editContent(string $newContent): void
    {
        if ($this->isEditable()) {
            $this->update([
                'content' => $newContent,
                'edited_at' => now()
            ]);
        }
    }

    /**
     * Format message for display
     */
    public function getFormattedContentAttribute(): string
    {
        if ($this->is_deleted) {
            return '<em>This message was deleted</em>';
        }

        return $this->content;
    }
}
