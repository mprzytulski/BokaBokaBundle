<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 09.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Traits;

trait DecoratedObject
{

    protected $related;

    protected $declared = false;

    protected $proxy_values = array();

    protected function getRelatedObject($klass, $args, $to_write = false)
    {
        $reflection = null;
        if(!$this->related || ($to_write && !$this->declared)) {
            $reflection = new \ReflectionClass($klass);
        }

        if(!$this->related) {
            $this->related = $reflection->newInstanceArgs($args);
        }

        if($to_write && !$this->declared && $reflection->hasMethod('declare')) {
            $this->related->declare();
        }

        return $this->related;
    }

    protected function setIfRelated($param, $value)
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

    protected function getIfRelated($name)
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