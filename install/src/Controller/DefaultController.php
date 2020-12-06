<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index($page)
    {
		return $this->render(
		            'app/tpl/default/index.html.twig',
		            ['page'=>$page]
		        );
    }
}