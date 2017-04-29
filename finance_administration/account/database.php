<?php
class Databases{
    public $con;

    public function __construct()
    {
        $this -> con = mysqli_connect('localhost', 'root', 'toor', 'final_project');
        if(!$this -> con)
        {
            echo 'Database Connection Error '.mysqli_connect_error($this -> con);
        }
    }

    public function insert($table, $data)
    {
        $string = "INSERT INTO".$table." (";
    }
}
?>