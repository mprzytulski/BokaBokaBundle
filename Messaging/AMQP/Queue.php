<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\AMQP;

use Aurora\BokaBokaBundle\Messaging\AMQP\Connection\Manager;
use Aurora\BokaBokaBundle\Messaging\Traits\ConnectionRelatedObject;
use Aurora\BokaBokaBundle\Messaging\Traits\DecoratedObject;
use Aurora\BokaBokaBundle\Messaging\Interfaces\Bindable;
use Aurora\BokaBokaBundle\Messaging\AMQP\Connection;
use Aurora\BokaBokaBundle\Messaging\Events\MessageReceivedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Aurora\BokaBokaBundle\Messaging\Events\MessageEvents;

/**
 * Queue class
 */
class Queue implements Bindable
{

    use ConnectionRelatedObject;
    use DecoratedObject;

    /**
     * @var array of exchanges related to queue
     */
    protected $exchanges;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface Event dispatcher used for message event propagation
     */
    protected $dispatcher = null;

    /**
     * Create AMQP Message Queue with given params
     *
     * @param $connection Connection used to define queue
     * @param string $name Queue name
     * @param null $exchange Exchange to bind to
     * @param string $bind_key Bind key
     */
    public function __construct($connection, $name = 'default', Exchange $exchange = null, $bind_key = 'default') {
        $this->setConnection($connection);
        $this->setName($name);
        $this->getRelated();
        if($exchange != null) {
            $this->bind($exchange, $bind_key);
        }
    }

    /**
     * Set EventDispatcher used for populate Message events, mainly used by consume,
     * if not set no event's will be propagated.
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Bind queue to given Exchange with given routing key
     *
     * @param Exchange $exchange
     * @param $routing_key
     */
    public function bind(Exchange $exchange, $routing_key)
    {
        $this->exchanges[$exchange->getName()] = $exchange;
        $this->getRelated(true)->bind($exchange->getRelated(true)->getName(), $routing_key);
    }

    /**
     * Get one message from queue
     *
     * @param bool $auto_ack Get message and auto ack if true, when false get message without ack
     * @return \Aurora\BokaBokaBundle\Messaging\AMQP\Message
     */
    public function getOne($ack = true)
    {
        $flags = $ack ? AMQP_AUTOACK : 0;
        $raw = $this->getRelated()->get($flags);
        if(!$raw) {
            return null;
        }
        return Message::create($raw, $this, $this->exchanges[$raw->getExchangeName()], $ack);
    }

    /**
     * Get given number of messages from queue, if queue contains less than given number method
     * will return all messages from queue
     *
     * @param int $items Number of messages to receive
     * @param bool $ack Get message and auto ack if true, when false get message without ack
     * @return array Returns array of \Aurora\BokaBokaBundle\Messaging\AMQP\Message
     */
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

    /**
     * Consume given number of messages in blocking mode, method will wait until consume given number.
     * Every message will dispatch MessageEvents::MESSAGE_RECEIVE event
     *
     * @param int $messages Number of messages to consume
     */
    public function consume($messages = null)
    {
        $this->consummed = 0;
        $this->consume_limit = $messages;
        $this->getRelated(true)->consume(array($this, 'onMessage'));
    }

    /**
     * Internal method used for new message creation and event propagation
     *
     * @param \AMQPEnvelope $raw
     * @return bool
     */
    public function onMessage(\AMQPEnvelope $raw)
    {
        $this->consummed++;

        $msg =  Message::create($raw, $this, $this->exchanges[$raw->getExchangeName()], true);
        $event = new MessageReceivedEvent($msg);

        $this->dispatcher->dispatch(MessageEvents::MESSAGE_RECEIVED, $event);

        if($this->consume_limit !== null && $this->consume_limit == $this->consummed) {
            return false;
        }
        return true;
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

    /**
     * Acknowledge given message
     *
     * @param Message $message
     * @return mixed
     */
    public function ack(Message $message)
    {
        $x = $this->getRelated(true)->ack($message->getDeliveryTag());
        $message->isAck($x);
        return $x;
    }

    /**
     * Mark message as un acknowledged
     *
     * @param Message $message
     * @return mixed
     */
    public function nack(Message $message)
    {
        $x = $this->getRelated(true)->nack($message->getDeliveryTag());
        $message->isAck(!$x);
        return $x;
    }

    /**
     * Purge all messages from queue
     *
     * @return mixed
     */
    public function purge()
    {
        return $this->getRelated(true)->purge();
    }

    /**
     * Delete given queue from broker
     *
     * @return mixed
     */
    public function delete()
    {
        return $this->getRelated(true)->delete();
    }

    public function getAttributes()
    {
        return $this->getRelated(true)->getArgument('message-count');
    }

    /**
     * Return text representation of queue
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode([
            'name' => $this->getName()
        ]);
    }

}