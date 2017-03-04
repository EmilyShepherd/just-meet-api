<?php

namespace JustMeet\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spaark\CompositeUtils\Traits\AllReadableTrait;

/**
 * AgendaItem
 *
 * @ORM\Table(name="agenda")
 * @ORM\Entity
 */
class AgendaItem
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
     * @ORM\Column(name="topic", type="string")
     */
    private $topic;

    /**
     * @var string
     * @ORM\Column(name="description", type="string")
     */
    private $description;

    /**
     * @var Meeting
     * @ORM\ManyToOne(targetEntity="JustMeet\AppBundle\Entity\Meeting")
     * @ORM\JoinColumns(
     *      @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", onDelete="CASCADE")
     * )
     */
    private $meeting;
}

