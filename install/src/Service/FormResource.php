<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

use Uicms\App\Service\Model;
use Uicms\App\Service\Form;
use Uicms\Admin\Form\Type\UIFormType;

class FormResource
{
    private $entity = 'Resource';
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
            $medias = $this->model->get('Media')->getAll(['linked_to'=>$this->entity, 'linked_to_id'=>$row->getId()]);
            $keywords = $this->model->get('Keyword')->getAll(['linked_to'=>$this->entity, 'linked_to_id'=>$row->getId()]);
        } else {
            $row = $this->model->get($this->entity)->mode('admin')->new();
            $topics = [];
            $medias = [];
            $keywords = [];
        }
        
        # Keywords
        $form_config['fields']['keywords'] = [
            'name'=>'keywords',
            'type'=>'CollectionType',
            'namespace'=> 'Symfony\Component\Form\Extension\Core\Type',
            'public'=> true,
            'public_required'=> false,
            'options'=>[
                'entry_type' => UIFormType::class,
                'entry_options' => [
                    'label' => $this->translator->trans('fld_keywords_keyword', [], 'admin'), 
                    'attr'=>[
                        'class'=>'collection_entry', 
                        'required'=>true,
                    ] , 
                    'translator'=>$this->translator,
                    'ui_config' => $this->ui_config,
                    'form_config' => $this->ui_config['entity']['App\Entity\Keyword']['form'],
                    'data_class' => 'App\Entity\Keyword',
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => false,
                'data'=> $keywords,
                'label' => $this->translator->trans('fld_keywords', [], 'admin'),
                'mapped'=>false,
                'attr'=>['prototype'=>$this->form->getPrototype('keywords', 'App\Entity\Keyword'), 'class'=>'collection_type'],
            ],
        ];
        
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

        # Medias
        $form_config['fields']['medias'] = [
            'name'=>'medias',
            'type'=>'CollectionType',
            'namespace'=> 'Symfony\Component\Form\Extension\Core\Type',
            'public'=> true,
            'public_required'=> false,
            'options'=>[
                'entry_type' => UIFormType::class,
                'entry_options' => [
                    'label' => $this->translator->trans('fld_medias_media', [], 'admin'), 
                    'attr'=>['class'=> 'form_collection_entry', 'required'=>true] , 
                    'translator'=>$this->translator,
                    'ui_config' => $this->ui_config,
                    'form_config' => $this->ui_config['entity']['App\Entity\Media']['form'],
                    'data_class' => 'App\Entity\Media',
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => false,
                'data'=> $medias,
                'label' => $this->translator->trans('fld_medias', [], 'admin'),
                'mapped'=>false,
                'attr'=>['prototype'=>$this->form->getPrototype('medias', 'App\Entity\Media'), 'class'=>'collection_type'],
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
            
            # Row
            $row = $form->getData();
            
            # Contributor
            $row->setContributor($this->session->get('contributor'));

            #try {
                $message = $row->getId() ? 'resource_updated' : 'resource_inserted';
                
                $this->model->setFlushMode('manual');
                
                # Persist row
                $this->model->get($this->entity)->mode('admin')->persist($row);
                
                # Medias
                if($medias = $form->get('medias')->getData()) {
                    foreach($medias as $i=>$media) {
                        $this->model->get('Media')->mode('admin')->persist($media);
                    }
                }
                
                $this->em->flush();
                
                # Unlink
                $this->model->get($this->entity)->mode('admin')->unlink([$row->getId()], 'App\Entity\Topic');
                $this->model->get($this->entity)->mode('admin')->unlink([$row->getId()], 'App\Entity\Media');
                $this->model->get($this->entity)->mode('admin')->unlink([$row->getId()], 'App\Entity\Keyword');
                
                # Link topics
                if($topics = $form->get('topics')->getData()) {
                    $this->model->get($this->entity)->mode('admin')->link([$row->getId()], 'App\Entity\Topic', $topics);
                }
                
                # keywords
                if($keywords = $form->get('keywords')->getData()) {
                    foreach($keywords as $keyword) {
                        if(!$keyword->getId()) {
                            foreach($keyword->getTranslations() as $translation) {
                                if($exists = $this->model->get('Keyword')->getRow(['findby'=>['name'=>$translation->getName()]])) {
                                    $keyword = $exists;
                                } else {
                                    $this->model->get('Keyword')->mode('admin')->persist($keyword);
                                }
                            }
                        }
                        $this->em->flush();
                        $this->model->get($this->entity)->mode('admin')->link([$row->getId()], 'App\Entity\Keyword', [$keyword->getId()]);
                    }
                }
                
                # Link Medias
                foreach($medias as $i=>$media) {
                    $this->model->get($this->entity)->mode('admin')->link([$row->getId()], 'App\Entity\Media', [$media->getId()]);
                }
                
                $this->em->flush();
                
                $this->flash->add('success', $message);
                return $row;
                #} catch (\Throwable $throwable) {
            #    throw new \Exception('Form handling error!');
            #}
        } else {
            return false;
        }
    }
}