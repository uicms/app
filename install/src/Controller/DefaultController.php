<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HtmlController extends AbstractController
{
    public function index($page)
    {
		return $this->render(
		            'app/tpl/html/index.html.twig',
		            ['page'=>$page]
		        );
    }
}