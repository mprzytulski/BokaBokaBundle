<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */


namespace Aurora\BokaBokaBundle\Tests\Messaging\RabbitMQ;

use Aurora\BokaBokaBundle\Messaging\Annotations as Messaging;
use Aurora\BokaBokaBundle\Messaging\RabbitMQ\Queue;

/**
 * @Messaging\Queue("test")
 */
class TestQueue extends Queue {}

class QueueTest extends \PHPUnit_Framework_TestCase
{

    public function testAnnotation()
    {
        $test = new TestQueue();
        $this->assertEquals($test->getName(), 'test');
    }

}