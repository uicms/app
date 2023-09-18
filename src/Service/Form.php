<?php
namespace Uicms\App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Form\FormFactoryInterface;

use Twig\Environment;

use Uicms\App\Service\Model;
use Uicms\Admin\Form\Type\UIFormType;

class Form
{
    private $params = [];
    private $ui_config;
    private $model;
    private $session;
    private $translator;
    private $twig;
    private $clone;

    public function __construct(ParameterBagInterface $parameters, Model $model, SessionInterface $session, TranslatorInterface $translator, FormFactoryInterface $form_factory, Environment $twig)
    {
        $this->params = $parameters;
        $this->ui_config = $parameters->get('ui_config');
        $this->model = $model;
        $this->session = $session;
        $this->translator = $translator;
        $this->form_factory = $form_factory;
        $this->twig = $twig;
    }

    public function get($entity, $row=null, $form_config=[])
   	{
        if(!$form_config) {
            $form_config = $this->ui_config['entity']['App\Entity\\' . $entity]['form'];
        }

   		# Loop on fields
        $field_types = array('fields', 'translations');
        foreach($field_types as $field_type) {
            foreach($form_config[$field_type] as $field_name=>$field_config) {
                if(!isset($field_config['public']) || !$field_config['public']) {
                    unset($form_config[$field_type][$field_name]);
                }
                if(isset($field_config['public_required']) && $field_config['public_required']) {
                    $form_config[$field_type][$field_name]['options']['required'] = true;
                }
                if(isset($field_config['type']) && $field_config['type'] == 'CollectionType' && isset($field_config['options']['entry_options']['form_config'])) {
                    
                    foreach($field_types as $entry_field_type) {

                        foreach($field_config['options']['entry_options']['form_config'][$entry_field_type] as $entry_field_name=>$entry_field_config) {
                            if(!isset($entry_field_config['public']) || !$entry_field_config['public']) {
                                unset($form_config[$field_type][$field_name]['options']['entry_options']['form_config'][$entry_field_type][$entry_field_name]);
                            }
                            if(isset($entry_field_config['public_required']) && $entry_field_config['public_required']) {
                                $form_config[$field_type][$field_name]['options']['entry_options']['form_config'][$entry_field_type][$entry_field_name]['options']['required'] = true;
                            }
                        }
                    }
                }
            }
        }

        # Build form
        $form = $this->form_factory->create(UIFormType::class, $row, [
                                                            'ui_config'=>$this->ui_config,
                                            				'form_config'=>$form_config,
                                            				'translator'=>$this->translator,
                                                            'data_class'=>'App\Entity\\' . $entity
                                                        ]);
       	return $form ;
   	}

    public function clone($name, $data)
    {
        $results = [];
        if(is_array($data)) {
            foreach($data as $i=>$row) {
                $results[$row->getId()] = clone $row;
            }
        } else if (is_object($data)) {
            $results[$data->getId()] = clone $data;
        }
        $this->clone[$name] = $results;
    }

    public function getClone($name)
    {
        return isset($this->clone[$name]) ? $this->clone[$name] : [];
    }

    public function getPrototype($collection_name, $entity)
    {
        $prototype_config = $this->ui_config['entity'][$entity]['form'];
        $prototype_form = $this->get('media', null, $prototype_config);
        $view = $prototype_form->createView();
        $view->offsetUnset('_token');
        $prototype_html = $this->twig->render('app/tpl/components/prototype.html.twig', [
            'form' => $view,
        ]);

        $prototype_html = str_replace(
            ['id="ui_form', 'for="ui_form', 'name="ui_form', 'id="form_field', 'href="#ui_form_'], 
            ['id="ui_form_' . $collection_name . '___name__', 'for="ui_form_' . $collection_name . '___name__', 'name="ui_form['. $collection_name . '][__name__]', 'id="form_field_' . $collection_name . '___name__', 'href="#ui_form_' . $collection_name . '___name___'],
            $prototype_html
        );
        return $prototype_html;
    }
}