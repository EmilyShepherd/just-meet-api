<?php

namespace JustMeet\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JustMeet\AppBundle\Entity\Meeting;
use JustMeet\AppBundle\Entity\User;
use JustMeet\AppBundle\Entity\AgendaItem;
use JustMeet\AppBundle\Entity\Action;
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

    /**
     * Creates a new meeting
     *
     * ## Input
     * ```
     *  {
     *      "name": "Event Name",
     *      "start_time": "The start Time",
     *      "end_time": "Optional end time"
     *  }
     * ```
     *
     * @Route("/user/{id}/meetings", name="create_meeting")
     * @Method({"POST"})
     * @ApiDoc(
     *      requirements={
     *          {
     *              "name"="name"
     *          },
     *          {
     *              "name"="start_time"
     *          },
     *          {
     *              "name"="end_time"
     *          }
     *      }
     * )
     */
    public function createMeetingAction(Request $request, $id)
    {
        $user = $this->getUserOrFail($id);

        $meeting = new Meeting();

        $meeting->name = $this->getRequired($request, 'name');

        $meeting->startTime =
            new \DateTime($this->getRequired($request, 'start_time'));

        if ($value = $request->request->get('end_time'))
        {
            $meeting->endTime = new \DateTime($value);
        }

        $user->meetings->add($meeting);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->persist($meeting);
        $this->getEntityManager()->flush();

        return new JsonResponse($this->jsonSerialize($meeting));
    }

    /**
     * Gets a given meeting
     *
     * @Route("/meeting/{id}", name="get_meeting")
     * @Method({"GET"})
     * @ApiDoc
     */
    public function getMeeting($id)
    {
        $meeting = $this->getMeetingOrFail($id);

        return new JsonResponse($this->jsonSerialize($meeting));
    }

    /**
     * Update meeting
     *
     * @Route("/meeting/{id}", name="update_meeting")
     * @Method({"PUT"})
     * @ApiDoc(
     *      requirements={
     *          {
     *              "name"="name"
     *          },
     *          {
     *              "name"="start_time"
     *          },
     *          {
     *              "name"="end_time"
     *          }
     *      }
     * )

     */
    public function updateMeetingAction(Request $request, $id)
    {
        $meeting = $this->getMeetingOrFail($id);

        if ($value = $request->request->get('name'))
        {
            $meeting->name = $value;
        }

        if ($value = $request->request->get('start_time'))
        {
            $meeting->startTime = $value;
        }

        if ($value = $request->request->get('end_time'))
        {
            $meeting->endTime = $value;
        }

        $this->getEntityManager()->persist($meeting);
        $this->getEntityManager()->flush();

        return new JsonResponse($this->jsonSerialize($meeting));
    }

    /**
     * Add agenda item to meeting
     *
     * @Route("/meeting/{id}/agenda", name="add_agenda_item")
     * @Method({"POST"})
     * @ApiDoc(
     *      requirements={
     *          {
     *              "name"="topic"
     *          },
     *          {
     *              "name"="description"
     *          }
     *      }
     * )
     */
    public function addAgendaItemAction(Request $request, $id)
    {
        $agenda = new AgendaItem();
        $agenda->meeting = $this->getMeetingOrFail($id);
        $agenda->topic = $this->getRequired($request, 'topic');

        if ($value = $request->request->get('description'))
        {
            $agenda->description = $value;
        }

        $this->getEntityManager()->persist($agenda);
        $this->getEntityManager()->flush();

        return new JsonResponse($this->jsonSerialize($agenda, 'item'));
    }

    /**
     * Update's an agenda item to
     *
     * @Route("/meeting/{meetingId}/agenda/{agendaId}", name="update_agenda_item")
     * @Method({"PUT"})
     * @ApiDoc(
     *      requirements={
     *          {
     *              "name"="topic"
     *          },
     *          {
     *              "name"="description"
     *          }
     *      }
     * )
     */
    public function updateAgendaItemAction(Request $request, $meetingId, $agendaId)
    {
        $agenda = $this->getAgendaItemOrFail($meetingId, $agendaId);

        if ($value = $request->request->get('topic'))
        {
            $agenda->topic = $value;
        }

        if ($value = $request->request->get('description'))
        {
            $agenda->description = $value;
        }

        $this->getEntityManager()->persist($agenda);
        $this->getEntityManager()->flush();

        return new JsonResponse($this->jsonSerialize($agenda, 'item'));
    }

    /**
     * Add action point
     *
     * @Route("/meeting/{id}/actions", name="add_action_point")
     * @Method({"POST"})
     * @ApiDoc(
     *      requirements={
     *          {
     *              "name"="topic"
     *          },
     *          {
     *              "name"="description"
     *          }
     *      }
     * )
     */
    public function addActionPointAction(Request $request, $id)
    {
        $action = new Action();
        $action->meeting = $this->getMeetingOrFail($id);
        $action->topic = $this->getRequired($request, 'topic');

        if ($value = $request->request->get('description'))
        {
            $action->description = $value;
        }

        $this->getEntityManager()->persist($action);
        $this->getEntityManager()->flush();

        return new JsonResponse($this->jsonSerialize($action, 'item'));
    }

    /**
     * Updates an action point
     *
     * @Route("/meeting/{meetingId}/action/{actionId}", name="update_action_point")
     * @Method({"PUT"})
     * @ApiDoc(
     *      requirements={
     *          {
     *              "name"="topic"
     *          },
     *          {
     *              "name"="description"
     *          },
     *          {
     *              "name"="users",
     *              "dataType"="array"
     *          }
     *      }
     * )
     */
    public function updateActionPointAction(Request $request, $meetingId, $actionId)
    {
        $action = $this->getActionPointOrFail($meetingId, $actionId);

        if ($value = $request->request->get('topic'))
        {
            $action->topic = $value;
        }

        if ($value = $request->request->get('description'))
        {
            $action->description = $value;
        }

        if ($value = $request->request->get('users'))
        {
            $action->users->clear();
            if (!is_array($value))
            {
                throw new \Exception('Users should be array');
            }

            foreach ($value as $uid)
            {
                $action->users->add($this->getUserOrFail($uid));
            }
        }

        $this->getEntityManager()->persist($action);
        $this->getEntityManager()->flush();

        return new JsonResponse($this->jsonSerialize($action, 'item'));
    }

    /**
     * Adds a user to an action
     *
     * @Route("/meeting/{meetingId}/action/{actionId}/users", name="add_action_user")
     * @Method({"POST"})
     * @ApiDoc(
     *      requirements={
     *          {
     *              "name"="id"
     *          }
     *      }
     * )
     */
    public function addUserToActionPointAction
    (
        Request $request,
        $meetingId,
        $actionId
    )
    {
        $action = $this->getActionPointOrFail($meetingId, $actionId);
        $user = $this->getUserOrFail($this->getRequired($request, 'id'));

        $action->users->add($user);

        $this->getEntityManager()->persist($action);
        $this->getEntityManager()->flush();

        return new JsonResponse($this->jsonSerialize($action, 'item'));
    }

    /**
     * Removes a user from an action
     *
     * @Route("/meeting/{meetingId}/action/{actionId}/user/{userId}", name="remove_action_user")
     * @Method({"DELETE"})
     * @ApiDoc
     */
    public function removeUserFromActionPointAction
    (
        Request $request,
        $meetingId,
        $actionId,
        $userId
    )
    {
        $action = $this->getActionPointOrFail($meetingId, $actionId);
        $user = $this->getUserOrFail($userId);

        $action->users->removeElement($user);

        $this->getEntityManager()->persist($action);
        $this->getEntityManager()->flush();

        return new JsonResponse($this->jsonSerialize($action, 'item'));
    }

    private function getRequired(Request $request, $name)
    {
        $value = $request->request->get($name);

        if (!$value)
        {
            throw new \Exception($name . ' is required');
        }

        return $value;
    }

    private function getAgendaItemOrFail($meetingId, $id)
    {
        return $this->getMeetingItemOrFail
        (
            AgendaItem::class,
            'agenda item',
            $meetingId,
            $id
        );
    }

    private function getActionPointOrFail($meetingId, $id)
    {
        return $this->getMeetingItemOrFail
        (
            Action::class,
            'action point',
            $meetingId,
            $id
        );
    }

    private function getMeetingItemOrFail($class, $name, $meetingId, $id)
    {
        return $this->getEntityOrFail
        (
            $class,
            $name,
            function($repo) use ($meetingId, $id)
            {
                return $repo->findByMeetingIdAndId($meetingId, $id);
            }
        );
    }

    private function getUserOrFail($id)
    {
        return $this->getEntityByIdOrFail($id, User::class, 'user');
    }

    private function getMeetingOrFail($id)
    {
        return $this->getEntityByIdOrFail($id, Meeting::class, 'meeting');
    }

    private function getEntityByIdOrFail($id, $class, $name)
    {
        return $this->getEntityOrFail
        (
            $class,
            $name,
            function($repo) use ($id)
            {
                return $repo->findOneById($id);
            }
        );
    }

    private function getEntityOrFail($class, $name, $cb)
    {
        $user = $cb($this->getEntityManager()->getRepository($class));

        if (!$user)
        {
            $this->notFound('A ' . $name . ' with this ID was not found');
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

    private function jsonSerialize($item, $group = 'full')
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);
        $context->setGroups([$group]);
        $serializer = SerializerBuilder::create()->build();

        return $serializer->toArray($item, $context);
    }


    /**
     * @Route("/{any}", name="preflight", requirements={"any"=".+"})
     * @Method({"OPTIONS"})
     */
    public function preflightAction()
    {
        return new Response('', 204, array('Content-Type' => 'text/plain charset=UTF-8', 'Content-Length' => 0));
    }

}
