<?php

/**
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_page_guest_comments
 */

namespace LwPageGuestComments\Views;

class CommentList
{

    /**
     * The html template will be rendered and return.
     * 
     * @param array $data
     * @param bool $admin
     * @return string
     */
    public function render($comments, $post, $preview, $admin, $lang)
    {        
        $view = new \lw_view(dirname(__FILE__) . '/Templates/CommentList.phtml');
        $view->saveUrl = \LwPageGuestComments\Services\Page::getUrl()."&cmd=save";
        $view->deleteUrl = \LwPageGuestComments\Services\Page::getUrl()."&cmd=delete";
        
        $view->lang = $lang;
        $view->admin = $admin;
        $view->comments = $comments;
        $view->preview = $preview;
        $view->post = $post;

        return $view->render();
    }
    
}