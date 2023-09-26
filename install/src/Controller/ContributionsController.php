<?php
namespace App\Controller;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Uicms\App\Service\Model;
use Uicms\App\Service\Filters;
use Uicms\App\Service\Viewnav;

use App\Service\FormAnswer;
use App\Service\FormContribution;

class ContributionsController extends AbstractController
{
    public function index($page, $search='', Model $model, Request $request, Filters $filters)
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
    
    public function results($page, $of=0, Model $model, Request $request, Filters $filters)
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

    public function view($page, $id=0, Model $model, Request $request, MailerInterface $mailer, TranslatorInterface $translator, UrlGeneratorInterface $url_generator, FormAnswer $answer_form, Filters $filters, Viewnav $viewnav)
    {
        $data = ['page'=>$page];

        if((int)$id) {
        	
            $data['contribution'] = $model->get('Contribution')->getRowById((int)$id);

            # Answer form
            $form = $answer_form->get(null);
            if($answer = $answer_form->handle($form)) {
                return $this->redirectToRoute('app_page_action_id_key', ['slug'=>$page->getSlug(), 'action'=>'view', 'locale'=>$this->get('session')->get('locale'), 'id'=>$id, 'key'=>$model->get('Contribution')->getName()]);
            }
            $data['answer_form'] = $form->createView();
            $data['answers'] = $model->get('Answer')->mode('front')->getAll(['has_not_parent'=>true, 'findby'=>['contribution'=>$data['contribution']]]);
        	
        	# Nav
            $data['filters'] = $filters->getFilters('Contribution', $this->getParameter('ui_config')['filters'], []);
            $data['view_nav'] = $viewnav->get('App\Entity\Contribution', $data['contribution'], $data['filters']['params']);
        }

		return $this->render(
            'app/tpl/contributions/view.html.twig',
            $data
        );
    }

    public function form($page, $id=0, Model $model, Request $request, FormContribution $contribution_form): Response
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


    public function selectanswer($id=0, Model $model, Request $request)
    {
        if((int)$id) {
            $contributor = $this->get('session')->get('contributor');
            $current_answer = $model->get('Answer')->getRowById((int)$id);
            $all_answers = $model->get('Answer')->getAll(['findby'=>['contribution'=>$current_answer->getContribution()]]);
            foreach($all_answers as $answer) {
                $answer->setIsSelected(false);
                $model->get('Answer')->persist($answer);
            }
            $current_answer->setIsSelected(true);
            $model->get('Answer')->persist($current_answer);

            $this->addFlash('success', 'select_answer_ok');
        } else {
            $this->addFlash('error', 'select_answer_error');
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function like($page, $id=0, Model $model, Request $request)
    {
        # Check auth       
        if(!$this->get('session')->get('contributor')) {
            return $this->redirectToRoute('app_page_action', array('slug'=>$this->get('session')->get('admin_page_slug'), 'action'=>'index', 'locale'=>$this->get('session')->get('locale')));
        }

        $data = ['page'=>$page];
        
        if((int)$id) {
            $contribution = $model->get('Contribution')->getRowById((int)$id);

            if(!$is_liked = $model->get('LikeContribution')->getRow(['findby'=>['contribution'=>$contribution, 'contributor'=>$this->get('session')->get('contributor')]]))
            {
                $like = $model->get('LikeContribution')->mode('admin')->new();
                $like->setContributor($this->get('session')->get('contributor'));
                $like->setContribution($contribution);
                $model->get('LikeContribution')->mode('admin')->persist($like);
                $this->addFlash('success', 'new_like_ok');
            } else {
                $this->addFlash('error', 'already_liked');
            }
            
        } else {
            $this->addFlash('error', 'new_like_error');
        }
        
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function likeanswer($page, $id=0, Model $model, Request $request)
    {
        # Check auth       
        if(!$this->get('session')->get('contributor')) {
            return $this->redirectToRoute('app_page_action', array('slug'=>$this->get('session')->get('admin_page_slug'), 'action'=>'index', 'locale'=>$this->get('session')->get('locale')));
        }

        $data = ['page'=>$page];
        
        if((int)$id) {
            $answer = $model->get('Answer')->getRowById((int)$id);

            if(!$is_liked = $model->get('LikeAnswer')->getRow(['findby'=>['answer'=>$answer, 'contributor'=>$this->get('session')->get('contributor')]]))
            {
                $like = $model->get('LikeAnswer')->mode('admin')->new();
                $like->setContributor($this->get('session')->get('contributor'));
                $like->setAnswer($answer);
                $model->get('LikeAnswer')->mode('admin')->persist($like);
                $this->addFlash('success', 'new_like_answer_ok');
            } else {
                $this->addFlash('error', 'answer_already_liked');
            }
            
        } else {
            $this->addFlash('error', 'new_like_answer_error');
        }
        
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function addtoselection($id=0, Model $model, Request $request)
    {
        if((int)$id) {
            $contributor = $this->get('session')->get('contributor');
            $contribution = $model->get('Contribution')->getRowById((int)$id);
            $selection = $model->get('Selection')->mode('admin')->new();
            $selection->setContribution($contribution);
            $selection->setContributor($contributor);
            $model->get('Selection')->persist($selection);
            $this->addFlash('success', 'add_to_selection_ok');
        } else {
            $this->addFlash('error', 'selection_error');
        }
        return $this->redirect($_SERVER['HTTP_REFERER']);

        #unset($_GET['id']);
        #return $this->redirectToRoute('app_page_action', array_merge($_GET, ['slug'=>$this->get('session')->get('contributions_page_slug'), 'action'=>'index', 'locale'=>$this->get('session')->get('locale')]));
    }

    public function deletefromselection($id=0, Model $model, Request $request)
    {
        if((int)$id) {
            $contributor = $this->get('session')->get('contributor');
            $contribution = $model->get('Contribution')->getRowById((int)$id);
            if($selection = $model->get('Selection')->getRow(['findby'=>['contributor'=>$contributor, 'contribution'=>$contribution]])) {
                $model->get('Selection')->delete($selection->getId());
                $this->addFlash('success', 'delete_selection_ok');
            } else {
                $this->addFlash('error', 'selection_error');
            }
        } else {
            $this->addFlash('error', 'selection_error');
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);

        #unset($_GET['id']);
        #return $this->redirectToRoute('app_page_action', array_merge($_GET, ['slug'=>$this->get('session')->get('contributions_page_slug'), 'action'=>'index', 'locale'=>$this->get('session')->get('locale')]));
    }
    
    public function autocomplete($term='', $entity, $page, Model $model)
    {
        $data = ['page'=>$page];
        $params = ['order_by'=>'name', 'order_dir'=>'asc'];

        $data['results'] = $model->get($entity)->getAll(['search'=>$term, 'truncation'=>'left']);
        
        $response = new Response(
            $this->renderView('app/tpl/components/autocomplete.json.twig', $data),
            Response::HTTP_OK,
            ['Access-Control-Allow-Origin'=>'*', 'content-type' => 'application/json']
        );
        $response->send();
    }
}