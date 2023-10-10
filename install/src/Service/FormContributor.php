<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
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

    public function __construct(ParameterBagInterface $parameters, Model $model, RequestStack $request, SessionInterface $session, TranslatorInterface $translator, Form $form, FlashBagInterface $flash, EntityManagerInterface $entityManager)
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
        $this->em = $entityManager;
    }

    public function get($id=0)
    {
        $form_config = $this->ui_config['entity']['App\Entity\\' . $this->entity]['form'];
        
   		# Data
        if((int)$id && ($row = $this->model->get($this->entity)->getRowById($id))) {
        	$topics = $this->model->get('Topic')->getAll(['linked_to'=>$this->entity, 'linked_to_id'=>$row->getId()]);
        } else {
            $row = $this->model->get($this->entity)->mode('admin')->new();
            $topics = [];
        }

   		# Topics
        $form_config['fields']['topics'] = [
            'name'=>'topics',
            'type'=>'EntityType',
            'namespace'=> 'Symfony\Bridge\Doctrine\Form\Type',
            'public'=> true,
            'public_required'=> false,
            'options'=>[
                'class' => "App\Entity\Topic",
                'choice_label' => 'translations[fr].name',
                'expanded' => true,
                'multiple' => true,
                'data'=> $topics,
                'label' => $this->translator->trans('fld_topics', [], 'admin'),
                'mapped' => false,
            ],
        ];
        
        # Build form
       	return $this->form->get($this->entity, $row, $form_config);
    }

    public function handle($form)
    {
        # Posted data
        $form->handleRequest($this->request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $row = $form->getData();

            #try {
                $message = $row->getId() ? 'contributor_updated' : 'contributor_inserted';
                
                $this->model->setFlushMode('manual');
                
                if(!$row->getId() && ($exists = $this->model->get($this->entity)->getRow(['findby'=>['email'=>$row->getEmail()]]))) {
                    $this->flash->add('error', 'already_registered');
                    return false;
                }

                # Set default contributor type
                if(!$row->getContributorType()) {
                    $default_type = $this->model->get('ContributorType')->getRow(['findby'=>['slug'=>'member']]);
                    $row->setContributorType($default_type);
                }
                
                # Set default contributor grade
                if(!$row->getContributorGrade()) {
                    $default_grade = $this->model->get('ContributorGrade')->getRow(['findby'=>['slug'=>'default']]);
                    $row->setContributorGrade($default_grade);
                }
                
                # Parent contributor
                if($this->session->get('contributor')) {
                    $row->setParentContributor($this->session->get('contributor'));
                }
                
                # Persist
                $this->model->get($this->entity)->mode('admin')->persist($row);
                $this->em->flush();
                
                # Unlink
                $this->model->get($this->entity)->mode('admin')->unlink([$row->getId()], 'App\Entity\Topic');

                # Link topics
                if($topics = $form->get('topics')->getData()) {
                    $this->model->get($this->entity)->mode('admin')->link([$row->getId()], 'App\Entity\Topic', $topics);
                }
                $this->em->flush();
                
                $this->flash->add('success', $message);
                return $row;
                #} catch (\Throwable $throwable) {
                #throw new \Exception('Form handling error!');
                #}
        }
    }
}