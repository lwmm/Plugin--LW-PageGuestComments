<?php

/**
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_page_guest_comments
 */

namespace LwPageGuestComments\Controller;

class PageGuestCommentsController
{

    protected $response;
    protected $request;
    protected $admin;
    protected $config;

    public function __construct($config, $response, $request, $admin)
    {
        $this->response = $response;
        $this->request = $request;
        $this->admin = $admin;
        $this->config = $config;
    }

    public function execute()
    {
        $commandHandler = new \LwPageGuestComments\Model\DataHandler\CommandHandler($this->response->getDbObject());
        $queryHandler = new \LwPageGuestComments\Model\DataHandler\QueryHandler($this->response->getDbObject());

        $array["admin"] = $this->admin;
        $array["comments"] = $queryHandler->getALlComments($this->request->getIndex());
        $array["lang"] = $this->response->getParameterByKey("lang");
        $array["uniqueId"] = uniqid();

        switch ($this->request->getAlnum("cmd")) {
            case "addComment":
                $array["post"] = array("email" => $this->request->getRaw("email"), "comment" => $this->request->getRaw("comment"), "firstname" => $this->request->getRaw("firstname"), "lastname" => $this->request->getRaw("lastname"), "uniqId" => $this->request->getAlnum("uniqId"));

                $validation = new \LwPageGuestComments\Services\Validation\FormValidate();
                $validation->setValues($array["post"]);
                $validation->validate();

                $errors = $validation->getErrors();

                if (isset($errors["firstname"][99]) || isset($errors["lastname"][99]) || isset($errors["uniqId"][99])) {
                    unset($_SESSION[$this->request->getAlnum("uniqId")]);
                    \LwPageGuestComments\Services\Page::reload(\LwPageGuestComments\Services\Page::getBaseUrl($this->request->getIndex()));
                }

                if (!empty($errors)) {
                    $array["errors"] = $errors;
                }
                else {
                    $array["preview"] = true;
                }
                break;

            case "save":
                $array["post"] = array("email" => $this->request->getRaw("email"), "comment" => $this->request->getRaw("comment"), "index" => $this->request->getIndex());
                $commandHandler->addComment($array["post"]);
                
                $mailConfig = $this->getMailConfig();
                $mailer = new \LwMailer($mailConfig, $this->config);
                
                $array["adminTo"] = $mailConfig["from"];
                $mails = $this->prepareEmails($array);
                
                foreach($mails as $mail){
                    $mailer->sendMail($mail);
                }
                
                unset($_SESSION[$this->request->getAlnum("uniqId")]);
                \LwPageGuestComments\Services\Page::reload(\LwPageGuestComments\Services\Page::getBaseUrl($this->request->getIndex()));
                break;

            case "delete":
                $commandHandler->deleteComment($this->request->getInt("cid"));
                \LwPageGuestComments\Services\Page::reload(\LwPageGuestComments\Services\Page::getBaseUrl($this->request->getIndex()));
                break;
        }

        $view = new \LwPageGuestComments\Views\Container($this->config, $this->request->getIndex());
        $this->response->setOutputByKey("LwPageGuestComments", $view->render($array));
    }

    private function getMailConfig()
    {
        return $this->config["mailConfig"];
    }
    
    private function prepareEmails($array)
    {       
        if($array["lang"] == "de"){
            $subject = "Neuer Seitenkommentar";
        }else{
            $subject = "New page comment";
        }

        $viewAdminMail = new \LwPageGuestComments\Views\AdminMail($this->config);
        $adminMail = array(
            "toMail"    => $array["adminTo"],
            "subject"   => $subject,
            "message"   => $viewAdminMail->render($array["lang"])
            );

        $viewUserMail = new \LwPageGuestComments\Views\UserMail($this->config);
        $userMail = array(
            "toMail"    => $array["post"]["email"],
            "subject"   => $subject,
            "message"   => $viewUserMail->render($array["lang"])
            );
        
        return array("admin" => $adminMail, "user" => $userMail);
    }

}
