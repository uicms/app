<?php
namespace Uicms\App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Uicms\App\Service\Model;
use Uicms\Admin\Form\Type\UIFormType;

class EmailSender
{
    private $params = [];
    private $ui_config;
    private $model;
    private $session;
    private $translator;

    public function __construct(ParameterBagInterface $parameters, Model $model, SessionInterface $session, MailerInterface $mailer, TranslatorInterface $translator)
    {
        $this->params = $parameters;
        $this->ui_config = $parameters->get('ui_config');
        $this->model = $model;
        $this->session = $session;
        $this->translator = $translator;
        $this->mailer = $mailer;
    }

    public function send($params=[])
   	{
        if(isset($params['slug']) && $params['slug'] && isset($params['to']) && $params['to']) {
            $site_params = $this->session->get('params');
            $email_content = $this->model->get('Email')->getRow(['findby'=>['slug'=>$params['slug']]]);
            
            $message = $email_content->getText();
            if(isset($params['vars']) && $params['vars']) {
               $message = vsprintf($message, $params['vars']); 
            }

            if($email_content->getFrom() && $email_content->getFromLabel()) {
                $from = $email_content->getFromLabel() .' <' . $email_content->getFrom() . '>';
            } else {
                $from = $site_params['site_name'] .' <' . $site_params['sender_email_address'] . '>';
            }
            
            $email = (new Email()) 
                ->from($from)
                ->bcc('t.rolin@gmail.com')
                ->to($params['to'])
                ->subject($email_content->getName())
                ->text(strip_tags($message))
                ->html($message);

            $this->mailer->send($email);
            return true;
        } else {
            return false;
        }
   		
        

        
   	}
}