<?php

namespace Aurora\BokaBokaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use \Aurora\BokaBokaBundle\Messaging\AMQP\Message;

class SimpleMessage extends Message {}

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('rabbit:test')
            ->setDescription('Test publish and pull message from queue');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $connection = $this->getContainer()->get('boka_boka.connection.default');

        $queue = $this->getContainer()->get('boka_boka.queue.default');
        $exchange = $this->getContainer()->get('boka_boka.exchange.default');

        $message = new SimpleMessage();
        $message->setTitle('test');
        $message->setBody('asdfasdfasdf asdf asd fas');
        $message->getHeaders()->add("test_1", "test");
        $message->getAttributes()->setAppId('test');

        $output->writeln("message:: ".serialize($message));

        $output->writeln("connection:: ". (string)$connection);
        $output->writeln("queue:: ". (string)$queue);
        $output->writeln("exchange:: ". (string)$exchange);
        $output->writeln("message:: ". (string)$message);

        $exchange->publish($message);

        $message1 = $queue->getOne();
        $output->writeln("message:: ". (string)$message1);
    }

}