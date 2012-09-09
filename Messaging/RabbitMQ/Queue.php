<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\RabbitMQ;

use \Aurora\BokaBokaBundle\Messaging\RabbitMQ\Connection\Manager;
use \Aurora\BokaBokaBundle\Messaging\Traits\Annotated;
use \Aurora\BokaBokaBundle\Messaging\Traits\ConnectionRelatedObject;
use \Aurora\BokaBokaBundle\Messaging\Traits\DecoratedObject;
use \Aurora\BokaBokaBundle\Messaging\Annotations as Messaging;
use \Aurora\BokaBokaBundle\Messaging\Interfaces\Bindable;

/**
 * @Messaging\Queue("default")
 */
class Queue implements Bindable
{
    use Annotated {
        Annotated::__construct as annotate;
    }
    use ConnectionRelatedObject;
    use DecoratedObject;

    public function __construct($name = null, $connection = 'default') {

        if($name !== null && !empty($name)) {
            $this->setName($name);
        }
        $this->setConnection($connection);
        $this->annotate();
    }

    /**
     * @param bool $auto_ack
     */
    public function getOne($auto_ack = true)
    {
        return $this->getRelated()->get();
    }

    public function get($items = 1, $auto_ack = true)
    {
        $collection = [];
        for($i = 0; $i < $items; $i++) {
            $item = $this->getOne($auto_ack);
            if($item !== null) {
                $collection[] = $item;
            }
        }
        return $collection;
    }

    public function consumeOne()
    {
        $items =  $this->consume(1);
        if(isset($items[0])) {
            return $items[0];
        }
        return null;
    }

    public function setName($name)
    {
        $this->setIfRelated('name', $name);
    }

    public function getName()
    {
        $this->getIfRelated('name');
    }

    protected function getRelated()
    {
        return $this->getRelatedObject('\AMQPQueue', $this->getConnection());
    }


    public function __toString()
    {
        return json_encode([
            'name' => $this->getName()
        ]);
    }

}