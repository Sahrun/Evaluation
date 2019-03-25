<?php
class Vendor{
 
    // database connection and table name
    private $conn;
    private $table_name = "vendor";
 
    // object properties
    public $VendorId;
    public $VendorName;
    public $Address;
    public $Status;
    // constructor with $db as database connection

    public function __construct($db){
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


  function SaveVendor($Vendor){
          $vendor = [
                    'VendorName' => $Vendor->VendorName,
                    'Address'    => $Vendor->Address,
                    'Status'     => $Vendor->Status
                  ];
          $sql_vendor = "INSERT INTO ".$this->table_name." (VendorName, Address, Status) VALUES (:VendorName, :Address, :Status)";
          $stmt= $this->conn->prepare($sql_vendor);
          try
          {
            $stmt->execute($vendor);
          }catch (Exception $e){
            http_response_code(404);
            throw $e;
          }
  }

  function UpdateVendor($Vendor)
  {
      $sql_vendor = "UPDATE ".$this->table_name." SET VendorName=:VendorName, Address=:Address , Status=:Status WHERE VendorId=:VendorId";
      $stmt= $this->conn->prepare($sql_vendor);

      $stmt->bindValue(':VendorName', $Vendor->VendorName);
      $stmt->bindValue(':Address', $Vendor->Address);
      $stmt->bindValue(':Status', $Vendor->Status);
      $stmt->bindValue(':VendorId', $Vendor->VendorId);

      try
      {
        $stmt->execute();
      }catch (Exception $e){
        http_response_code(404);
        throw $e;
      }
  }

  function GetVendor(){
      $result = array(); 
      $query = "SELECT * FROM ".$this->table_name." WHERE Status = 'Active' ORDER BY VendorId DESC";
   
      $stmt = $this->conn->prepare($query);
      try
      {
       $stmt->execute();
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            $_vendor = array(
                      "VendorId"    => $VendorId,
                      "VendorName"  => $VendorName,
                      "Address"     => $Address,
                      "Status"      => $Status
            );
            array_push($result, $_vendor);
        }
      }catch (Exception $e){
            http_response_code(404);
            throw $e;
      }
      return $result;
  }
  function GetVendorById($VendorId)
  {
        $result = array();
        $query = "SELECT * FROM ".$this->table_name." WHERE VendorId ='$VendorId'";

        $stmt = $this->conn->prepare($query); 
        try
        {
          $stmt->execute();
          $vendor = $stmt->fetch();
          extract($vendor);
          $result = array(
                    "VendorId"    => $VendorId,
                    "VendorName"  => $VendorName,
                    "Address"     => $Address,
                    "Status"      => $Status
          );
        }catch (Exception $e){
          http_response_code(404);
          throw $e;
        }

        return $result;
  }
}