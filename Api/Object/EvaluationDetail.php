<?php
class EvaluationDetail{
 
    // database connection and table name
    private $conn;
    private $table_name = "evaluationdetail";
 
    // object properties
    public $EvaluationDetailId ;
    public $SubTotal;
    public $Total;
    public $Discount;
    public $PPK;
    public $GrendTotal;
    public $DeliveryPoint;
    public $PaymentTerms;
    public $VendorInvitationId;
    public $EvaluationId;
    public $PriceIDR;
    public $PRItemId;
    public $ItemId;
    // constructor with $db as database connection

    public function __construct($db){
        $this->conn = $db;
    }

    function GetItemPR($PRId){
 
        $query = "SELECT A.PRItemId,A.ItemId,B.ItemName,A.Qty,C.UoMName FROM pritem A 
                  INNER JOIN Item B ON A.ItemId = B.ItemId
                  INNER JOIN UoM C ON A.UoMId = C.UoMId
                  WHERE PRId ='$PRId'
                  ORDER BY PRItemId DESC";
     
        $stmt = $this->conn->prepare($query);
     
        $stmt->execute();
     
        return $stmt;
    }

}