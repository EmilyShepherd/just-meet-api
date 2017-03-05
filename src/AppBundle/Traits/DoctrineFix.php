<?php

namespace JustMeet\AppBundle\Traits;

trait DoctrineFix
{
    public function __call($method, $args)
    {
        return $this->$method;
    }
}
