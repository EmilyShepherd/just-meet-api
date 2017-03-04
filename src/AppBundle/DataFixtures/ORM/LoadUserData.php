<?php

namespace JustMeet\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JustMeet\AppBundle\Entity\User;
use JustMeet\AppBundle\Entity\Meeting;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $accessor = new RawPropertyAccessor($user);
        $accessor->setRawValue('firstName', 'Emily');
        $accessor->setRawValue('secondName', 'Shepherd');
        $accessor->setRawValue('email', 'emily.shepherd@wearetwogether.com');
        $manager->persist($user);

        $user2 = new User();
        $accessor = new RawPropertyAccessor($user2);
        $accessor->setRawValue('firstName', 'Jon');
        $accessor->setRawValue('secondName', 'Busby');
        $accessor->setRawValue('email', 'jon.busby@wearetwogether.com');
        $manager->persist($user2);

        $manager->flush();

        $meeting = new Meeting();
        $accessor = new RawPropertyAccessor($meeting);
        $accessor->setRawValue('name', 'Hackathon Planing');
        $accessor->setRawValue('startTime', new \DateTime());
        $meeting->attendees->add($user);
        $meeting->attendees->add($user2);
        $manager->persist($meeting);

        $manager->flush();
    }
}
