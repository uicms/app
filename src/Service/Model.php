<?php

namespace Uicms\App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Doctrine\ORM\EntityManager;

class Model
{	
	public function __construct(EntityManager $em, RequestStack $requestStack, ParameterBagInterface $parameters)
    {
		$request = $requestStack->getCurrentRequest();
		$this->session = $request->getSession();
		$this->em = $em;
    }
	
    public function get($entity_name)
    {
        if(strpos($entity_name, 'App\\') === false) {
            $entity_name = 'App\Entity\\' . ucfirst($entity_name);
        }
        $model = $this->em->getRepository($entity_name);
		$model->locale($this->session->get('locale'));
		return $model;
    }
}