<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 20.09.2012
 */


namespace Aurora\BokaBokaBundle\Messaging\Serializer;

use \Aurora\BokaBokaBundle\Messaging\Interfaces\Serializer;
use \Aurora\BokaBokaBundle\Messaging\Interfaces\Message;

class Json implements Serializer
{

    public function serialize(Message $message)
    {
        return json_encode($message->getBody());
    }

    public function deserialize($raw_message)
    {
        return json_decode($raw_message, true);
    }
}
