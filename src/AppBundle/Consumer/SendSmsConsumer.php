<?php 
namespace JustMeet\AppBundle\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Nexmo\Client as NexmoClient;
use Nexmo\Client\Credentials\Basic as BasicCredentials;


class SendSmsConsumer implements ConsumerInterface 
{
    public function execute(AMQPMessage $msg)
    {
        // Process picture upload.
        // $msg will be an instance of `PhpAmqpLib\Message\AMQPMessage`
        // with the $msg->body being the data sent over RabbitMQ.
        fwrite(STDOUT, "Excecuting Queue Item! ".$msg->body."\n" );

        $client = new NexmoClient(new BasicCredentials
        (
            '35c8329c', 'a9f5b169560927ac'
        ));

        $message = $client->message()->send([
            'to' => "+447834228887",
            'from' => "NEXMO",
            'text' => 'Test message from the Nexmo PHP Client'
        ]);  
        
        $isTaskSuccess = true;
        if (!$message) {
            return false;
        }
    }
}
