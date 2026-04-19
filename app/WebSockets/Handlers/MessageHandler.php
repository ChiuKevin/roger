<?php

namespace App\WebSockets\Handlers;

use App\WebSockets\Services\MessageService;
use Swoole\Table;
use OpenSwoole\WebSocket\Server;

class MessageHandler
{
    protected Server $server;
    protected Table $users;
    protected Table $rooms;
    protected MessageService $messageService;

    public function __construct(Server $server, Table $users, Table $rooms, MessageService $messageService)
    {
        $this->server = $server;
        $this->users = $users;
        $this->rooms = $rooms;
        $this->messageService = $messageService;
    }

    public function handleJoin(array $user, array $data, $frame): void
    {
        $quote_pro_id = $data['quote_pro_id'];

        $clients = [];

        if ($this->rooms->exists($quote_pro_id)) {
            $clients = json_decode($this->rooms->get($quote_pro_id, 'clients'), true);
        }

        $clients[$frame->fd] = true;

        $this->rooms->set($quote_pro_id, ['clients' => json_encode($clients)]);
        echo "user {$user['username']} joined quote {$quote_pro_id} chat\n";

        $historyMessages = $this->messageService->getHistoryMessages($quote_pro_id);

        foreach ($historyMessages as $message) {
            $senderUsername = $message->sender ? $message->sender->username : 'Unknown';

            $isMe = $message->sender_id == $user['id'];

            $this->server->push($frame->fd, json_encode([
                'username' => $senderUsername,
                'type' => $message->type,
                'message' => $message->message,
                'is_read' => $message->is_read,
                'isMe' => $isMe,
            ]));
        }
    }

    public function handleMessage(array $user, array $data, $frame): void
    {
        $quote_pro_id = $data['quote_pro_id'];

        if ($this->rooms->exists($quote_pro_id)) {
            $clients = json_decode($this->rooms->get($quote_pro_id, 'clients'), true);

            $this->messageService->saveMessage($user, $data);

            foreach ($clients as $fd => $status) {
                $isMe = $fd == $frame->fd;

                if ($this->server->isEstablished($fd)) {
                    $this->server->push($fd, json_encode([
                        'username' => $user['username'],
                        'type' => $data['type'],
                        'message' => $data['message'],
                        'is_read' => 0,
                        'isMe' => $isMe
                    ]));
                } else {
                    unset($clients[$fd]);
                }
            }

            $this->rooms->set($quote_pro_id, ['clients' => json_encode($clients)]);
        }
    }

    public function handleRead(array $user, array $data): void
    {
        $this->messageService->markMessagesAsRead($user['id'], $data['quote_pro_id']);

        $clients = json_decode($this->rooms->get($data['quote_pro_id'], 'clients'), true);
        foreach ($clients as $fd => $status) {
            if ($this->users->get($fd)['id'] != $user['id']) {
                $this->server->push($fd, json_encode([
                    'action' => 'message_read',
                    'quote_pro_id' => $data['quote_pro_id'],
                    'reader_id' => $user['id']
                ]));
            }
        }
    }
}
