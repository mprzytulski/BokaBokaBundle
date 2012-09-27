<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 19.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\AMQP\Message;


class Headers implements \JsonSerializable
{

    protected $headers = array();

    public function __construct(array $headers = array())
    {
        $this->headers = $headers;
    }

    public function add($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function get($name, $default = null)
    {
        return isset($this->headers[$name]) ? $this->headers[$name] : $default;
    }

    public function getAll()
    {
        return $this->headers;
    }

    public function asArray()
    {
        return $this->headers;
    }

    public function jsonSerialize()
    {
        return $this->headers;
    }

    public function __toString()
    {
        return json_encode($this->headers);
    }

}