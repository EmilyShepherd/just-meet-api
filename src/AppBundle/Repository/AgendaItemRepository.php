<?php

namespace JustMeet\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use JustMeet\AppBundle\Entity\User;

class AgendaItemRepository extends EntityRepository
{
    public function findByMeetingIdAndId($meetingId, $id)
    {
        $return = $this->createQueryBuilder('a')
            ->join('a.meeting', 'm')
            ->where('a.id = :id')->setParameter('id', $id)
            ->andWhere('m.id = :meetingId')->setParameter('meetingId', $meetingId)
            ->getQuery()
            ->getResult();

        return empty($return) ? null : $return[0];
    }
}
