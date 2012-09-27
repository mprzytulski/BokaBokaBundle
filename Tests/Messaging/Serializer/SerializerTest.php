<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */


namespace Aurora\BokaBokaBundle\Tests\Messaging\Serializer;

use Aurora\BokaBokaBundle\Messaging\Serializer\PHP;
use Aurora\BokaBokaBundle\Messaging\AMQP\Message;
use Aurora\BokaBokaBundle\Messaging\Serializer\Json;


class SerializerTest extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{

    public function testJsonSerializer()
    {
        $serializer = new Json();
        $message_out = new Message();
        $message_out->setTitle('test');
        $message_out->setBody('body test');

        $this->assertEquals($serializer->serialize($message_out), '{"title":"test","body":"body test"}');
        $this->assertEquals($serializer->deserialize('{"title":"test","body":"body test"}'), array('title'=>'test', 'body' => 'body test'));
    }

    public function testPHPSerializer()
    {
        $serializer = new PHP();
        $message_out = new Message();
        $message_out->setTitle('test');
        $message_out->setBody('body test');

        $this->assertEquals($serializer->serialize($message_out), 'a:2:{s:5:"title";s:4:"test";s:4:"body";s:9:"body test";}');
        $this->assertEquals($serializer->deserialize('a:2:{s:5:"title";s:4:"test";s:4:"body";s:9:"body test";}'), array('title'=>'test', 'body' => 'body test'));
    }

}