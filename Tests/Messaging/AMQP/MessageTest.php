<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */


//namespace Aurora\BokaBokaBundle\Tests\Messaging\AMQP;

use Symfony\Component\Console\Tester\CommandTester;
use Aurora\BokaBokaBundle\Messaging\AMQP\Message;
use Aurora\BokaBokaBundle\Tests\Messaging\AmqpTest;

class TestMessage extends Message
{

}

class MessageTest extends AmqpTest
{

    public function testPublish()
    {
        $message_out = new TestMessage();
        $message_out->setTitle('test');
        $this->assertTrue($this->exchange->publish($message_out));

        $message_in = $this->queue->getOne();

        $this->assertEquals($message_out->getTitle(), $message_in->getTitle());
    }

    public function testSerializationType()
    {
        $message_out = new TestMessage();
        $message_out->setTitle('test');
        $this->assertTrue($this->exchange->publish($message_out));

        $message_in = $this->queue->getOne();

        $this->assertEquals(get_class($message_out), get_class($message_in));
    }

    public function testAck()
    {
        $message_out = new TestMessage();
        $message_out->setTitle('test');
        $this->exchange->publish($message_out);

        $message_in = $this->queue->getOne(false);

        $this->assertFalse($message_in->isAck());
        $this->assertTrue($message_in->ack());
        $this->assertTrue($message_in->isAck());

        $message_in = $this->queue->getOne();
        $this->assertNull($message_in);

    }

    public function testHeaders()
    {
        $val = microtime();

        $message_out = new TestMessage();
        $message_out->setTitle('test');
        $message_out->getHeaders()->add('X-Custom-Header', $val);
        $this->assertTrue($this->exchange->publish($message_out));

        $message_in = $this->queue->getOne();

        $this->assertEquals($message_in->getHeaders()->get('X-Custom-Header'), $val);
    }

}