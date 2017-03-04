<?php

namespace AppBundle\Service;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
class CorsHeaderService
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Allow-Headers',
            'DNT,Authorization,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type');
        $response->headers->set('Access-Control-Max-Age', 1728000);
    }
}
