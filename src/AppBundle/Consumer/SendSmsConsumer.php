<?php 
namespace JustMeet\AppBundle\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Nexmo\Client as NexmoClient;
use Nexmo\Client\Credentials\Basic as BasicCredentials;
use Doctrine\ORM\EntityManager;
use JustMeet\AppBundle\Entity\User;
use JustMeet\AppBundle\Entity\Meeting;


class SendSmsConsumer implements ConsumerInterface 
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(array $params, EntityManager $em)
    {
        $this->key = $params['key'];
        $this->secret = $params['secret'];
        $this->em = $em;
    }

    public function execute(AMQPMessage $msg)
    {
        // Process picture upload.
        // $msg will be an instance of `PhpAmqpLib\Message\AMQPMessage`
        // with the $msg->body being the data sent over RabbitMQ.
        fwrite(STDOUT, "Excecuting Queue Item! ".$msg->body."\n" );

        $client = new NexmoClient(new BasicCredentials
        (
            $this->key, $this->secret
        ));

        $info = \unserialize($msg->body);
        $user = $this->em->getRepository(User::class)
            ->findOneById($info['user_id']);
        $meeting = $this->em->getRepository(Meeting::class)
            ->findOneById($info['meeting_id']);

        if (!$user)
        {
            return false;
        }

        $message = $client->message()->send([
            'to' => "+447834228887",
            'from' => "Just Meet",
            'text' =>
                'Hey ' . $user->firstName . '. Here\'s your next '
                . 'meeting: ' . $meeting->name
        ]);  
        
        if (!$message) {
            return false;
        }
    }
}
