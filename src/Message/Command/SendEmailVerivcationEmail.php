<?php
namespace App\Message\Command;
#require_once __DIR__.'/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Entity\Users;
class SendEmailVerivcationEmail {

    /**
     * @var int
     */
    private $UserId;

    /**
     * UserRegistered constructor.
     * @param int $UserId
     */
    public function __construct(int $UserId)
    {
        $this->UserId = $UserId;

    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->UserId;
    }

}