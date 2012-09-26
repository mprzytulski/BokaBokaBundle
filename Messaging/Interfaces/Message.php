<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 09.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Interfaces;

interface Message
{
    public function getRoutingKey();
    public function getFlags();
    public function getAttributes();
    public function getHeaders();
    public function getBody();
}