<?php

namespace JustMeet\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Spaark\CompositeUtils\Traits\AllReadableTrait;

/**
 * Action
 *
 * @ORM\Table(name="action")
 * @ORM\Entity
 * @JMS\ExclusionPolicy("all")
 */
class Action
{
    use AllReadableTrait;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMS\Expose
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="topic", type="string")
     * @JMS\Expose
     */
    private $topic;

    /**
     * @var string
     * @ORM\Column(name="description", type="string")
     * @JMS\Expose
     */
    private $description;

    /**
     * @var Meeting
     * @JMS\Expose
     * @ORM\ManyToOne(targetEntity="JustMeet\AppBundle\Entity\Meeting")
     * @ORM\JoinColumns(
     *      @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", onDelete="CASCADE")
     * )
     */
    private $meeting;
}

