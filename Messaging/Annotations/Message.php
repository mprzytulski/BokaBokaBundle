<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Annotations;

/**
 * @Annotation
 */
class Message extends Base
{

    protected $routing_key;

}