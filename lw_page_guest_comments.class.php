<?php

/**
 * This plugin supports the creation of a tag cloud.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 * @package lw_page_guest_comments
 */
class lw_page_guest_comments extends lw_plugin
{

    protected $repository;

    /**
     * For the functionality of this plugin is it necessary to include
     * the "Autoloader" and the instances of "in_auth" and "auth"
     * objects.
     */
    public function __construct()
    {
        parent::__construct();
        include_once(dirname(__FILE__) . '/Services/Autoloader.php');
        $autoloader = new \LwPageGuestComments\Services\Autoloader();
        $this->auth = \lw_registry::getInstance()->getEntry("auth");
    }

    /**
     * The HTML frontend output will be build.
     * 
     * @return string
     */
    public function buildPageOutput()
    {
        $plugindata = $this->repository->plugins()->loadPluginData($this->getPluginName(), $this->params['oid']);

        $response = new \LwPageGuestComments\Services\Response();
        $response->setDbObject($this->db);
        $response->setParameterByKey("lang", $plugindata["parameter"]["language"]);
        
        $controller = new \LwPageGuestComments\Controller\PageGuestCommentsController($this->config, $response, $this->request, $this->auth->isLoggedIn());
        $controller->execute();
        
        return $response->getOutputByKey("LwPageGuestComments");
    }

    /**
     * The HTML backend output will be build.
     * 
     * @return string
     */
    public function getOutput()
    {
       $backend = new \LwPageGuestComments\Controller\Backend($this->config, $this->request, $this->repository, $this->getPluginName(), $this->getOid());
        if ($this->request->getAlnum("pcmd") == "save") {
            $backend->backend_save();
        }
        return $backend->backend_view();
    }

    /**
     * Here will be set if the plugin-conetentbox is deleteable from a page.
     * 
     * @return boolean
     */
    function deleteEntry()
    {
        return true;
    }

}