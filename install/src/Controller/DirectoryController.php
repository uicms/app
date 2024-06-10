<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Uicms\App\Service\Model;
use Uicms\App\Service\Filters;
use Uicms\App\Service\Viewnav;

use App\Service\FormContributor;

class DirectoryController extends AbstractController
{
    public function index($page, Model $model, Request $request, Filters $filters, $search='')
    {
        $params = ['limit' => $this->get('session')->get('params')['results_limit']];
    	$filters = $filters->getFilters('Contributor', $this->getParameter('ui_config')['filters'], $params);
        
        $data = [
        	'page' => $page, 
        	'params'=> array_merge($params, $filters['params']), 
        	'filters' => $filters
        ];

        $data['total'] = $model->get('Contributor')->count($data['params']);
        $data['results'] = $model->get('Contributor')->getAll($data['params']);

		return $this->render(
		            'app/tpl/directory/index.html.twig',
		            $data
		        );
    }
    
    public function results($page, Model $model, Request $request, Filters $filters, $of=0)
    {
        $params = ['limit' => $this->get('session')->get('params')['results_limit'], 'offset' => $of];
        $filters = $filters->getFilters('Contributor', $this->getParameter('ui_config')['filters'], $params);
        
        $data = [
        	'page' => $page,
        	'params'=> array_merge($params, $filters['params']),
        	'filters' => $filters,
        ];

        $data['results'] = $model->get('Contributor')->getAll($data['params']);
        
        return $this->render(
        	'app/tpl/directory/results.html.twig', 
        	$data
        );
    }
    
    public function view($page, Model $model, Request $request, $id=0)
    {
		return $this->render(
            'app/tpl/directory/view.html.twig',
            [
                'page'=>$page,
                'contributor'=> $id ? $model->get('Contributor')->getRowById((int)$id) : null,
            ]
        );
    }
    
    public function form($page, Model $model, Request $request, FormContributor $contributor_form, $id=0)
    {
    	# Form
        $form = $contributor_form->get($id);
        if($contributor = $contributor_form->handle($form)) {
            return $this->redirectToRoute('app_page_action_id_key', ['slug'=>$page->getSlug(), 'action'=>'form', 'locale'=>$this->get('session')->get('locale'), 'id'=>$contributor->getId(), 'key'=>$contribution->getFirstame() . '-' . $contribution->getLastame()]);
        }

        return $this->render(
            'app/tpl/directory/form.html.twig',
            [
                'page'=>$page,
                'form'=>$form->createView(),
            ]
        );
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