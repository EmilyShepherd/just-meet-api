<?php

namespace JustMeet\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spaark\CompositeUtils\Traits\AllReadableTrait;

/**
 * Meeting
 *
 * @ORM\Table(name="meeting")
 * @ORM\Entity
 */
class Meeting
{
    use AllReadableTrait;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var \DateTime
     * @ORM\Column(name="start_time", type="datetime")
     */
    private $startTime;

    /**
     * @var \DateTime
     * @ORM\Column(name="end_time", type="datetime")
     */
    private $endTime;

    /**
     * @var User[]
     * @ORM\ManyToMany(targetEntity="JustMeet\AppBundle\Entity\User")
     * @ORM\JoinTable(name="attendee",
     *      joinColumns={
     *          @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *      }
     * )
     */
    private $attendees;

    /**
     * @var Action[]
     * @ORM\OneToMany(targetEntity="JustMeet\AppBundle\Entity\Action", mappedBy="meeting")
     */
    private $actions;

    /**
     * @var AgendaItem[]
     * @ORM\OneToMany(targetEntity="JustMeet\AppBundle\Entity\AgendaItem", mappedBy="meeting")
     */
    private $agendaItems;
}

