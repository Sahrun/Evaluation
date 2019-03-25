<?php
class PRVendor{
 
    // database connection and table name
    private $conn;
    private $table_name = "prvendor";
 
    // object properties
    public $PRVendorId;
    public $PRId;
    public $VendorId;
    // constructor with $db as database connection

    public function __construct($db){
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


function GetPRVendor($PRId){
    
    $query = "SELECT A.PRVendorId,A.VendorId,B.VendorName
              FROM ".$this->table_name." A 
              INNER JOIN vendor B ON A.VendorId = B.VendorId
              WHERE A.PRId = '$PRId'
              ORDER BY A.PRVendorId ASC";
 
    $stmt = $this->conn->prepare($query);
 
    $stmt->execute();
 
    return $stmt;
}

}