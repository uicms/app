<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
#use Symfony\Component\Mailer\MailerInterface;
#use Symfony\Component\Mime\Email;

use Uicms\App\Service\Model;
use Uicms\App\Service\EmailSender;
use App\Service\FormContributor;

class AuthenticationController extends AbstractController
{
	
    public function index($page, Model $model, Request $request, FormContributor $contributor_form, EmailSender $email_sender): Response
    {
        if($this->get('session')->get('contributor')) {
            return $this->redirectToRoute('app_page', ['slug'=>$this->get('session')->get('home_page_slug'),  'locale'=>$this->get('session')->get('locale')]);
        }

        # Form
        $form = $contributor_form->get();
        if($contributor = $contributor_form->handle($form)) {
        	$email_sender->send(['slug'=>'registration',
        						 'to'=>$contributor->getEmail(), 
        						 'vars'=>[$contributor->getFirstname(), $contributor->getLastname()]]
        	);
        }

        # Data
        $data = [
        	'page'=>$page,
        	'form'=>$form->createView(),
        ];

		return $this->render(
		            'app/tpl/authentication/index.html.twig',
		            $data,
		        );
    }

    public function login($email='', $password='', $page, Model $model, UserPasswordEncoderInterface $passwd_encoder)
    {
        if($email && $password) {
            if($contributor = $model->get('Contributor')->mode('admin')->getRow(['findby'=>['email'=>$email]])) {
                if($contributor->getStatus() == 'validated' && $contributor->getContributorType()->getSlug() != 'guest') {
                
                    $password = $passwd_encoder->encodePassword($contributor, $password);
                    if($password == $contributor->getPassword()) {
                        $this->get('session')->set('contributor', $contributor);
                        
                        if(!$contributor->getHasAgreed()) {
                            return $this->redirectToRoute('app_page_action', array('slug'=>$page->getSlug(), 'action'=>'agreement', 'locale'=>$this->get('session')->get('locale')));
                        } else {
                            return $this->redirectToRoute('app_page', array('slug'=>$this->get('session')->get('home_page_slug'), 'locale'=>$this->get('session')->get('locale')));
                        }                    
                    } else {
                        $this->addFlash('error', 'login_incorrect_password');
                    }
                
                } else {
                    $this->addFlash('error', 'login_user_not_allowed');
                }
            } else {
                $this->addFlash('error', 'login_incorrect_email');
            }
        } else {
            $this->addFlash('error', 'login_param_missing');
        }

        return $this->redirectToRoute('app_page_action', array('slug'=>$page->getSlug(), 'action'=>'index', 'locale'=>$this->get('session')->get('locale')));
    }
	
    public function agreement($page, Model $model)
    {
        if(!$this->get('session')->get('contributor')) {
            return $this->redirectToRoute('app_page_action', array('slug'=>$page->getSlug(), 'action'=>'index', 'locale'=>$this->get('session')->get('locale')));
        }

        # Render
        return $this->render(
            'app/tpl/authentication/agreement.html.twig',
            [
                'page'=>$page,
                'block'=>$model->get('Block')->getRow(['findby'=>['slug'=>'agreement']]),
            ]
        );
    }

    public function agree($agreement=0, $page, Model $model, Request $request)
    {
        if(!$this->get('session')->get('contributor')) {
            return $this->redirectToRoute('app_page_action', array('slug'=>$page->getSlug(), 'action'=>'index', 'locale'=>$this->get('session')->get('locale')));
        }

        if($agreement) {
            $contributor = $model->get('Contributor')->mode('admin')->getRowById($this->get('session')->get('contributor')->getId());
            $contributor->setHasAgreed(true);
            $model->get('Contributor')->mode('admin')->persist($contributor);

            return $this->redirectToRoute('app_page', array('slug'=>$this->get('session')->get('home_page_slug'), 'locale'=>$this->get('session')->get('locale')));
        } else {
            return $this->redirectToRoute('app_page_action', array('slug'=>$page->getSlug(), 'action'=>'index', 'locale'=>$this->get('session')->get('locale')));
        }
    }

    public function logout($page)
    {
        $this->get('session')->set('contributor', null);
        return $this->redirectToRoute('app_page_action', array('slug'=>$page->getSlug(), 'action'=>'index', 'locale'=>$this->get('session')->get('locale')));
    }
	
    public function password($page, Model $model)
    {
		return $this->render(
            'app/tpl/authentication/password.html.twig',
            ['page'=>$page]
        );
    }
    
    public function retrievepassword($email='', $page, Model $model, UserPasswordEncoderInterface $passwd_encoder, EmailSender $email_sender)
    {
    	$params = $this->get('session')->get('params');

        if($email && isset($params['sender_email_address']) && ($contributor = $model->get('Contributor')->mode('admin')->getRow(array('findby'=>array('email'=>$email))))) {
        	
        	# Update passwd
            $new_password = rtrim(strtr(base64_encode(random_bytes(8)), '+/', '-_'), '=');
            $encoded_password = $passwd_encoder->encodePassword($contributor, $new_password);
            $contributor->setPlainPassword($new_password);
            $contributor->setPassword($encoded_password);
            $model->get('Contributor')->mode('admin')->persist($contributor);
            
            # Send passwd by email
            $email_sender->send(['slug'=>'retrieve_password','to'=>$contributor->getEmail(), 'vars'=>[$new_password]]);

            $this->addFlash('success', 'retrieve_passwd_sent');
        } else {
            $this->addFlash('error', 'retrieve_passwd_error');
        }
        
        return $this->redirectToRoute('app_page_action', array('slug'=>$page->getSlug(), 'action'=>'index', 'locale'=>$this->get('session')->get('locale')));
    }
}