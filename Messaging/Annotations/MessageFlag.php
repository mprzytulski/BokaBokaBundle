<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 09.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Annotations;

class MessageFlag
{

    const NONE      = AMQP_NOPARAM;
    const MANDATORY = AMQP_MANDATORY;
    const IMMEDIATE = AMQP_IMMEDIATE;

}