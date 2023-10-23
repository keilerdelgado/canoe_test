<?php
require_once 'services/RabbitmqHandler.php';

class EventsController
{
    public function getMessages()
    {
        $rabbitmqHandler = new RabbitmqHandler();
        $callback = function ($message) {
            $messageBody = $message->body;

            echo "Received message: $messageBody\n";
        };
        $rabbitmqHandler->consumeMessage($callback);
    }
}
