<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 09.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Annotations;

abstract class Base
{

    public $name;
    public $connection;


    public function __construct($options)
    {
        if (isset($options['value'])) {
            $options['name'] = $options['value'];
            unset($options['value']);
        }

        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->$key = $value;
        }
    }

    public function setValues($originalObject)
    {
        foreach(get_class_vars(get_class($this)) as $property => $value) {
            $setter = 'set'.ucfirst($property);
            if(method_exists($originalObject, $setter) && $this->$property != null) {
                call_user_func_array(array($originalObject, $setter), array($this->$property));
            }
        }
    }

}