<?php

namespace JustMeet\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use JustMeet\AppBundle\Entity\User;

class MeetingRepository extends EntityRepository
{
    public function findByAttendingUser(User $user)
    {
        return $this->createQueryBuilder('m')
            ->join('m.attendees', 'a')
            ->where('a.id = :id')->setParameter('id', $user->id)
            ->getQuery()
            ->getResult();
    }
}
