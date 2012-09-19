<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\RabbitMQ;

use \Aurora\BokaBokaBundle\Messaging\Traits\DecoratedObject;

class Connection extends \AMQPConnection
{

    use DecoratedObject;


    private $channel = null;

    public function __construct($host = 'localhost', $port = 3333, $vhost = '/', $login = '', $password ='')
    {
        parent::__construct(array(
            'host' => $host,
            'port' => $port,
            'vhost' => $vhost,
            'login' => $login,
            'password' => $password
        ));
        $this->connect();
    }

    public function getRelated($to_write = false)
    {
        return $this->getRelatedObject('\AMQPConnection', array($this->getConnection()->getChannel()), $to_write);
    }

    public function connect()
    {
        parent::connect();
        $this->channel = new \AMQPChannel($this);
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function __destruct()
    {
        if($this->isConnected()) {
            $this->disconnect();
        }
    }

    public function __toString()
    {
        return json_encode(array(
                'host' => $this->getHost(),
                'port' => $this->getPort(),
                'vhost' => $this->getVhost(),
                'login' => $this->getLogin()
            ));
    }


}