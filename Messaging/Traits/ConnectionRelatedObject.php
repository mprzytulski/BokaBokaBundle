<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 09.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Traits;

use \Aurora\BokaBokaBundle\Messaging\Annotations\Reader\AnnotationReader;

trait ConnectionRelatedObject
{

    /**
     * @var Connection
     */
    public $connection;

    protected static $connection_manager;

    protected function getConnectionManager()
    {
        if(!self::$connection_manager) {
            global $kernel;
            if ('AppCache' == get_class($kernel)) {
                $kernel = $kernel->getKernel();
            }
            self::$connection_manager = $kernel->getContainer()->get('boka_boka.connection_manager');
        }
        return self::$connection_manager;
    }

    public function getConnection()
    {
        if($this->connection !== null && (is_string($this->connection))) {
            $this->connection = $this->getConnectionManager()->get($this->connection);
            parent::__construct($this->connection->getChannel());
        }
        return $this->connection;
    }

    public function setConnection($connection)
    {
        if($connection == null) {
            return;
        }

        if(is_object($connection)) {
            if(($connection instanceof Connection)) {
                $this->connection = $connection;
                return;
            }
            else {
                throw new \InvalidArgumentException('Invalid connection object [1]: '.get_class($connection));
            }
        }

        if(is_string($connection)) {
            if(!$this->getConnectionManager()->has($connection)) {
                throw new \InvalidArgumentException('Invalid connection name [2]: '.$connection);
            }
            $this->connection = $connection;
            return;
        }

        throw new \InvalidArgumentException('Invalid connection object [3]: '.var_export($connection, true));
    }

}