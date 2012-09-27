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

        $message_out = new SimpleMessage();
        $message_out->setTitle('Subject');
        $message_out->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $message_out->getHeaders()->add("X-Test-Header", "true");
        $message_out->getAttributes()->setAppId('Symfony 2.0 Messaging Test');

        $output->writeln("connection:: ". (string)$connection);
        $output->writeln("queue:: ". (string)$queue);
        $output->writeln("exchange:: ". (string)$exchange);
        $output->writeln("message:: ". (string)$message_out);

        $exchange->publish($message_out);

        $message_in = $queue->getOne();
        $output->writeln("message:: ". (string)$message_in);
    }

}