<?php

/**
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_page_guest_comments
 */

namespace LwPageGuestComments\Views;

class CommentForm
{
    /**
     * The html template will be rendered and return.
     * 
     * @param array $data
     * @param bool $admin
     * @return string
     */
    public function render($post, $errors, $lang, $uniqId)
    {        
        $view = new \lw_view(dirname(__FILE__) . '/Templates/CommentForm.phtml');
        
        $view->actionUrl = \LwPageGuestComments\Services\Page::getUrl()."&cmd=addComment#preview";
        $view->baseUrl = \LwPageGuestComments\Services\Page::getUrl();
        $view->post = $post;
        $view->errors = $errors;
        $view->lang = $lang;
        $view->uniqId = $uniqId;

        return $view->render();
    }
    
}