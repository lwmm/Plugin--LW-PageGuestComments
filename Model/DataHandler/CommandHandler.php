<?php

namespace LwPageGuestComments\Model\DataHandler;

class CommandHandler
{
    protected $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function addComment($array)
    {
        $this->db->setStatement("INSERT INTO t:lw_master (lw_object, opt1text,opt1number, opt2number) VALUES (:lw_object, :email, :date, :index) ");
        $this->db->bindParameter("lw_object", "s", "lw_page_guest_comments");
        $this->db->bindParameter("email", "s", $array["email"]);
        $this->db->bindParameter("date", "i", date("YmdHis"));
        $this->db->bindParameter("index", "i", $array["index"]);
        
        $id = $this->db->pdbinsert($this->db->gt("lw_master"));

        if ($id > 0) {
            return $this->db->saveClob($this->db->gt("lw_master"), "opt1clob", $this->db->quote($array["comment"]), $id);
        }
        else {
            return false;
        }
    }
    
    public function deleteComment($id)
    {
        $this->db->setStatement("DELETE FROM t:lw_master WHERE id = :id AND lw_object = :lw_object ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("lw_object", "s", "lw_page_guest_comments");
        
        return $this->db->pdbquery();
    }
}