<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

use Uicms\App\Service\Model;
use Uicms\App\Service\Form;
use Uicms\Admin\Form\Type\UIFormType;

class FormAnswer
{
    private $entity = 'Answer';
	private $params = [];
    private $ui_config;
    private $model;
    private $request;
    private $session;
    private $translator;
    private $clone;

    public function __construct(ParameterBagInterface $parameters, Model $model, RequestStack $request, SessionInterface $session, TranslatorInterface $translator, Form $form, FlashBagInterface $flash)
    {
        $this->params = $parameters;
        $this->ui_config = $parameters->get('ui_config');
        $this->model = $model;
        $this->request = $request->getCurrentRequest();
        $this->route = $this->request->attributes->get('_route_params');
        $this->session = $session;
        $this->translator = $translator;
        $this->form = $form;
        $this->flash = $flash;
    }

    public function get($id=0, $parent=null)
    {
		$form_config = $this->ui_config['entity']['App\Entity\\' . $this->entity]['form'];

        if(!(int)$id || (!$row = $this->model->get($this->entity)->getRowById($id))) {
            $row = $this->model->get($this->entity)->mode('admin')->new();
        }
        
        # Parent
        $form_config['fields']['parent_answer']['options']['label'] = false;
        if($parent) {
            $form_config['fields']['parent_answer']['options']['data'] = $parent;
        }

       	return $this->form->get($this->entity, $row, $form_config);
    }

    public function handle($form)
    {
        # Posted data
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $row = $form->getData();
            $row->setContributor($this->model->get('Contributor')->getRowById($this->session->get('contributor')->getId()));
            $row->setContribution($this->model->get('Contribution')->getRowById($this->route['id']));
            
            try {
                $message = $row->getId() ? 'answer_updated' : 'answer_inserted';
                $this->model->get($this->entity)->mode('admin')->persist($row);
                $this->flash->add('success', $message);
                return $row;
            } catch (\Throwable $throwable) {
                throw new \Exception('Form handling error!');
            }
        } else {
            return false;
        }
    }
}