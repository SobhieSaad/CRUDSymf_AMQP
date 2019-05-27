<?php

namespace App\MessageHandler;

use App\Entity\Users;
use App\Message\Command\SendEmailVerivcationEmail;
use App\Repository\UsersRepository;
use Swift_Message;

class SendEmailVerivicationHandler{


    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var UsersRepository
     */
    private $userRepository;
    /**
     * SendEmailVerification constructor.
     * @param Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param UsersRepository $userRepository
     *
     */
    public function __construct(Swift_Mailer $mailer,\Twig_Environment $twig,
                                UsersRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->userRepository = $userRepository;
    }

    public function __invoke(SendEmailVerivcationEmail $sendEmailVerification)
    {
        $userId= $sendEmailVerification->getUserId();
        $user=$this->userRepository->find($userId);

        $message = (new Swift_Message('Verify Your Email'))
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



        $this->mailer->send($message);

    }
}