<?php

namespace JustMeet\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Spaark\CompositeUtils\Traits\PropertyAccessTrait;

/**
 * Action
 *
 * @ORM\Table(name="action")
 * @ORM\Entity(repositoryClass="JustMeet\AppBundle\Repository\ActionRepository")
 * @JMS\ExclusionPolicy("all")
 * @IgnoreAnnotation("readable")
 * @IgnoreAnnotation("writable")
 */
class Action
{
    use PropertyAccessTrait;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMS\Expose
     * @JMS\Groups({"item", "full"})
     * @readable
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="topic", type="string")
     * @JMS\Expose
     * @JMS\Groups({"item", "full"})
     * @readable
     * @writable
     */
    private $topic;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", nullable=true)
     * @JMS\Expose
     * @JMS\Groups({"item", "full"})
     * @readable
     * @writable
     */
    private $description;

    /**
     * @var Meeting
     * @readable
     * @writable
     * @JMS\Groups({"item"})
     * @JMS\Expose
     * @ORM\ManyToOne(targetEntity="JustMeet\AppBundle\Entity\Meeting", inversedBy="actions")
     * @ORM\JoinColumns(
     *      @ORM\JoinColumn(name="meeting_id", referencedColumnName="id", onDelete="CASCADE")
     * )
     */
    private $meeting;

    /**
     * @var User
     * @readable
     * @JMS\Expose
     * @JMS\Groups({"item", "full"})
     * @ORM\ManyToMany(targetEntity="JustMeet\AppBundle\Entity\User", inversedBy="actions")
     * @ORM\JoinTable(name="action_user",
     *      joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="action_id", referencedColumnName="id", onDelete="CASCADE")
     *      }
     * )
     */
    private $users;
}

