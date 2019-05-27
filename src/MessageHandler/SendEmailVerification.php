<?php
namespace App\MessageHandler;
#require_once __DIR__.'/vendor/autoload.php';

use App\Entity\Users;
use App\CommandBus;
#use PhpAmqpLib\Connection\AMQPStreamConnection;
#use PhpAmqpLib\Message\AMQPMessage;
use App\Message\Event\UserRegistered;
use App\Message\Command\SendEmailVerivcationEmail;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class SendEmailVerification implements MessageHandlerInterface{


    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * SendEmailVerification constructor.

     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {

        $this->commandBus = $commandBus;
    }


    public function __invoke(UserRegistered $userRegistered)
    {
        $userId=$userRegistered->getUserId();
        $this->commandBus->dispatch(new SendEmailVerivcationEmail($userId));
    }


}


