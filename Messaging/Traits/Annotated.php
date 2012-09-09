<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 09.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Traits;

use \Aurora\BokaBokaBundle\Messaging\Annotations\Reader\AnnotationReader;

trait Annotated
{
    protected static $annotationReader = null;

    public function __construct()
    {
        if(self::$annotationReader == null) {
            self::$annotationReader = new AnnotationReader;
        }
        self::$annotationReader->annotate($this);
    }

}