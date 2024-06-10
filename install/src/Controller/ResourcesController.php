<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Uicms\App\Service\Model;
use Uicms\App\Service\Filters;
use Uicms\App\Service\Viewnav;

use App\Service\FormResource;

class ResourcesController extends AbstractController
{
    public function index($page, Model $model, Request $request, Filters $filters, $search='')
    {
        # Init
        $limit = $this->get('session')->get('params')['results_limit'];
        $data = ['page'=>$page, 'limit'=>$limit];
        $ui_config = $this->getParameter('ui_config');
        
        # Filters
        $params = ['findby'=>[]];
        $data['filters'] = $filters->getFilters('Resource', $ui_config['filters'], $params);
        $params = array_merge($params, $data['filters']['params']);

        # Results
        $data['params'] = array_merge($params, $data['filters']['params'], ['offset'=>0, 'limit'=>$limit]);
        $data['total'] = $model->get('Resource')->count($data['params']);
        $data['results'] = $model->get('Resource')->getAll($data['params']);

        return $this->render(
            'app/tpl/resources/index.html.twig',
            $data
        );
    }

    public function results($page, Model $model, Request $request, Filters $filters, $of=0)
    {
        # Init
        $limit = $this->get('session')->get('params')['results_limit'];
        $data = ['page'=>$page, 'limit'=>$limit, 'offset'=>$of];
        $ui_config = $this->getParameter('ui_config');
        
         # Results
        $data['filters'] = $filters->getFilters('Resource', $ui_config['filters']);
        $params = ['offset'=>$of, 'limit'=>$limit, 'is_allowed'=>true, 'findby'=>[]];
        $data['params'] = array_merge($params, $data['filters']['params']);
        $data['results'] = $model->get('Resource')->getAll($data['params']);
        
        # Render
        return $this->render('app/tpl/resources/results.html.twig', $data);
    }
    
    public function view($page, Model $model, Request $request, Filters $filters, Viewnav $viewnav, $search='', $id=0)
    {
        $data = ['page'=>$page];
        $ui_config = $this->getParameter('ui_config');

        if((int)$id) {
            $data['resource'] = $model->get('Resource')->getRowById((int)$id);

            # Filters & params
            $data['filters'] = $filters->getFilters('Resource', $ui_config['filters']);
            $params = ['findby'=>[]];
            $data['params'] = array_merge($params, $data['filters']['params']);

            # Nav
            $data['view_nav'] = $viewnav->get('App\Entity\Resource', $data['resource'], $data['params']);
        }
        
        return $this->render(
            'app/tpl/resources/view.html.twig',
            $data
        );
    }

    public function form($page, Model $model, Request $request, FormResource $resource_form, $id=0): Response
    {
        # Form
        $form = $resource_form->get($id);
        if($resource = $resource_form->handle($form)) {
            return $this->redirectToRoute('app_page_action', ['slug'=>$page->getSlug(), 'action'=>'form', 'locale'=>$this->get('session')->get('locale'), 'id'=>$resource->getId()]);
        }

        return $this->render(
            'app/tpl/resources/form.html.twig',
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