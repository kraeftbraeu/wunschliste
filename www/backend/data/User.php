<?php
class User //extends Data
{
    public $id, $name, $email, $isadmin;
    public function __construct($id, $name, $email, $isadmin)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->isadmin = $isadmin;
    }

    public function isAdmin()
    {
         return $this->isadmin;
    }

    /*public function printData($data)
    {
        foreach ($data as $key => $value)
            echo "<p>$key => $value</p>\n";
    }*/
}
?>