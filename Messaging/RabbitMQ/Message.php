<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\RabbitMQ;

use \Aurora\BokaBokaBundle\Messaging\Interfaces\Message as MessageInterface;
use \Aurora\BokaBokaBundle\Messaging\RabbitMQ\Message\Attributes;
use \Aurora\BokaBokaBundle\Messaging\RabbitMQ\Message\Headers;

class Message implements MessageInterface
{

    protected $body_parts = array();
    protected $routing_key = null;

    protected $attributes;

    public function __construct($routing_key = 'default', array $body_parts = array(), Attributes $attrs = null, Headers $headers = null)
    {
        $this->routing_key = $routing_key;
        $this->body_parts = $body_parts;

        if ($attrs === null) {
            $attrs = new Attributes();
        }

        if ($headers === null) {
            $headers = new Headers();
        }

        $this->headers = $headers;
        $this->attributes = $attrs;
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
        elseif(strpos($name, 'set') === 0) {
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
        return 0;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttibutes(MessageAttributes $attributes)
    {
        $this->attributes = $attributes;
    }

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
        return json_encode($this->body_parts);
    }

    public static function create(\AMQPEnvelope $raw)
    {
        $body = json_decode($raw->getBody(), true);
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

//        const DELIVERY_MODE    = 'delivery_mode';

        $msg = new Message($raw->getRoutingKey(), $body, $attrs, $headers);
        return $msg;
    }

    public function __toString()
    {
        return json_encode(['routing_key' => $this->routing_key, 'body' => $this->body_parts, $this->attributes]);
    }

}