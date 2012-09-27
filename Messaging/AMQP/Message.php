<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\AMQP;

use \Aurora\BokaBokaBundle\Messaging\Interfaces\Message as MessageInterface;
use \Aurora\BokaBokaBundle\Messaging\AMQP\Message\Attributes;
use \Aurora\BokaBokaBundle\Messaging\AMQP\Message\Headers;
use \Aurora\BokaBokaBundle\Messaging\AMQP\Message\Flags;
use \Aurora\BokaBokaBundle\Messaging\AMQP\Exchange;

class Message implements MessageInterface
{

    protected $body_parts = array();
    protected $routing_key = null;

    protected $attributes;
    protected $headers;
    protected $flags;

    protected $delivery_tag;

    protected $queue;

    protected $ack = false;

    public function __construct($routing_key = 'default', array $body_parts = array(), Flags $flags = null, Attributes $attrs = null, Headers $headers = null, $ack = true)
    {
        $this->routing_key = $routing_key;
        $this->body_parts = $body_parts;

        if ($flags === null) {
            $flags = new Flags();
        }
        $this->flags = $flags;

        if ($attrs === null) {
            $attrs = new Attributes();
        }

        if ($headers === null) {
            $headers = new Headers();
        }

        $this->headers = $headers;
        $this->attributes = $attrs;
        $this->ack = $ack;
    }

    public function getDefaults()
    {
        return $this->defaults;
    }

    public function addParameter($name, $value)
    {
        if(is_object($value) && !($value instanceof JsonSerializable)) {
            throw new \InvalidArgumentException("Failed to add non JsonSerializable object as a part of a message");
        }
        $this->body_parts[$name] = $value;
    }

    public function getParameter($name)
    {
        if(isset($this->body_parts[$name])) {
            return $this->body_parts[$name];
        }
        return null;
    }

    public function __call($name, $params)
    {
        if(strpos($name, 'set') === 0) {
            $this->addParameter(strtolower(substr($name, 3)), $params[0]);
            return;
        }
        elseif(strpos($name, 'get') === 0) {
            return $this->getParameter(strtolower(substr($name, 3)));
        }
        throw new \RuntimeException("Method not exists");
    }

    public function getRoutingKey()
    {
        return $this->routing_key;
    }

    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @return \Aurora\BokaBokaBundle\Messaging\AMQP\Message\Attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttibutes(MessageAttributes $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return \Aurora\BokaBokaBundle\Messaging\AMQP\Message\Headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders(Headers $headers)
    {
        $this->headers = $headers;
    }

    public function getBody()
    {
        return $this->body_parts;
    }


    protected function setQueue(Queue $queue)
    {
        $this->queue = $queue;
    }

    public function getDeliveryTag()
    {
        return $this->delivery_tag;
    }

    public function isAck($ack = true)
    {
        if(func_num_args() == 1) {
            $this->ack = $ack;
        }
        return $this->ack;
    }

    public function ack()
    {
        $this->queue->ack($this);
        return true;
    }

    public function nack()
    {
        $this->queue->nack($this);
        return true;
    }


    public static function create(\AMQPEnvelope $raw, Queue $queue, Exchange $exchange, $ack = true)
    {

        $body = $exchange->getSerializer()->deserialize($raw->getBody());

        $attrs = new Attributes(array(
            Attributes::APP_ID           => $raw->getAppId(),
            Attributes::CONTENT_ENCODING => $raw->getContentEncoding(),
            Attributes::CONTENT_TYPE     => $raw->getContentType(),
            Attributes::EXPIRATION       => $raw->getExpiration(),
            Attributes::PRIORITY         => $raw->getPriority(),
            Attributes::REPLY_TO         => $raw->getReplyTo(),
            Attributes::TIMESTAMP        => $raw->getTimestamp(),
            Attributes::MESSAGE_ID       => $raw->getMessageId(),
            Attributes::USER_ID          => $raw->getUserId(),
            Attributes::TYPE             => $raw->getType()
        ));

        $headers = new Headers($raw->getHeaders());

        $flags = new Flags();

        $msg_class = new \ReflectionClass($headers->get('X-PHP-Type', 'Message'));
        $msg = $msg_class->newInstanceArgs(array(
                $raw->getRoutingKey(), $body, $flags, $attrs, $headers, $ack
            ));

        $msg->setQueue($queue);
        $msg->delivery_tag = $raw->getDeliveryTag();
        return $msg;
    }

    public function __toString()
    {
        return json_encode([
            'routing_key' => $this->routing_key,
            'body' => $this->body_parts,
            'attributes' => $this->getAttributes(),
            'flags' => $this->getFlags(),
            'headers' => $this->getHeaders()]);
    }

}