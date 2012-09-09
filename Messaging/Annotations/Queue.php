<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Annotations;

use \Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 */
class Queue extends Base
{

    public $name = null;

}