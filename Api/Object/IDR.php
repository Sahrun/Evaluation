<?php
class IDR{
 
    // database connection and table name
    private $conn;
    private $table_name = "idr";
 
    // object properties
    public $IDRId;
    public $IDRCode;
    public $Description;
    public $Status;
    // constructor with $db as database connection

    public function __construct($db){
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


  function SaveIDR($IDR){
          $idr = [
                    'IDRCode'     => $IDR->IDRCode,
                    'Description' => $IDR->Description,
                    'Status'      => $IDR->Status
                  ];
          $sql_idr = "INSERT INTO ".$this->table_name." (IDRCode, Description, Status) VALUES (:IDRCode, :Description, :Status)";
          $stmt= $this->conn->prepare($sql_idr);
          try
          {
            $stmt->execute($idr);
          }catch (Exception $e){
            http_response_code(404);
            throw $e;
          }
  }

  function UpdateIDR($IDR)
  {
      $sql_idr = "UPDATE ".$this->table_name." SET IDRCode=:IDRCode, Description=:Description , Status=:Status WHERE IDRId=:IDRId";
      $stmt= $this->conn->prepare($sql_idr);

      $stmt->bindValue(':IDRCode', $IDR->IDRCode);
      $stmt->bindValue(':Description', $IDR->Description);
      $stmt->bindValue(':Status', $IDR->Status);
      $stmt->bindValue(':IDRId', $IDR->IDRId);

      try
      {
        $stmt->execute();
      }catch (Exception $e){
        http_response_code(404);
        throw $e;
      }
  }

  function GetIDR(){
      $result = array(); 
      $query = "SELECT * FROM ".$this->table_name." WHERE Status = 'Active' ORDER BY IDRId DESC";
   
      $stmt = $this->conn->prepare($query);
      try
      {
       $stmt->execute();
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            $_idr = array(
                      "IDRId"        => $IDRId,
                      "IDRCode"      => $IDRCode,
                      "Description"  => $Description,
                      "Status"       => $Status
            );
            array_push($result, $_idr);
        }
      }catch (Exception $e){
            http_response_code(404);
            throw $e;
      }
      return $result;
  }
  function GetIDRById($IDRId)
  {
        $result = array();
        $query = "SELECT * FROM ".$this->table_name." WHERE IDRId ='$IDRId'";

        $stmt = $this->conn->prepare($query); 
        try
        {
          $stmt->execute();
          $idr = $stmt->fetch();
          extract($idr);
          $result = array(
                    "IDRId"        => $IDRId,
                    "IDRCode"      => $IDRCode,
                    "Description"  => $Description,
                    "Status"       => $Status
          );
        }catch (Exception $e){
          http_response_code(404);
          throw $e;
        }

        return $result;
  }
}