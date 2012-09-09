<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Annotations\Reader;

use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;

class AnnotationReader extends DoctrineAnnotationReader
{

    public function annotate($originalObject)
    {

        $reflectionClass = new \ReflectionClass($originalObject);
        $annotationClass = null;
        if($originalObject instanceof \Aurora\BokaBokaBundle\Messaging\RabbitMQ\Queue) {
            $annotationClass = '\\Aurora\\BokaBokaBundle\\Messaging\\Annotations\\Queue';
        }
        else if ($originalObject instanceof \Aurora\BokaBokaBundle\Messaging\RabbitMQ\Exchange) {
            $annotationClass = '\\Aurora\\BokaBokaBundle\\Messaging\\Annotations\\Exchange';
        }
        else if ($originalObject instanceof \Aurora\BokaBokaBundle\Messaging\RabbitMQ\Message) {
            $annotationClass = '\\Aurora\\BokaBokaBundle\\Messaging\\Annotations\\Message';
        }

        $annotation = $this->getClassAnnotation($reflectionClass, $annotationClass);
        if (null !== $annotation) {
            $annotation->setValues($originalObject);
        }

        return $originalObject;
    }

}