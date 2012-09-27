<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */


namespace Aurora\BokaBokaBundle\Tests\Messaging\AMQP;

use Aurora\BokaBokaBundle\Messaging\AMQP\Message;
use Aurora\BokaBokaBundle\Tests\Messaging\AmqpTest;


//class TestQueue extends Queue {}

class QueueTest extends AmqpTest
{

    public function testPurge()
    {

        $message_out = new Message();
        $message_out->setTitle('test');
        $this->assertTrue($this->exchange->publish($message_out));
        $this->assertTrue($this->queue->purge());
        $this->assertNull($this->queue->getOne());

    }

}