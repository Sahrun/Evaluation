<?php
class Group{

    // database connection and table name
    private $conn;
    private $table_name = "`group`";

    // object properties
    public $GroupId;
    public $GroupName;
    // constructor with $db as database connection

    public function __construct($db){
        $this->conn = $db;
    }

  function GetGroup()
  {
    $result = array();
    $query = "SELECT * FROM ".$this->table_name."";
    $stmt = $this->conn->prepare($query);
    try
    {
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
                  extract($row);
                  $_group = array(  "GroupId"   => $GroupId,
                                   "GroupName"  => $GroupName,
                  );
                 array_push($result, $_group);
        }
  }catch (Exception $e){
    http_response_code(404);
    throw $e;
  }
    return $result;
  }
}
