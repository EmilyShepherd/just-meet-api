<?php

namespace JustMeet\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Spaark\CompositeUtils\Traits\AllReadableTrait;
use Spaark\CompositeUtils\Traits\AutoConstructTrait;
use Spaark\CompositeUtils\Traits\PropertyAccessTrait;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * Meeting
 *
 * @ORM\Table(name="meeting")
 * @ORM\Entity(repositoryClass="JustMeet\AppBundle\Repository\MeetingRepository")
 * @JMS\ExclusionPolicy("all")
 * @IgnoreAnnotation("construct")
 * @IgnoreAnnotation("readable")
 * @IgnoreAnnotation("writable")
 */
class Meeting
{
    use PropertyAccessTrait;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMS\Expose
     * @readable
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     * @JMS\Expose
     * @readable
     * @writable
     */
    private $name;

    /**
     * @var DateTime
     * @ORM\Column(name="start_time", type="datetime")
     * @JMS\Expose
     * @readable
     * @writable
     */
    private $startTime;

    /**
     * @var DateTime
     * @ORM\Column(name="end_time", type="datetime", nullable=true)
     * @JMS\Expose
     * @readable
     * @writable
     */
    private $endTime;

    /**
     * @var ArrayCollection
     * @construct new
     * @ORM\ManyToMany(targetEntity="JustMeet\AppBundle\Entity\User", mappedBy="meetings")
     * @readable
     */
    private $attendees;

    /**
     * @var ArrayCollection
     * @construct new
     * @ORM\OneToMany(targetEntity="JustMeet\AppBundle\Entity\Action", mappedBy="meeting")
     * @readable
     */
    private $actions;

    /**
     * @var ArrayCollection
     * @construct new
     * @ORM\OneToMany(targetEntity="JustMeet\AppBundle\Entity\AgendaItem", mappedBy="meeting")
     * @readable
     */
    private $agendaItems;

    public function __construct()
    {
        $this->attendees = new ArrayCollection();
        $this->agendaItems = new ArrayCollection();
    }
}

