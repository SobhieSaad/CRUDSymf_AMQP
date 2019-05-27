<?php
namespace App;
use App\Entity\Users;

use Symfony\Component\Messenger\Envelope;

use Symfony\Component\Messenger\MessageBusInterface;
class EventsBus implements MessageBusInterface{

    /**
     * @var MessageBusInterface
     */

    private $messageBus;

    /**
     * EventsBus constructor.
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }


    /**
     * @return Envelope
     */
    public function dispatch($message) : Envelope
    {
        $this->messageBus->dispatch($message);
    }

}