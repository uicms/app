<?php
namespace App\Service;

use Uicms\App\Service\Model;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MenuHelper
{    
    /* Helpers */
    protected function getBlocksResult()
    {
        $blocks = $this->model->get('Block')->getAll(array('linked_to'=>'Page', 'linked_to_id'=>$this->page->getId()));
        $twig = $this->getTwig('app/tpl/helper/menublocks.html.twig');
        return $twig->render(['session'=>$this->session, 'page'=>$this->page, 'blocks' => $blocks]);
    }
    
    
    /* Private */
    protected $result = "";

	public function __construct(\App\Entity\Page $page, Model $model, $session)
    {
        $this->session = $session;
        $this->page = $page;
        $this->model = $model;
        eval("\$this->result = \$this->get" . $page->getHelper() . "Result();");
        return $this;
    }
    
    public function getResult()
    {
        return $this->result;
    }
    
    protected function getTwig($template_file)
    {
        $loader = new \Twig\Loader\FilesystemLoader('/home/osty/public/themes');
        $twig = new \Twig\Environment($loader, [
            'cache' => 'var/cache/dev', # FIX THAT
            'debug' => true,
        ]);
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        $twig->addExtension(new \Uicms\app\Twig\SluggerExtension());
        
        $template = $twig->load($template_file);
        return $template;
    }
}