<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 08.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Annotations;

class ExchangeType
{

    const DIRECT  = AMQP_EX_TYPE_DIRECT;
    const TOPIC   = AMQP_EX_TYPE_TOPIC;
    const FANOUT  = AMQP_EX_TYPE_FANOUT;
    const HEADER  = AMQP_EX_TYPE_HEADER;

    public static function asString($type)
    {
        if($type === false || $type === null) {
            return 'UNKNOWN';
        }
        switch($type) {
            case self::DIRECT:
                return 'DIRECT';
            case self::TOPIC:
                return 'TOPIC';
            case self::FANOUT:
                return 'FANOUT';
            case self::HEADER:
                return 'HEADER';
            default:
                return null;
        }
    }

}