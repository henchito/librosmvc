<?php
class Editorial extends EntidadBase{
    private $id;
     
    public function __construct($adapter) {
        $table="Editorial";
        parent::__construct($table,$adapter);
    }
     
    public function getId() {
        return $this->id;
    }
 
    public function setId($id) {
        $this->id = $id;
    }
    
    public function save(){
        $query="INSERT INTO Editorial (id)
                VALUES('".$this->id."');";
        $save=$this->db()->query($query);
        //$this->db()->error;
        return $save;
    }
}
?>