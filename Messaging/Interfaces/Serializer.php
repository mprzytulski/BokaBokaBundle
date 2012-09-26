<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 20.09.2012
 */


namespace Aurora\BokaBokaBundle\Messaging\Interfaces;


interface Serializer
{

    public function serialize(Message $message);
    public function deserialize($raw_message);

}
