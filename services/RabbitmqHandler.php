<?php
require_once 'lib/constants.php';
require_once 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQHandler
{
    private $connection;
    private $channel;
    private $queue;

    public function __construct()
    {
        try {
            $this->connection = new AMQPStreamConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASSWORD);
            $this->channel = $this->connection->channel();
            $this->queue = QUEUE_NAME;

            $this->channel->queue_declare(QUEUE_NAME, false, true, false, false);
        } catch (Exception $e) {
            echo "connection failed: " . $e->getMessage();
        }
    }

    public function publishMessage($messageBody, $routingKey)
    {
        try {
            $message = new AMQPMessage($messageBody);
            $this->channel->basic_publish($message, '', $routingKey);
        } catch (Exception $e) {
            echo "publishing messages failed: " . $e->getMessage();
        }
    }

    public function consumeMessage($callback)
    {
        try {
            $this->channel->basic_consume(
                $this->queue,
                '',
                false,
                true,
                false,
                false,
                function ($message) use ($callback) {
                    $callback($message);
                    $this->channel->basic_ack($message->delivery_info['delivery_tag']);
                }
            );

            while (count($this->channel->callbacks)) {
                $this->channel->wait();
            }
        } catch (Exception $e) {
            echo "consuming messages failed: " . $e->getMessage();
        }
    }


    public function close()
    {
        try {
            $this->channel->close();
            $this->connection->close();
        } catch (Exception $e) {
            echo "closing connection failed: " . $e->getMessage();
        }
    }
}
