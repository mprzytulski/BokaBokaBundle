<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */


namespace Aurora\BokaBokaBundle\Tests\Messaging\RabbitMQ;

use Aurora\BokaBokaBundle\Messaging\Annotations as Messaging;
use Aurora\BokaBokaBundle\Messaging\RabbitMQ\Exchange;

/**
 * @Messaging\Exchange("test")
 */
class TestExchange extends Exchange {}

class ExchangeTest extends \PHPUnit_Framework_TestCase
{

    public function testAnnotation()
    {
        $test = new TestExchange();
        $this->assertEquals($test->getName(), 'test');
        $this->assertEquals($test->getType(), Messaging\ExchangeType::DIRECT);
    }

}