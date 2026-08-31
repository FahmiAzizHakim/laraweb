<?php

namespace App\Services;

use App\Models\Message;

class MessageService
{
    /**
     * Messages for a website (newest first). Read-only inbox.
     */
    public function getList($websiteId = null)
    {
        return Message::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->orderByDesc('created_at')
            ->get();
    }

    public function getRow($id, $websiteId = null)
    {
        return Message::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    /**
     * Mark a message as read (no-op if already read).
     */
    public function markAsRead(Message $message): void
    {
        if ($message->status !== 'read') {
            $message->update(['status' => 'read']);
        }
    }

    /**
     * Count of unread messages for a website.
     */
    public function unreadCount($websiteId = null): int
    {
        return Message::where('status', '!=', 'read')
            ->when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->count();
    }
}
