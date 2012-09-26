<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\AMQP;

use \Aurora\BokaBokaBundle\Messaging\Traits\ConnectionRelatedObject;
use \Aurora\BokaBokaBundle\Messaging\Traits\DecoratedObject;
use \Aurora\BokaBokaBundle\Messaging\AMQP\Exchange\Type;
use \Aurora\BokaBokaBundle\Messaging\AMQP\Connection;
use \Aurora\BokaBokaBundle\Messaging\Interfaces\Bindable;
use \Aurora\BokaBokaBundle\Messaging\Interfaces\Message as MessageInterface;
use \Aurora\BokaBokaBundle\Messaging\Interfaces\Serializer;


/**
 * @Messaging\Exchange("default")
 */
class Exchange implements Bindable
{
    use ConnectionRelatedObject;
    use DecoratedObject;

    protected $serializer;

    public function __construct(Connection $connection, Serializer $serializer, $name = 'default', $type = Type::DIRECT)
    {
        $this->serializer = $serializer;
        $this->setConnection($connection);
        $this->setName($name);
        $this->setType($type);
        $this->getRelated(true);
    }

    public function getSerializer()
    {
        return $this->serializer;
    }

    public function getRelated($to_write = false)
    {
        return $this->getRelatedObject('\AMQPExchange', array($this->getConnection()->getChannel()), $to_write);
    }

    public function publish(MessageInterface $message)
    {
        return $this->getRelated(true)->publish(
            $this->serializer->serialize($message),
            $message->getRoutingKey(),
            $message->getFlags()->asInt(),
            array_merge(
                $message->getAttributes()->asArray(),
                array(
                    'headers' => $message->getHeaders()->asArray()
                )
            )
        );
    }

    public function bind(Bindable $obj)
    {
        return $this->getRelated()->bind($obj);
    }

    public function setName($name)
    {
        $this->getRelated()->setName($name);
    }

    public function getName()
    {
        return $this->getRelated()->getName();
    }

    public function setType($type)
    {
        $this->getRelated()->setType($type);
    }

    public function getType()
    {
        return $this->getRelated()->getType();
    }

    public function setDurable($durable = true)
    {
        $this->setIfRelated('durable', $durable);
    }

    public function isDurable()
    {
        return $this->getIfRelated('durable');
    }

    public function setPassiv($passiv = true)
    {
        $this->setIfRelated('passiv', $passiv);
    }

    public function isPassiv()
    {
        return $this->getIfRelated('passiv');
    }

    public function delete()
    {
        $this->getRelated()->delete();
    }

    public function __toString()
    {
        return json_encode([
            'name' => $this->getName(),
            'type' => Type::asString($this->getType()),
            'durable' => $this->isDurable() ? 'true' : 'false',
            'passiv' => $this->isPassiv() ? 'true' : 'false',
        ]);
    }

}