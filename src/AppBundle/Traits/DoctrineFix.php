<?php

namespace JustMeet\AppBundle\Traits;

/**
 * This trait is required because symfony twig refuses to attempt to
 * access a property if it's not natively public, even if a __get
 * method is present. It will, however, call it as a method if a __call
 * method exists, so this trait hacks that into Enties.
 *
 * Will consider moving into spaark/composite-utils in a slightly more
 * neat way
 */
trait DoctrineFix
{
    public function __call($method, $args)
    {
        return $this->$method;
    }
}
