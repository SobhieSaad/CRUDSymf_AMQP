<?php

// src/MessageHandler/SmsNotificationHandler.php
namespace App\MessageHandler;


use App\Entity\Users;
use App\Message\SmsNotification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

//====================================================

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//====================================================

class SmsNotificationHandler implements MessageHandlerInterface
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;


    /**
     * @var \Twig_Environment
     */
    private $twig;


    /**
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer,\Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }
      
    public function __invoke(SmsNotification $SmsNotification)
    {
        $user= $SmsNotification->getUser();


        $email=$user->getEmail();
        $UsrName=$user->getUsrName();
        $Pass=$user->getPassword();
        $Gender=$user->getGender();
        $msg=json_encode(['Email'=>$email,
            'UsrName'=>$UsrName,
            'Password'=>$Pass,
            'Gender'=>$Gender
        ]);
        $host='fox.rmq.cloudamqp.com';
        $port=5672;
        $user='jzogrvug';
        $password='rktLwFJbNLuyOHA6vxhzG9gaQBoyn4hg';
        $vhost='jzogrvug';
        $exhange='subscribers';
        $queue='msgs';
        $connection=new AMQPStreamConnection($host,$port,$user,$password,$vhost);

        $channel=$connection->channel();

        $channel->queue_declare($queue,false,true,false,false);

        $channel->exchange_declare($exhange,'direct',false,true,false);

        $channel->queue_bind($queue,$exhange);

        $message_body=json_encode(['Email'=>$email,
            'UsrName'=>$UsrName,
            'Password'=>$Pass,
            'Gender'=>$Gender
            ]);
        $message = new AMQPMessage($message_body,['Content/type' => 'application/json' ,
            'delivery_mode'=> AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $channel->basic_publish($message,$exhange);

        $channel->close();
        $connection->close();
        /*$message = (new \Swift_Message('Verify Your Email'))
        ->setFrom('test@example.com')
        ->setTo($user->getEmail())
        ->setBody(
            $this->twig->render(
                 'emails/registration.html.twig'
                ,
               array('user'=>$user)
            ),
            'text/html'
        )
        ->addPart(
            $this->twig->render(
                'emails/registration.txt.twig',
                array('user'=>$user)
                ),
            'text/plain'
            );        
     $this->mailer->send($message);*/

    }
}