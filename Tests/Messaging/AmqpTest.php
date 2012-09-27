<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 26.09.2012
 */

namespace Aurora\BokaBokaBundle\Tests\Messaging;

use Symfony\Bundle\FrameworkBundle\Console\Application;

use Aurora\BokaBokaBundle\Messaging\AMQP\Message;
use Aurora\BokaBokaBundle\Messaging\AMQP\Exchange;
use Aurora\BokaBokaBundle\Messaging\AMQP\Connection;
use Aurora\BokaBokaBundle\Messaging\AMQP\Queue;
use Aurora\BokaBokaBundle\Messaging\Serializer\Json;

abstract class AmqpTest extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{

    protected $_kernel;
    protected $_container;
    protected $_application;
    protected $serializer;
    protected $connection;
    protected $exchange;
    protected $queue;

    public function setUp()
    {
        // Boot the AppKernel in the test environment and with the debug.
        $this->_kernel = static::createKernel();
        $this->_kernel->boot();

        // Store the container and the entity manager in test case properties
        $this->_container = $this->_kernel->getContainer();

//        $this->application = new Application($this->kernel);

        $this->serializer = new Json();
        $this->connection = new Connection('10.8.0.10', 5672, '/');
        $this->exchange = new Exchange($this->connection, $this->serializer, 'test');
        $this->queue = new Queue($this->connection, 'test', $this->exchange);

        $this->queue->purge();

        parent::setUp();
    }

}
