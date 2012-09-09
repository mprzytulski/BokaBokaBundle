<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\RabbitMQ;

use \Aurora\BokaBokaBundle\Messaging\Traits\Annotated;
use \Aurora\BokaBokaBundle\Messaging\Interfaces\Message as MessageInterface;

class Message extends \AMQPEnvelope implements MessageInterface
{

    use Annotated {
        Annotated::__construct as annotate;
    }

    protected $body_parts = array();
    protected $routing_key = null;

    public function __construct($routing_key = null, array $body_parts = array())
    {
        $this->routing_key = $routing_key;
        $this->body_parts = $body_parts;
        $this->annotate();
    }

    public function addParameter($name, $value)
    {
        if(is_object($value) && !($value instanceof JsonSerializable)) {
            throw new \InvalidArgumentException("Failed to add non JsonSerializable object as a part of a message");
        }
        $this->body_parts[$name] = $value;
    }

    public function __call($name, $params)
    {
        if(strpos($name, 'set') === 0) {
            $this->addParameter(strtolower(substr($name, 3)), $params[0]);
            return;
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
        return array();
    }

    public function getBody()
    {
        return json_encode($this->body_parts);
    }

    public function __toString()
    {
        return json_encode(['routing_key' => $this->routing_key, 'body' => $this->body_parts]);
    }

}