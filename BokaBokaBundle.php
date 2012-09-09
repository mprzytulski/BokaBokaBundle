<?php

namespace Aurora\BokaBokaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use \Aurora\BokaBokaBundle\Messaging\RabbitMQ\Connection\ConnectionCompilerPass;


class BokaBokaBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConnectionCompilerPass());
    }

}
