<?php

namespace JustMeet\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JustMeet\AppBundle\Entity\Meeting;
use JustMeet\AppBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
     * ## Return
     * ```
     *  [
     *      {
     *          "id":11,
     *          "first_name":
     *          "Emily",
     *          "second_name":"Shepherd",
     *          "email":"emily.shepherd@wearetwogether.com"
     *      },
     *      ...
     * ]
     * ```
     *
     * @Route("/users", name="users")
     * @Method({"GET"})
     * @ApiDoc(
     *      resource=true,
     *      output={
     *          "class"="JustMeet\AppBundle\Entity\User"
     *      }
     * )
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
     * ## Return
     * ```
     *  [
     *      {
     *          "id":11,
     *          "name": "Hackathon Planning",
     *          "start_time": "...",
     *          "end_time": "..."
     *      },
     *      ...
     * ]
     * ```
     * @Route("/user/{id}/meetings", name="meetings")
     * @Method({"GET"})
     * @ApiDoc(
     *      resource=true,
     *      output={
     *          "class"="JustMeet\AppBundle\Entity\Meeting"
     *      }
     * )
     * @param int $id The id of the user
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
     * ## Return
     * ```
     *  {
     *      "id":11,
     *      "first_name":
     *      "Emily",
     *      "second_name":"Shepherd",
     *      "email":"emily.shepherd@wearetwogether.com"
     *  }
     * ```
     * @Route("/user/{id}", name="user")
     * @Method({"GET"})
     * @ApiDoc(
     *      resource=true,
     *      output={
     *          "class"="JustMeet\AppBundle\Entity\User"
     *      }
     * )
     * @param int $id The id of the user
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
