<?php

namespace JustMeet\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        if ($id == 3)
        {
            return new JsonResponse(['success' => 'Good show']);
        }
        else
        {
            return new JsonResponse(['error' => 'You are lame']);
        }
    }
}
