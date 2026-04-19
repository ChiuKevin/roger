<?php

require 'vendor/autoload.php';

use App\WebSockets\WebSocketServer;

$webSocketServer = new WebSocketServer();
$webSocketServer->start();
