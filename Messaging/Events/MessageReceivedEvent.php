<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 27.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Events;

use Symfony\Component\EventDispatcher\Event;
use Aurora\BokaBokaBundle\Messaging\AMQP\Message;
use Aurora\BokaBokaBundle\Messaging\Interfaces\Message as MessageInterface;

class MessageReceivedEvent extends Event
{

    protected $message;

    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

}
