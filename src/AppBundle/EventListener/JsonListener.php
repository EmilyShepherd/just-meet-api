<?php                                                                    
namespace JustMeet\AppBundle\EventListener;                  
                                                                         
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;            
                                                                         
/**                                                                      
 * JsonListener will allow symfony2 to handle real json-applications to requests
 * as angular uses "Content-Type: application/json" per default: http://docs.angularjs.org/api/ng.$http#settinghttpheaders
 */                                                                      
class JsonListener                                                       
{                                                                        
    /*                                                                   
     * Defines to add the listener to the onKernelController             
     */                                                                  
    public function onKernelController(FilterControllerEvent $event)        
    {                                                                    
        $controller = $event->getController();                           
                                                                         
        /*                                                               
         * $controller passed can be either a class or a Closure. This is not usual in Symfony2 but it may happen.
         * If it is a class, it comes in array format                    
         */                                                              
        if (!is_array($controller)) {                                    
            return;                                                      
        }                                                                
                                                                         
        /*                                                               
         * Replace request vars with the json object attributes          
         */                                                              
        $request = $event->getRequest();                                 
                                                                         
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);           
            $request->request->replace(is_array($data) ? $data : array());
        }                                                                
    }                                                                    
}
