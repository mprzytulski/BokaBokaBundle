<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\RabbitMQ;

class Connection extends \AMQPConnection
{

    private $channell = null;

    public function __construct($host = 'localhost', $port = 3333, $vhost = '/', $login = '', $password ='')
    {
        parent::__construct(array(
            'host' => $host,
            'port' => $port,
            'vhost' => $vhost,
            'login' => $login,
            'password' => $password
        ));
    }

    public function connect()
    {
        parent::connect();
        $this->channell = new AMQPChannel($this);
    }

    public function getChannel()
    {
        return $this->channell;
    }

    public function __destruct()
    {
        if($this->isConnected()) {
            $this->disconnect();
        }
    }

}