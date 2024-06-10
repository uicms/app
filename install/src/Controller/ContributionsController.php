<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Uicms\App\Service\Model;
use Uicms\App\Service\Filters;
use Uicms\App\Service\Viewnav;

use App\Service\FormAnswer;
use App\Service\FormContribution;

class ContributionsController extends AbstractController
{
    public function index($page, Model $model, Request $request, Filters $filters, $search='')
    {
        $params = ['limit' => $this->get('session')->get('params')['results_limit']];
    	$filters = $filters->getFilters('Contribution', $this->getParameter('ui_config')['filters'], $params);
        
        $data = [
        	'page' => $page, 
        	'params'=> array_merge($params, $filters['params']), 
        	'filters' => $filters
        ];

        $data['total'] = $model->get('Contribution')->count($data['params']);
        $data['results'] = $model->get('Contribution')->getAll($data['params']);

		return $this->render(
		            'app/tpl/contributions/index.html.twig',
		            $data
		        );
    }
    
    public function results($page, Model $model, Request $request, Filters $filters, $of=0)
    {
        $params = ['limit' => $this->get('session')->get('params')['results_limit'], 'offset' => $of];
        $filters = $filters->getFilters('Contribution', $this->getParameter('ui_config')['filters'], $params);
        
        $data = [
        	'page' => $page,
        	'params'=> array_merge($params, $filters['params']),
        	'filters' => $filters,
        ];

        $data['results'] = $model->get('Contribution')->getAll($data['params']);
        
        return $this->render(
        	'app/tpl/contributions/results.html.twig', 
        	$data
        );
    }

    public function view($page, Model $model, Request $request, FormAnswer $answer_form, Filters $filters, Viewnav $viewnav, $id=0)
    {
        $data = ['page'=>$page];

        if((int)$id) {
        	
            $data['contribution'] = $model->get('Contribution')->getRowById((int)$id);

            # Answer form
            $form = $answer_form->get(0, null);
            if($answer = $answer_form->handle($form)) {
                return $this->redirectToRoute('app_page_action_id_key', ['slug'=>$page->getSlug(), 'action'=>'view', 'locale'=>$this->get('session')->get('locale'), 'id'=>$id, 'key'=>$model->get('Contribution')->getName()]);
            }
            $data['answer_form'] = $form->createView();
            $data['answers'] = $model->get('Answer')->mode('front')->getAll(['has_not_parent'=>true, 'findby'=>['contribution'=>$data['contribution']]]);
            foreach($data['answers'] as $i=>$answer) {
                if($this->get('session')->get('contributor')->getId() == $data['contribution']->getContributor()->getId()) {
                    $answer->form = $answer_form->get(0, $answer)->createView();
                } else {
                    $answer->form = null;
                }
                $answer->children = $model->get('Answer')->mode('front')->getAll(['findby'=>['parent_answer'=>$answer]]);
            }
        	
        	# Nav
            $data['filters'] = $filters->getFilters('Contribution', $this->getParameter('ui_config')['filters'], []);
            $data['view_nav'] = $viewnav->get('App\Entity\Contribution', $data['contribution'], $data['filters']['params']);
        }

		return $this->render(
            'app/tpl/contributions/view.html.twig',
            $data
        );
    }

    public function form($page, Model $model, Request $request, FormContribution $contribution_form, $id=0): Response
    {
    	# Form
        $form = $contribution_form->get($id);
        if($contribution = $contribution_form->handle($form)) {
            return $this->redirectToRoute('app_page_action_id_key', ['slug'=>$page->getSlug(), 'action'=>'form', 'locale'=>$this->get('session')->get('locale'), 'id'=>$contribution->getId(), 'key'=>$contribution->getName()]);
        }

        return $this->render(
            'app/tpl/contributions/form.html.twig',
            [
                'page'=>$page,
                'form'=>$form->createView(),
            ]
        );
    }

    public function like($page, Model $model, Request $request, $id=0)
    {
        $data = ['page'=>$page];
        
        if((int)$id) {
            $contribution = $model->get('Contribution')->getRowById((int)$id);

            if(!$is_liked = $model->get('Selection')->getRow(['findby'=>['type'=>'like', 'contribution'=>$contribution->getId(), 'contributor'=>$this->get('session')->get('contributor')]]))
            {
                $like = $model->get('Selection')->mode('admin')->new();
                $like->setContributor($this->get('session')->get('contributor'));
                $like->setContribution($contribution);
                $like->setType('like');
                $model->get('Selection')->mode('admin')->persist($like);
                $this->addFlash('success', 'contribution_like_ok');
            } else {
                $this->addFlash('error', 'contribution_like_already');
            }
            
        } else {
            $this->addFlash('error', 'contribution_like_error');
        }
        
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function unlike($page, Model $model, Request $request, $id=0)
    {
        if((int)$id) {
            if($is_liked = $model->get('Selection')->getRow(['findby'=>['type'=>'like', 'contribution'=>$id, 'contributor'=>$this->get('session')->get('contributor')]]))
            {
                $model->get('Selection')->delete($is_liked->getId());
                $this->addFlash('success', 'contribution_unlike_ok');
            } else {
                $this->addFlash('error', 'contribution_unlike_already');
            }
        } else {
            $this->addFlash('error', 'contribution_unlike_error');
        }
        
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function addtoselection(Model $model, Request $request, $id=0)
    {
        if((int)$id) {
            $selection = $model->get('Selection')->mode('admin')->new();
            $selection->setContribution( $model->get('Contribution')->getRowById($id));
            $selection->setType('selection');
            $selection->setContributor($this->get('session')->get('contributor'));
            $model->get('Selection')->persist($selection);
            $this->addFlash('success', 'contribution_add_selection_ok');
        } else {
            $this->addFlash('error', 'contribution_add_selection_error');
        }
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function removefromselection(Model $model, Request $request, $id=0)
    {
        if((int)$id) {
            if($selection = $model->get('Selection')->getRow(['findby'=>['type'=>'selection', 'contribution'=>$id, 'contributor'=>$this->get('session')->get('contributor')]])) {
                $model->get('Selection')->delete($selection->getId());
                $this->addFlash('success', 'contribution_remove_selection_ok');
            } else {
                $this->addFlash('error', 'contribution_remove_selection_error');
            }
        } else {
            $this->addFlash('error', 'contribution_remove_selection_error');
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function likeanswer($page, Model $model, Request $request, $id=0)
    {
        if((int)$id) {
            if(!$is_liked = $model->get('Selection')->getRow(['findby'=>['type'=>'like', 'answer'=>$id, 'contributor'=>$this->get('session')->get('contributor')]]))
            {
                $like = $model->get('Selection')->mode('admin')->new();
                $like->setContributor($this->get('session')->get('contributor'));
                $like->setAnswer($model->get('Answer')->getRowByid($id));
                $like->setType('like');
                $model->get('Selection')->mode('admin')->persist($like);
                $this->addFlash('success', 'answer_like_ok');
            } else {
                $this->addFlash('error', 'answer_like_already');
            }
        } else {
            $this->addFlash('error', 'answer_like_error');
        }
        
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function unlikeanswer($page, Model $model, Request $request, $id=0)
    {
        if((int)$id) {
            if($is_liked = $model->get('Selection')->getRow(['findby'=>['type'=>'like', 'answer'=>$id, 'contributor'=>$this->get('session')->get('contributor')]]))
            {
                $model->get('Selection')->delete($is_liked->getId());
                $this->addFlash('success', 'answer_unlike_ok');
            } else {
                $this->addFlash('error', 'answer_unlike_already');
            }
        } else {
            $this->addFlash('error', 'answer_unlike_error');
        }
        
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function selectanswer(Model $model, Request $request, $id=0)
    {
        if((int)$id) {
            $current_answer = $model->get('Answer')->getRowById((int)$id);
            $all_answers = $model->get('Answer')->getAll(['findby'=>['contribution'=>$current_answer->getContribution()]]);
            foreach($all_answers as $answer) {
                $answer->setIsSelected(false);
                $model->get('Answer')->persist($answer);
            }
            $current_answer->setIsSelected(true);
            $model->get('Answer')->persist($current_answer);
            $this->addFlash('success', 'answer_select_ok');
        } else {
            $this->addFlash('error', 'answer_select_error');
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function autocomplete($term, $entity, $field_name, $page, Model $model)
    {
        $data = ['page'=>$page];
        $params = ['order_by'=>'name', 'order_dir'=>'asc'];

        $data['results'] = $model->get($entity)->getAll(['search'=>[$term=>[$field_name]], 'truncation'=>'left']);
        
        $response = new Response(
            $this->renderView('app/tpl/components/autocomplete.json.twig', $data),
            Response::HTTP_OK,
            ['Access-Control-Allow-Origin'=>'*', 'content-type' => 'application/json']
        );
        $response->send();
    }
}