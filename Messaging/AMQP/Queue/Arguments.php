<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 27.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\AMQP\Queue;

class Arguments
{
    protected $args = array();

    public function setExpires($time)
    {
        $this->args['x-expires'] = (int)$time;
    }

    public function setMessageTTL($ttl)
    {
        $this->args['x-message-ttl'] = (int)$ttl;
    }

    public function setHAPolicy($policy)
    {
        $this->args['x-ha-policy'] = $policy;
    }

    public function setHAPolicyParams($params)
    {
        $this->args['x-ha-policy-params'] = $params;
    }

}
