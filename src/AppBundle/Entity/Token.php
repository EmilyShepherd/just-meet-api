<?php

namespace JustMeet\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Spaark\CompositeUtils\Traits\AllReadableTrait;
use Spaark\CompositeUtils\Traits\AutoConstructTrait;
use Doctrine\Common\Collections\ArrayCollection;
use JustMeet\AppBundle\Traits\DoctrineFix;

/**
 * Token
 *
 * @ORM\Table(name="token")
 * @ORM\Entity
 * @JMS\ExclusionPolicy("all")
 * @IgnoreAnnotation("readable")
 * @IgnoreAnnotation("writable")
 * @IgnoreAnnotation("construct")
 */
class Token
{
    use AllReadableTrait;
    use AutoConstructTrait;
    use DoctrineFix;

    /**
     * @var string
     * @ORM\Column(name="token", type="string")
     * @ORM\Id
     * @JMS\Expose
     * @JMS\Groups({"full"})
     * @construct required
     */
    protected $token;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="JustMeet\AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @JMS\Expose
     * @JMS\Groups({"full"})
     * @readable
     * @writable
     * @construct required
     */
    protected $user;
}
