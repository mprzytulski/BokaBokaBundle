<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\AMQP;

use \Aurora\BokaBokaBundle\Messaging\AMQP\Connection\Manager;
use \Aurora\BokaBokaBundle\Messaging\Traits\ConnectionRelatedObject;
use \Aurora\BokaBokaBundle\Messaging\Traits\DecoratedObject;
use \Aurora\BokaBokaBundle\Messaging\Interfaces\Bindable;
use \Aurora\BokaBokaBundle\Messaging\AMQP\Connection;

class Queue implements Bindable
{

    use ConnectionRelatedObject;
    use DecoratedObject;

    protected $exchanges;

    public function __construct($connection, $name = 'default', $exchange = null, $bind_key = 'default') {
        $this->setConnection($connection);
        $this->setName($name);
        $this->getRelated();
        if($exchange != null) {
            $this->bind($exchange, $bind_key);
        }
    }

    public function bind(Exchange $exchange, $routing_key)
    {
        $this->exchanges[$exchange->getName()] = $exchange;
        $this->getRelated(true)->bind($exchange->getRelated(true)->getName(), $routing_key);
    }

    /**
     * @param bool $auto_ack
     */
    public function getOne($ack = true)
    {
        $flags = $ack ? AMQP_AUTOACK : 0;
        $raw = $this->getRelated()->get($flags);
        if(!$raw) {
            return null;
        }
        return Message::create($raw, $this->exchanges[$raw->getExchangeName()]);
    }

    public function get($items = 1, $ack = true)
    {
        $collection = [];
        for($i = 0; $i < $items; $i++) {
            $item = $this->getOne($ack);
            if($item !== null) {
                $collection[] = $item;
            }
        }
        return $collection;
    }

    public function consumeOne()
    {
        $items =  $this->consume(1);
        if(isset($items[0])) {
            return $items[0];
        }
        return null;
    }

    public function setName($name)
    {
        $this->getRelated()->setName($name);
    }

    public function getName()
    {
        return $this->getRelated()->getName();
    }

    protected function getRelated($to_write = false)
    {
        return $this->getRelatedObject('\AMQPQueue', array($this->getConnection()->getChannel()), $to_write);
    }


    public function __toString()
    {
        return json_encode([
            'name' => $this->getName()
        ]);
    }

}