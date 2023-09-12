<?php
namespace App\Service;

use Uicms\App\Service\Model;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

class MenuHelper
{
    protected $twig = null;
    protected $result = '';

    public function __construct(Model $model, SessionInterface $session, Environment $twig)
    {
        $this->session = $session;
        $this->model = $model;
        $this->twig = $twig;
    }

    public function get($page) {
        if($page->getHelper()) {
            eval("\$html = \$this->get" . $page->getHelper() . "Result(\$page);");
        } else {
            $html = '';
        }
        return $html;
    }

    /* Helpers */
    public function getBlocksResult($page)
    {
        $blocks = $this->model->get('Block')->getAll(array('linked_to'=>'Page', 'linked_to_id'=>$page->getId()));
        $html = $this->twig->render('app/tpl/helper/menublocks.html.twig', [
            'session'=>$this->session, 'page'=>$page, 'blocks' => $blocks
        ]);
        return $html;
    }
}