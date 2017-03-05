<?php

namespace JustMeet\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Spaark\CompositeUtils\Traits\AllReadableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use JustMeet\AppBundle\Traits\DoctrineFix;

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
    use DoctrineFix;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMS\Expose
     * @JMS\Groups({"item", "full"})
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string")
     * @JMS\Expose
     * @JMS\Groups({"full"})
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(name="second_name", type="string")
     * @JMS\Expose
     * @JMS\Groups({"full"})
     */
    protected $secondName;

    /**
     * @var string
     * @ORM\Column(name="email", type="string")
     * @JMS\Expose
     * @JMS\Groups({"full"})
     */
    protected $email;

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
    protected $meetings;
    
    /**
     * @var Action
     * @ORM\ManyToMany(targetEntity="JustMeet\AppBundle\Entity\Action", mappedBy="users")
     */
    protected $actions;

    public function getActionsForMeeting(Meeting $meeting)
    {
        $return = [];
        foreach ($this->actions as $action)
        {
            $a = $action->meeting->id;
            $b = $meeting->id;
            if ($action->meeting->id === $meeting->id)
            {
                $return[] = $action;
            }
        }

        return $return;
    }

    public function __construct()
    {
        $this->meetings = new ArrayCollection();
        $this->actions = new ArrayCollection();
    }
}

