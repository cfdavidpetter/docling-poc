<?php

namespace App\Http\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class RabbitMQService
{
  protected $connection;
  protected $channel;

  public function __construct()
  {
    $this->connection = new AMQPStreamConnection(
      env('RABBITMQ_HOST', 'localhost'),
      env('RABBITMQ_PORT', 5672),
      env('RABBITMQ_USER', 'guest'),
      env('RABBITMQ_PASSWORD', 'guest')
    );
    $this->channel = $this->connection->channel();
  }

  public function sendMessage(string $uuid, string $queue, string $message)
  {
    $this->channel->queue_declare($queue, false, false, false, false);

    $headers = new AMQPTable([
      'uuid' => $uuid
    ]);
    $msg = new AMQPMessage($message, [
      'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
    ]);
    $msg->set('application_headers', $headers);

    $this->channel->basic_publish($msg, '', $queue);
  }

  public function waitForResponse(string $queue)
  {
    $this->channel->queue_declare($queue, false, false, false, false);

    $response = null;
    $this->channel->basic_consume($queue, '', false, true, false, false, function ($msg) use (&$response) {
      $response = $msg->getBody();
    });

    while ($response === null) {
      $this->channel->wait(null, false, 300);
    }
    return $response;
  }

  public function deleteQueue(string $queue)
  {
    $this->channel->queue_delete($queue);
  }

  public function __destruct()
  {
    $this->channel->close();
    $this->connection->close();
  }
}
