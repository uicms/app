<?php

namespace Uicms\App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Doctrine\ORM\EntityManager;

class Model
{	
    protected $flush_mode = 'auto';
    
	public function __construct(EntityManager $em, RequestStack $requestStack, ParameterBagInterface $parameters)
    {
		$request = $requestStack->getCurrentRequest();
        if($request !== null) {
            $this->session = $request->getSession();
        }
		$this->em = $em;
    }
	
    public function get($entity_name)
    {
        if(strpos($entity_name, 'App\\') === false) {
            $entity_name = 'App\Entity\\' . ucfirst($entity_name);
        }
        $repository = $this->em->getRepository($entity_name);
        if(isset($this->session) && $this->session !== null && $this->session->get('locale')) {
            $repository->locale($this->session->get('locale'));
        }
        $repository->setFlushMode($this->flush_mode);
        
		return $repository;
    }
    
    public function setFlushMode($mode)
    {
        $this->flush_mode = $mode;
    }
}