<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */


namespace Aurora\BokaBokaBundle\Tests\Messaging\AMQP;

use Aurora\BokaBokaBundle\Messaging\AMQP\Exchange;


class ExchangeTest extends \PHPUnit_Framework_TestCase
{
/*
    public function setUp()
    {
        // Boot the AppKernel in the test environment and with the debug.
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();

        // Store the container and the entity manager in test case properties
        $this->container = $this->kernel->getContainer();

        $this->application = new \Application($this->kernel);
    }
*/
    public function testCreate()
    {
        $this->assertTrue(true);
//        $test = new TestExchange();
//        $this->assertEquals($test->getName(), 'test');
//        $this->assertEquals($test->getType(), Messaging\ExchangeType::DIRECT);
    }

}