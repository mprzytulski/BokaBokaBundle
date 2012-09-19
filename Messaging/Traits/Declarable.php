<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 09.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\Traits;

trait Declarable
{
    protected $declared = false;

    protected function declaration()
    {
        if(!$this->declared) {
            $this->declared = true;
        }
    }

}