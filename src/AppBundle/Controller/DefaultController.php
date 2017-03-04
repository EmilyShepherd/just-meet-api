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
     * Gets all users
     *
     * @Route("/users", name="users")
     */
    public function getUsersAction()
    {
        return new JsonResponse($this->jsonSerialize(
            $this->getEntityManager()->getRepository(User::class)
                ->findAll()
            ));
    }

    /**
     * Gets the meetings a user can see
     *
     * @Route("/user/{id}/meetings", name="meetings")
     */
    public function getMeetingsActions($id)
    {
        $meetings = $this->getEntityManager()->getRepository(Meeting::class)
            ->findByAttendingUser($this->getUserOrFail($id));

        return new JsonResponse($this->jsonSerialize($meetings));
    }

    /**
     * Gets information about a user
     *
     * @Route("/user/{id}", name="user")
     */
    public function getUserAction($id)
    {
        return new JsonResponse($this->jsonSerialize(
            $this->getUserOrFail($id)
        ));
    }

    private function getUserOrFail($id)
    {
        $user = $this->getEntityManager()->getRepository(User::class)
            ->findOneById($id);

        if (!$user)
        {
            $this->notFound('A user with this ID was not found');
        }

        return $user;
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
