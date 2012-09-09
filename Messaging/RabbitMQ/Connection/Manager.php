<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\RabbitMQ\Connection;

use \Symfony\Component\DependencyInjection\ContainerInterface;

class Manager
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $connection_id_template = 'boka_boka.connection.%s';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @static
     * @param $name
     * @return \Aurora\BokaBokaBundle\Messaging\RabbitMQ\Connection
     * @throws \InvalidArgumentException
     */
    public function get($name)
    {
        if(!$this->has($name)) {
            throw new \InvalidArgumentException('No connection: '.$name);
        }
        $c =  $this->container->get(sprintf($this->connection_id_template, $name));
        if(!$c->isConnected()) {
            $c->connect();
        }
        return $c;
    }

    public function has($name)
    {
        return $this->container->has(sprintf($this->connection_id_template, $name));
    }

    public function registryConnection($id, $connection)
    {

    }




}