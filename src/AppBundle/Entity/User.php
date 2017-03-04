<?php

namespace JustMeet\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Spaark\CompositeUtils\Traits\AllReadableTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @JMS\ExclusionPolicy("all")
 */
class User
{
    use AllReadableTrait;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMS\Expose
     * @JMS\Groups({"full"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string")
     * @JMS\Expose
     * @JMS\Groups({"full"})
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(name="second_name", type="string")
     * @JMS\Expose
     * @JMS\Groups({"full"})
     */
    private $secondName;

    /**
     * @var string
     * @ORM\Column(name="email", type="string")
     * @JMS\Expose
     * @JMS\Groups({"full"})
     */
    private $email;

    /**
     * @var Meeting
     * @ORM\ManyToMany(targetEntity="JustMeet\AppBundle\Entity\Meeting", inversedBy="attendees")
     * @ORM\JoinTable(name="attendee",
     *      joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", onDelete="CASCADE")
     *      }
     * )
     */
    private $meetings;
    
    /**
     * @var Action
     * @ORM\ManyToMany(targetEntity="JustMeet\AppBundle\Entity\Action")
     * @ORM\JoinTable(name="action_user",
     *      joinColumns={
     *          @ORM\JoinColumn(name="action_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *      }
     * )
     */
    private $actions;

    public function __construct()
    {
        $this->meetings = new ArrayCollection();
        $this->actions = new ArrayCollection();
    }
}

