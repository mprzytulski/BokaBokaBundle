<?php

namespace Aurora\BokaBokaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use \Aurora\BokaBokaBundle\Messaging\Annotations as Messaging;
use \Aurora\BokaBokaBundle\Messaging\RabbitMQ\Queue;
use \Aurora\BokaBokaBundle\Messaging\RabbitMQ\Exchange;
use \Aurora\BokaBokaBundle\Messaging\RabbitMQ\Message;

/**
 * @Messaging\Queue("test")
 */
class TestQueue extends Queue {}

/**
 * @Messaging\Exchange("test", type=Messaging\ExchangeType::FANOUT, durable=false)
 */
class TestExchange extends Exchange {}

/**
 * @Messaging\Message()
 */
class SimpleMessage extends Message {}

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('rabbit:test')
            ->setDescription('Greet someone')
            ->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


//        $queue = new TestQueue();
//        $exchange = new TestExchange();

        $connection = $this->getContainer()->get('boka_boka.connection.default');

        $queue = $this->getContainer()->get('boka_boka.queue.default');
        $exchange = $this->getContainer()->get('boka_boka.exchange.default');

//        $queue->bind($exchange, 'test');

        $message = new SimpleMessage();
        $message->setTitle('test');
        $message->setBody('asdfasdfasdf asdf asd fas');
        $message->getHeaders()->add("X-Custom-Header", "test");

        $output->writeln("connection:: ". (string)$connection);
        $output->writeln("queue:: ". (string)$queue);
        $output->writeln("exchange:: ". (string)$exchange);
        $output->writeln("message:: ". (string)$message);

        $exchange->publish($message);

        $message1 = $queue->getOne();
        $output->writeln("message:: ". (string)$message1);
    }

}