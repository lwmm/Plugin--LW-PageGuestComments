<?php

/**
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_page_guest_comments
 */

namespace LwPageGuestComments\Views;

class Container
{
    protected $config;
    protected $pageIndex;


    public function __construct($config, $pageIndex)
    {
        $this->config = $config;
        $this->pageIndex = $pageIndex;
    }


    /**
     * The html template will be rendered and return.
     * 
     * @param array $data
     * @param bool $admin
     * @return string
     */
    public function render($array)
    {   
        $errors = false;
        if(isset($array["errors"])) {
            $errors = $array["errors"];
        }
        $post = false;
        if(isset($array["post"])) {
            $post = $array["post"];
        }
        $preview = false;
        if(isset($array["preview"])) {
            $preview = $array["preview"];
        }
        
        $commentList = new \LwPageGuestComments\Views\CommentList($this->pageIndex);
        $commentForm = new \LwPageGuestComments\Views\CommentForm($this->pageIndex);
        
        $view = new \lw_view(dirname(__FILE__) . '/Templates/Container.phtml');
        $view->jQueryMin = $this->config["url"]["media"] ."jquery/jquery.min.js";
        
        $view->commentList = $commentList->render($array["comments"], $post, $preview, $array["admin"], $array["lang"]);
        $view->commentForm = $commentForm->render($post, $errors, $array["lang"], $array["uniqId"]);
        $view->preview = $preview;

        $_SESSION[$array["uniqId"]] = date("YmdHis");
        return $view->render();
    }
    
}