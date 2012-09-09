<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 09.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Traits;

trait DecoratedObject
{

    protected $related;

    protected $proxy_values = array();

    public function getRelatedObject($klass, $args)
    {
        if(!$this->related) {
            $reflection = new \ReflectionClass($klass);
            $this->related = $reflection->newInstanceArgs($args);
        }
        return $this->related;
    }

    public function setIfRelated($param, $value)
    {
        if($this->related) {
            $method = 'set'.ucfirst($param);
            if(method_exists($this, $method)) {
                call_user_func_array($this, $method, array($value));
            }
        }
        else {
            $this->proxy_values[$param] = $value;
        }
    }

    public function getIfRelated($name)
    {
        if($this->related) {
            $method = 'get'.ucfirst($name);
            if(method_exists($this, $method)) {
                return call_user_func($this, $method);
            }
        }
        else {
            if(isset($this->proxy_values[$name])) {
                return $this->proxy_values[$name];
            }
        }
    }

}