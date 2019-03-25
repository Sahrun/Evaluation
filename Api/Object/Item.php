<?php
class Item{
 
    // database connection and table name
    private $conn;
    private $table_name = "item";
 
    // object properties
    public $ItemId;
    public $ItemName;
    public $Status;
    public $Description;
    // constructor with $db as database connection

    public function __construct($db){
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


  function SaveItem($Item){

          $item = [
                    'ItemName'    => $Item->ItemName,
                    'Status'      => $Item->Status,
                    'Description' => $Item->Description
                  ];
          $sql_item = "INSERT INTO ".$this->table_name." (ItemName, Status, Description) VALUES (:ItemName, :Status, :Description)";
          $stmt= $this->conn->prepare($sql_item);
          try
          {
            $stmt->execute($item);
          }catch (Exception $e){
            http_response_code(404);
            throw $e;
          }
  }

  function UpdateItem($item)
  {
      $sql_item = "UPDATE ".$this->table_name." SET ItemName=:ItemName, Status=:Status , Description=:Description WHERE ItemId=:ItemId";
      $stmt= $this->conn->prepare($sql_item);

      $stmt->bindValue(':ItemName', $item->ItemName);
      $stmt->bindValue(':Status', $item->Status);
      $stmt->bindValue(':Description', $item->Description);
      $stmt->bindValue(':ItemId', $item->ItemId);

      try
      {
        $stmt->execute();
      }catch (Exception $e){
        http_response_code(404);
        throw $e;
      }
  }

  function GetItem(){
      $result = array(); 
      $query = "SELECT * FROM ".$this->table_name." WHERE Status = 'Active' ORDER BY ItemId DESC";
   
      $stmt = $this->conn->prepare($query);
      try
      {
       $stmt->execute();
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            $_item = array(
                      "ItemId"        => $ItemId,
                      "ItemName"      => $ItemName,
                      "Status"        => $Status,
                      "Description"   => $Description
            );
            array_push($result, $_item);
        }
      }catch (Exception $e){
            http_response_code(404);
            throw $e;
      }
      return $result;
  }
  function GetItemById($ItemId)
  {
        $result = array();
        $query = "SELECT * FROM ".$this->table_name." WHERE ItemId ='$ItemId'";

        $stmt = $this->conn->prepare($query); 
        try
        {
          $stmt->execute();
          $item = $stmt->fetch();
          extract($item);
          $result = array(
                    "ItemId"        => $ItemId,
                    "ItemName"      => $ItemName,
                    "Status"        => $Status,
                    "Description"   => $Description
          );
        }catch (Exception $e){
          http_response_code(404);
          throw $e;
        }

        return $result;
  }
}