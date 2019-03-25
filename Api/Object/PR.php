<?php
class PR{
 
    // database connection and table name
    private $conn;
    private $table_name = "pr";
 
    // object properties
    public $PRId;
    public $PRName;
    public $Project;
    public $PRCode;
    public $CostCenter;
    public $Colective;
    public $PurchaseCode;
    public $IsEvaluation;
    // constructor with $db as database connection

    public function __construct($db){
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function GetPR(){
        $result = array();
        $query = "SELECT ".
                  "A.PRId ".
                  ",A.PRName".
                  ",A.Project".
                  ",A.PRCode".
                  ",A.CostCenter".
                  ",A.Colective".
                  ",A.PurchaseCode".
                  ",A.IsEvaluation".
                  ",B.IsApprove".
                  ",B.EvaluationId".
                  " FROM pr A LEFT JOIN evaluation B ON A.PRId = B.PRId".
                  " ORDER BY A.IsEvaluation ASC";
        $stmt = $this->conn->prepare($query);
        try
        {
          $stmt->execute();
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
          {
              extract($row);
              $_pr = array(
                        "PRId"          => $PRId,
                        "PRName"        => $PRName,
                        "Project"       => $Project,
                        "PRCode"        => $PRCode,
                        "CostCenter"    => $CostCenter,
                        "Colective"     => $Colective,
                        "PurchaseCode"  => $PurchaseCode,
                        "IsEvaluation"  => $IsEvaluation,
                        "EvalApprove"   => $IsEvaluation == 1 ? $IsApprove == 0? 'SEDANG BERLANGSUNG': 'SELESAI' : null,
                        "EvaluationId"  => $EvaluationId
              );
              array_push($result, $_pr);
          }
        }catch (Exception $e){
          http_response_code(404);
          throw $e;
        }
        return $result;
    }

    function GetPRById($PRId){
        $query = "SELECT * FROM pr WHERE PRId = '$PRId'";
        $result = array();
        $stmt = $this->conn->prepare($query);
        try
        {
          $stmt->execute(); 
          $pr = $stmt->fetch();
          extract($pr);
          if($PRId !== null){
            $result = array(
                "PRId"          => $PRId,
                "PRName"        => $PRName,
                "Project"       => $Project,
                "PRCode"        => $PRCode,
                "CostCenter"    => $CostCenter,
                "Colective"     => $Colective,
                "PurchaseCode"  => $PurchaseCode,
                "IsEvaluation"  => $IsEvaluation,
                "PRItem"        => $this->GetPRItem($PRId),
                "PRVendor"      => $this->GetPRVendor($PRId)
            );
          }
        }catch (Exception $e){
              http_response_code(404);
              throw $e;
        }
        
        return $result;
    }

    function GetPRVendor($PRId)
    {

        $result = array();
        $query = "SELECT * FROM prvendor A INNER JOIN vendor B ON A.VendorId = B.VendorId WHERE PRId ='$PRId'";
        $stmt = $this->conn->prepare($query);
        try
        {
          $stmt->execute();
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
          {
              extract($row);
              $_prvendor = array(
                        "PRVendorId"   => $PRVendorId,
                        "VendorId"     => $VendorId,
                        "PRId"         => $PRId,
                        "VendorName"   => $VendorName,
                        "Address"      => $Address,
              );
              array_push($result, $_prvendor);
          }
        }catch (Exception $e){
          http_response_code(404);
          throw $e;
        }
        return $result;
    }

    function GetPRItem($PRId)
    {

        $result = array();
        $query = "SELECT * FROM pritem A INNER JOIN item B ON A.ItemId = B.ItemId INNER JOIN UoM C ON A.UoMId = C.UoMId WHERE PRId ='$PRId'";
        $stmt = $this->conn->prepare($query);
        try
        {
          $stmt->execute();
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
          {
              extract($row);
              $_pritem = array(
                        "PRItemId"  => $PRItemId,
                        "Qty"       => $Qty,
                        "ItemId"    => $ItemId,
                        "ItemName"  => $ItemName,
                        "PRId"      => $PRId,
                        "UoMId"     => $UoMId,
                        "UoMKode"   => $UoMKode,
                        "UoMName"   => $UoMName,
              );
              array_push($result, $_pritem);
          }
        }catch (Exception $e){
          http_response_code(404);
          throw $e;
        }
        return $result;
    }

    function InsertPR($PR)
    {
        $pr = [ 'PRName'       => $PR->PRName,
                'Project'      => $PR->Project,
                'PRCode'       => "PR-".strtoupper(uniqid()),
                'CostCenter'   => 0,
                'Colective'    => '',
                'PurchaseCode' => '',
                'IsEvaluation' => '0'
              ];
        $sql_pr = "INSERT INTO ".$this->table_name." (PRName, Project,PRCode,CostCenter,Colective,PurchaseCode,IsEvaluation) VALUES (:PRName, :Project, :PRCode, :CostCenter, :Colective, :PurchaseCode, :IsEvaluation)";
        $stmt= $this->conn->prepare($sql_pr);

        try
         {
            $this->conn->beginTransaction();
            $stmt->execute($pr);
            $this->PRId = $this->conn->lastInsertId();

            // Insert Detail
              $this->InsertPRVendor($PR);
              $this->InsertPRItem($PR);
            // End Insert Detail
              
            $this->conn->commit();
        }catch (Exception $e){
          $this->conn->rollback();
          http_response_code(404);
          throw $e;
        }
    }
    function InsertPRVendor($PR)
    {
         $insert_values = array();
            $question_marks =null;
            foreach ($PR->PRVendor as $keyVendor => $Vendor) 
            { 
              
                $_prvendor = array(
                                  "VendorId"  => $Vendor->VendorId, 
                                  "PRId"      => $this->PRId, 
                );
                $question_marks[] = '('  . $this->Placeholders('?', sizeof($_prvendor)) . ')';
                $insert_values = array_merge($insert_values, array_values($_prvendor));
            }

            $sql_prvendor = "INSERT INTO prvendor (VendorId, PRId) VALUES " .
                 implode(',', $question_marks);
            $stmt = $this->conn->prepare($sql_prvendor);

            try
            {
              $stmt->execute($insert_values);
            }catch (Exception $e){
              http_response_code(404);
              throw $e;
            }
    }
    function InsertPRItem($PR)
    {
        $insert_values = array();
            $question_marks =null;
            foreach ($PR->PRItem as $keyItem => $Item) 
            { 
              
                $_pritem = array(
                                  "Qty"     => $Item->Qty, 
                                  "ItemId"  => $Item->ItemId, 
                                  'PRId'    => $this->PRId,
                                  'UoMId'   => $Item->UoMId,
                );
                $question_marks[] = '('  . $this->Placeholders('?', sizeof($_pritem)) . ')';
                $insert_values = array_merge($insert_values, array_values($_pritem));
            }

            $sql_pritem = "INSERT INTO pritem (Qty,ItemId,PRId,UoMId) VALUES " .
                 implode(',', $question_marks);
            $stmt = $this->conn->prepare($sql_pritem);
            
            try
            {
              $stmt->execute($insert_values);
            }catch (Exception $e){
              http_response_code(404);
              throw $e;
            }
    }
    function Placeholders($text, $count=0, $separator=",")
    {
        $result = array();
        if($count > 0){
            for($x=0; $x < $count; $x++)
            {
                $result[] = $text;
            }
        }

        return implode($separator, $result);
    }

}