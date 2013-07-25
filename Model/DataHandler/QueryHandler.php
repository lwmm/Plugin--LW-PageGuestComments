<?php

namespace LwPageGuestComments\Model\DataHandler;

class QueryHandler
{
    protected $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function getALlComments($index)
    {
        $this->db->setStatement("SELECT id,opt1text,opt1clob,opt1number FROM t:lw_master WHERE lw_object = :lw_object AND opt2number = :index ORDER BY id DESC ");
        $this->db->bindParameter("lw_object", "s", "lw_page_guest_comments");
        $this->db->bindParameter("index", "i", $index);
        
        return $this->db->pselect();
    }
}