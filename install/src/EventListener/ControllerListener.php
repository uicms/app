<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ControllerListener
{	
    public function __construct(RequestStack $requestStack, ParameterBagInterface $parameters)
    {
        $this->requestStack = $requestStack;
		$this->request = $requestStack->getCurrentRequest();
		$this->session = $this->request->getSession();
    }
	
	public function onKernelController(FilterControllerEvent $event)
	{
        # Path package
		$path_package = new PathPackage('themes/app', new StaticVersionStrategy('v1'));
		$this->session->set('app_path', $path_package);
        
		#$path_package = new PathPackage('themes/admin', new StaticVersionStrategy('v2'));
		#$this->session->set('admin_path', $path_package);
	}
}