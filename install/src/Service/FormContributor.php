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

class FormContributor
{
    private $entity = 'Contributor';
	private $params = [];
    private $ui_config;
    private $model;
    private $request;
    private $session;
    private $translator;

    public function __construct(ParameterBagInterface $parameters, Model $model, RequestStack $request, SessionInterface $session, TranslatorInterface $translator, Form $form, FlashBagInterface $flash)
    {
        $this->params = $parameters;
        $this->ui_config = $parameters->get('ui_config');
        $this->model = $model;
        $this->request = $request->getCurrentRequest();
        $this->session = $session;
        $this->translator = $translator;
        $this->form = $form;
        $this->flash = $flash;
    }

    public function get($id=0)
    {
   		# Data
        if((int)$id && ($row = $this->model->get($this->entity)->getRowById($id))) {
        	# Populate extra fields
        } else {
        	$user = $this->model->get('User')->getRowById(1);
            $row = $this->model->get($this->entity)->mode('admin')->new($user);
        }

   		# Add extra fields in form_config
        $form_config = $this->ui_config['entity']['App\Entity\\' . $this->entity]['form'];

        # Build form
       	return $this->form->get($this->entity, $row, $form_config);
    }

    public function handle($form)
    {
        # Posted data
        $form->handleRequest($this->request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $row = $form->getData();

            try {
                $message = $row->getId() ? 'contributor_updated' : 'contributor_inserted';

                if(!$row->getId() && ($exists = $this->model->get($this->entity)->getRow(['findby'=>['email'=>$row->getEmail()]]))) {
                    $this->flash->add('error', 'already_registered');
                    return false;
                }

                if(!$row->getContributorType()) {
                    $default_type = $this->model->get('ContributorType')->getRow(['findby'=>['slug'=>'member']]);
                    $row->setContributorType($default_type);
                }
                
                $this->model->get($this->entity)->mode('admin')->persist($row);
                #$model->get($this->entity)->mode('admin')->unlink([$row->getId()], 'App\Entity\Topic');

                # Link topics
                /*if(isset($_POST['ui_form']['Topics']) && ($new_topics = $_POST['ui_form']['Topics']['topics'])) {
                    $model->get($this->entity)->mode('admin')->link([$row->getId()], 'App\Entity\Topic', $new_topics);
                }*/
                
                $this->flash->add('success', $message);
                return $row;
            } catch (\Throwable $throwable) {
                throw new \Exception('Form handling error!');
            }
        }
    }
}