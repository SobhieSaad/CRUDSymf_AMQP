<?php
namespace App;
use App\Entity\Users;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Envelope;

class CommandBus implements MessageBusInterface {

    /**
     * @var MessageBusInterface
     */

    private $messageBus;

    /**
     * CommandBus constructor.
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }


    /**
     * @param object|Envelope $message
     * @return Envelope|void
     */
    public function dispatch($message) : Envelope
    {
        $this->messageBus->dispatch($message);
    }

}