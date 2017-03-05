<?php

namespace JustMeet\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Spaark\CompositeUtils\Traits\PropertyAccessTrait;

/**
 * AgendaItem
 *
 * @ORM\Table(name="agenda")
 * @ORM\Entity(repositoryClass="JustMeet\AppBundle\Repository\AgendaItemRepository")
 * @JMS\ExclusionPolicy("all")
 * @IgnoreAnnotation("readable")
 * @IgnoreAnnotation("writable")
 */
class AgendaItem
{
    use PropertyAccessTrait;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMS\Expose
     * @readable
     * @JMS\Groups({"item", "full"})
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="topic", type="string")
     * @JMS\Expose
     * @readable
     * @writable
     * @JMS\Groups({"item", "full"})
     */
    protected $topic;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", nullable=true)
     * @JMS\Expose
     * @readable
     * @writable
     * @JMS\Groups({"item", "full"})
     */
    protected $description;

    /**
     * @var Meeting
     * @readable
     * @writable
     * @JMS\Expose
     * @JMS\Groups({"item"})
     * @ORM\ManyToOne(targetEntity="JustMeet\AppBundle\Entity\Meeting", inversedBy="agendaItems")
     * @ORM\JoinColumns(
     *      @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", onDelete="CASCADE")
     * )
     */
    protected $meeting;
}

