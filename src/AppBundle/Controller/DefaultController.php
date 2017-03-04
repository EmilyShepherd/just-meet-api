<?php

namespace JustMeet\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JustMeet\AppBundle\Entity\Meeting;
use JustMeet\AppBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * Gets the meetings a user can see
     *
     * @Route("/user/{id}/meetings", name="meetings")
     */
    public function getMeetingsActions($id)
    {
        $user = $this->getEntityManager()->getRepository(User::class)
            ->findOneById($id);

        if (!$user)
        {
            $this->notFound('A user with this ID was not found');
        }

        $meetings = $this->getEntityManager()->getRepository(Meeting::class)
            ->findByAttendingUser($user);

        return new JsonResponse($this->jsonSerialize($meetings));
    }

    private function notFound($text)
    {
        throw new \Exception($text);
    }

    private function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    private function jsonSerialize($item)
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);
        $serializer = SerializerBuilder::create()->build();

        return $serializer->toArray($item, $context);
    }
}
