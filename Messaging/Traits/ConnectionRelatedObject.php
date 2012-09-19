<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 09.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Traits;

use \Aurora\BokaBokaBundle\Messaging\RabbitMQ\Connection;

trait ConnectionRelatedObject
{

    /**
     * @var Connection
     */
    public $connection;


    public function getConnection()
    {
        return $this->connection;
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

}