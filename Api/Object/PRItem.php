<?php
class PRItem{
 
    // database connection and table name
    private $conn;
    private $table_name = "pritem";
 
    // object properties
    public $PRItemId ;
    public $Qty;
    public $ItemId;
    public $PRId;
    public $UoMId;
    // constructor with $db as database connection

    public function __construct($db){
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function GetPRItem($PRId){
 
        $query = "SELECT A.PRItemId,A.ItemId,B.ItemName,A.Qty,C.UoMName FROM ".$this->table_name." A 
                  INNER JOIN Item B ON A.ItemId = B.ItemId
                  INNER JOIN UoM C ON A.UoMId = C.UoMId
                  WHERE PRId ='$PRId'
                  ORDER BY PRItemId DESC";
     
        $stmt = $this->conn->prepare($query);
        try
        {
          $stmt->execute();
        }catch (Exception $e){
          http_response_code(404);
          throw $e;
        }
     
        return $stmt;
    }

}