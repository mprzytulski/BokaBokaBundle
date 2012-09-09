<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\RabbitMQ;

use \Aurora\BokaBokaBundle\Messaging\Traits\Annotated;
use \Aurora\BokaBokaBundle\Messaging\Traits\ConnectionRelatedObject;
use \Aurora\BokaBokaBundle\Messaging\Traits\DecoratedObject;
use \Aurora\BokaBokaBundle\Messaging\Annotations as Messaging;
use \Aurora\BokaBokaBundle\Messaging\Annotations\ExchangeType;
use \Aurora\BokaBokaBundle\Messaging\Interfaces\Bindable;
use \Aurora\BokaBokaBundle\Messaging\Interfaces\Message;

/**
 * @Messaging\Exchange("default")
 */
class Exchange implements Bindable
{
    use Annotated {
        Annotated::__construct as annotate;
    }
    use ConnectionRelatedObject;
    use DecoratedObject;


    public function __construct($name = '', $type = ExchangeType::DIRECT, $connection = null)
    {
        $this->setName($name);
        $this->setType($type);
        $this->setConnection($connection);
        $this->annotate();
    }

    protected function getRelated()
    {
        return $this->getRelatedObject('\AMQPExchange', $this->getConnection()->getChannel());
    }

    public function publish(Message $message)
    {
        return $this->getRelated()->publish($message->getBody(), $message->getRoutingKey(), $message->getFlags(), $message->getAttributes());
    }

    public function bind(Bindable $obj)
    {
        return $this->getRelated()->bind($obj);
    }

    public function setName($name)
    {
        $this->setIfRelated('name', $name);
    }

    public function getName()
    {
        $this->getIfRelated('name');
    }

    public function setType($type)
    {
        $this->setIfRelated('type', $type);
    }

    public function getType()
    {
        $this->getIfRelated('type');
    }

    public function setDurable($durable = true)
    {
        $this->setIfRelated('durable', $durable);
    }

    public function isDurable()
    {
        $this->getIfRelated('durable');
    }

    public function setPassiv($passiv = true)
    {
        $this->setIfRelated('passiv', $passiv);
    }

    public function isPassiv()
    {
        $this->getIfRelated('passiv');
    }

    public function delete()
    {
        $this->getRelated()->delete();
    }

    public function __toString()
    {
        return json_encode([
            'name' => $this->getName(),
            'type' => ExchangeType::asString($this->getType()),
            'durable' => $this->isDurable() ? 'true' : 'false',
            'passiv' => $this->isPassiv() ? 'true' : 'false',
        ]);
    }

}