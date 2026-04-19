<?php

namespace App\WebSockets;

use OpenSwoole\WebSocket\Server;
use OpenSwoole\Util;
use Swoole\Table;
use App\WebSockets\Handlers\MessageHandler;
use App\WebSockets\Services\MessageService;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class WebSocketServer
{
    protected Server $server;
    protected Table $users;
    protected Table $rooms;
    protected MessageHandler $messageHandler;

    public function __construct(string $host = "0.0.0.0", int $port = 9501)
    {
        $this->initLaravel();

        $this->server = new Server($host, $port);
        $this->server->on('Open', [$this, 'onOpen']);
        $this->server->on('Message', [$this, 'onMessage']);
        $this->server->on('Close', [$this, 'onClose']);
        $this->server->set(['worker_num' => Util::getCPUNum()]);

        $this->users = new Table(1024);
        $this->users->column('id', Table::TYPE_INT);
        $this->users->column('username', Table::TYPE_STRING, 64);
        $this->users->create();

        $this->rooms = new Table(1024);
        $this->rooms->column('clients', Table::TYPE_STRING, 2048);
        $this->rooms->create();

        $this->messageHandler = new MessageHandler($this->server, $this->users, $this->rooms, new MessageService());
    }

    private function initLaravel(): void
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';
        $kernel = $app->make(Kernel::class);
        $request = Request::capture();
        $kernel->handle($request);
        echo "Laravel initialized.\n";
    }

    public function onOpen(Server $server, $request): void
    {
        $queryParams = [];
        parse_str($request->server['query_string'], $queryParams);
        $token = $queryParams['token'] ?? null;

        if ($token) {
            $user = $this->validateJwt($token);
            if ($user) {
                $this->users->set($request->fd, [
                    'id' => $user->id,
                    'username' => $user->username,
                ]);
            } else {
                $server->close($request->fd);
            }
        } else {
            $server->close($request->fd);
        }
    }

    private function validateJwt(string $token): ?object
    {
        try {
            $jwtAuth = app('tymon.jwt.auth');
            $jwtAuth->setToken($token);

            if ($user = $jwtAuth->authenticate()) {
                return $user;
            }

            return null;
        } catch (JWTException $e) {
            return null;
        }
    }

    public function onMessage(Server $server, $frame): void
    {
        $data = json_decode($frame->data, true);
        $user = $this->users->get($frame->fd);

        if (!$user) {
            $server->close($frame->fd);
            return;
        }

        switch ($data['action']) {
            case 'join':
                $this->messageHandler->handleJoin($user, $data, $frame);
                break;
            case 'message':
                $this->messageHandler->handleMessage($user, $data, $frame);
                break;
            case 'read':
                $this->messageHandler->handleRead($user, $data);
                break;
            default:
                echo "Unknown action: " . $data['action'];
                break;
        }
    }

    public function onClose(Server $server, int $fd): void
    {
        $this->users->del($fd);

        foreach ($this->rooms as $quote_id => $row) {
            $clients = json_decode($row['clients'], true);

            if (isset($clients[$fd])) {
                unset($clients[$fd]);
                $this->rooms->set($quote_id, ['clients' => json_encode($clients)]);
                echo "Client {$fd} disconnected.\n";
            }
        }
    }

    public function start(): void
    {
        $this->server->start();
    }
}
