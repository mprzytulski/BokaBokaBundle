BokaBokaBundle
==============


This is full object oriented wrapper over apqp extension AMQP* classes
providing unifided API for sending and processing messages.

Bundle provides few basic classes:
* Connection
* Exchange
* Queue
* Message
* Serializer

Full broker configuration and connection, exchange, broker configuration is provided as as Symfony 2.0 services.

### Parameters:

#### classes parameters:

* boka_boka.connection.class Aurora\BokaBokaBundle\Messaging\AMQP\Connection - default connection class
* boka_boka.exchange.class Aurora\BokaBokaBundle\Messaging\AMQP\Exchange - default exchange class
* boka_boka.queue.class Aurora\BokaBokaBundle\Messaging\AMQP\Queue - default queue class
* boka_boka.serializer.json.class Aurora\BokaBokaBundle\Messaging\Serializer\Json - json serializer class
* boka_boka.serializer.php.class Aurora\BokaBokaBundle\Messaging\Serializer\PHP - php serializer class
* boka_boka.connection_manager.class Aurora\BokaBokaBundle\Messaging\AMQP\Connection\Manager - connection manager class

#### connection parameters:
* boka_boka.connection.default.host 10.8.0.10 - message broker host address
* boka_boka.connection.default.port 5672 - message broker port
* boka_boka.connection.default.vhost / - message boroker vhost
* boka_boka.connection.default.user - message broker auth user name
* boka_boka.connection.default.password - message broker auth user password

#### Default services:

* boka_boka.serializer.default - default Message serializer - json
* boka_boka.connection.default - default connection
* boka_boka.exchange.default - default exchange - default
* boka_boka.queue.default - default queue


### Requierments:

* php 5.4+ with spl and spl_types
* symfony 2.0 +
* amqp pecl extension


### Example usage:

    class SimpleMessage extends Message {

        public function setTitle($title)
        {
            $this->addParameter('title', $title);
        }

        public function getTitle()
        {
            return $this->getParameter('title');
        }

        public function setBody($body)
        {
            $this->setParameter('body', $body);
        }

        public function getBody($body)
        {
            return $this->getParameter('body');
        }

    }

    ...

    $exchange = $this->getContainer()->get('boka_boka.exchange.default');

    $message_out = new SimpleMessage();
    $message_out->setTitle('test');
    $message_out->setBody('asdfasdfasdf asdf asd fas');
    $message_out->getHeaders()->add("test_1", "test");
    $message_out->getAttributes()->setAppId('test');

    echo "exchange:: ". (string)$exchange);
    echo "message:: ". (string)$message_out);

    $exchange->publish($message_out);

    $message_in = $queue->getOne();
    echo "message:: ". (string)$message_in;