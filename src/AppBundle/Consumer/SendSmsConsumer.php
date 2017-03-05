<?php 
namespace JustMeet\AppBundle\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Nexmo\Client;


class SendSmsConsumer implements ConsumerInterface 
{
    public function execute(AMQPMessage $msg)
    {
        //Process picture upload.
        //$msg will be an instance of `PhpAmqpLib\Message\AMQPMessage` with the $msg->body being the data sent over RabbitMQ.
        fwrite(STDOUT, "Excecuting Queue Item! ".$msg->body."\n" );

        $client = new \Nexmo\Client(new \Nexmo\Client\Credentials\Basic('35c8329c','a9f5b169560927ac'));

        $message = $client->message()->send([
            'to' => "+447834228887",
            'from' => "NEXMO",
            'text' => 'Test message from the Nexmo PHP Client'
        ]);  
        
        $isTaskSuccess = true;
        if (!$message) {
            // If your image upload failed due to a temporary error you can return false
            // from your callback so the message will be rejected by the consumer and
            // requeued by RabbitMQ.
            // Any other value not equal to false will acknowledge the message and remove it
            // from the queue
            return false;
        }
    }
}