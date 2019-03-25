<?php
class UoM{
 
    // database connection and table name
    private $conn;
    private $table_name = "uom";
 
    // object properties
    public $UoMId;
    public $UoMKode;
    public $UoMName;
    public $Status;
    // constructor with $db as database connection

    public function __construct($db){
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


  function SaveUoM($UoM){
          $uom = [
                    'UoMKode' => $UoM->UoMKode,
                    'UoMName' => $UoM->UoMName,
                    'Status'  => $UoM->Status
                  ];
          $sql_uom = "INSERT INTO ".$this->table_name." (UoMKode, UoMName, Status) VALUES (:UoMKode, :UoMName, :Status)";
          $stmt= $this->conn->prepare($sql_uom);
          try
          {
            $stmt->execute($uom);
          }catch (Exception $e){
            http_response_code(404);
            throw $e;
          }
  }

  function UpdateUoM($UoM)
  {
      $sql_uom = "UPDATE ".$this->table_name." SET UoMKode=:UoMKode, UoMName=:UoMName , Status=:Status WHERE UoMId=:UoMId";
      $stmt= $this->conn->prepare($sql_uom);

      $stmt->bindValue(':UoMKode', $UoM->UoMKode);
      $stmt->bindValue(':UoMName', $UoM->UoMName);
      $stmt->bindValue(':Status', $UoM->Status);
      $stmt->bindValue(':UoMId', $UoM->UoMId);

      try
      {
        $stmt->execute();
      }catch (Exception $e){
        http_response_code(404);
        throw $e;
      }
  }

  function GetUoM(){
      $result = array(); 
      $query = "SELECT * FROM ".$this->table_name." WHERE Status = 'Active' ORDER BY UoMId DESC";
   
      $stmt = $this->conn->prepare($query);
      try
      {
       $stmt->execute();
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            $_uom = array(
                      "UoMId"    => $UoMId,
                      "UoMKode"  => $UoMKode,
                      "UoMName"  => $UoMName,
                      "Status"   => $Status
            );
            array_push($result, $_uom);
        }
      }catch (Exception $e){
            http_response_code(404);
            throw $e;
      }
      return $result;
  }
  function GetUoMById($uomId)
  {
        $result = array();
        $query = "SELECT * FROM ".$this->table_name." WHERE UoMId ='$uomId'";

        $stmt = $this->conn->prepare($query); 
        try
        {
          $stmt->execute();
          $uom = $stmt->fetch();
          extract($uom);
          $result = array(
                  "UoMId"    => $UoMId,
                  "UoMKode"  => $UoMKode,
                  "UoMName"  => $UoMName,
                  "Status"   => $Status
          );
        }catch (Exception $e){
          http_response_code(404);
          throw $e;
        }

        return $result;
  }
}