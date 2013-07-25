<?php

/**
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_page_guest_comments
 */

namespace LwPageGuestComments\Views;

class AdminMail
{
    protected $config;
    
    public function __construct($config)
    {
        $this->config = $config;
    }


    /**
     * The html template will be rendered and return.
     * 
     * @param array $data
     * @param bool $admin
     * @return string
     */
    public function render($lang)
    {   
        if($lang == "de"){
            $view = new \lw_view(dirname(__FILE__) . '/Templates/Mails/de/Admin.phtml');
        }else{
            $view = new \lw_view(dirname(__FILE__) . '/Templates/Mails/en/Admin.phtml');
        }
        
        $view->url = \LwPageGuestComments\Services\Page::getUrl();
   
        return $view->render();
    }
    
}