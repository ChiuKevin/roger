<?php

namespace App\WebSockets\Services;

use App\Models\QuoteProMessage;
use Illuminate\Database\Eloquent\Collection;

class MessageService
{
    public function saveMessage(array $user, array $data): void
    {
        $message = [
            'quote_pro_id' => $data['quote_pro_id'],
            'sender_id' => $user['id'],
            'type' => $data['type'],
            'message' => $data['message']
        ];

        QuoteProMessage::create($message);
    }

    public function getHistoryMessages(int $quoteProId): Collection
    {
        return QuoteProMessage::where('quote_pro_id', $quoteProId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function markMessagesAsRead(int $userId, int $quoteProId): void
    {
        QuoteProMessage::where('quote_pro_id', $quoteProId)
            ->where('sender_id', '<>', $userId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
    }
}
