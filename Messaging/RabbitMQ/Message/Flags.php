<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 19.09.2012
 */


namespace Aurora\BokaBokaBundle\Messaging\RabbitMQ\Message;

class Flags implements \JsonSerializable
{

    protected $mandatory;
    protected $immediate;

    public function __construct($mandatory = false, $immediate = false)
    {
        $this->mandatory = $mandatory;
        $this->immediate = $immediate;
    }

    public function isMandatory()
    {
        return $this->mandatory;
    }

    public function setMandatory($mandatory = true)
    {
        $this->mandatory = $mandatory;
    }

    public function isImmediate()
    {
        return $this->immediate;
    }

    public function setImmediate($immediate = true)
    {
        $this->immediate = $immediate;
    }

    public function asInt()
    {
        $flags = 0;
        $flags = $this->mandatory ? $flags | AMQP_MANDATORY : $flags;
        $flags = $this->immediate ? $flags | AMQP_IMMEDIATE : $flags;
        return $flags;
    }

    public function __toString()
    {
        return json_encode(array('mandatory' => $this->mandatory, 'immediate' => $this->immediate));
    }

}