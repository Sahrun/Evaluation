<?php
include_once 'PR.php';
include_once 'PRVendor.php';
include_once 'EvaluationDetail.php';
include_once 'PRItem.php';
class Evaluation
{

    private $conn;
    private $table_name = "evaluation";

    public $EvaluationId;
    public $PRId;
    public $EvaluationCode;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function GetEvalVendorDet($PRId)
    {
      $result = array();
      $PRVendor = new PRVendor($this->conn);
      $stmt = $PRVendor->GetPRVendor($PRId);
      $num = $stmt->rowCount();

      if($num>0){
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

              extract($row);
              $evaluationVendorDetail  = array(
                   "EvaluationDetailId" => null,
                   "SubTotal"           => null,
                   "Total"              => null,
                   "Discount"           => null,
                   "PPN"                => null,
                   "GrandTotal"         => null,
                   "DeliveryPoint"      => null,
                   "PaymentTerms"       => null,
                   "PRVendorId"         => null,
                   "EvaluationId"       => null,
                   "IDRId"              => null
              );
              $PRVendor=array(
                  "PRVendorId"         => $PRVendorId,
                  "VendorId"           => $VendorId,
                  "VendorName"         => $VendorName,
                  "Disabled"           => false,
                  "IsWinner"           => false,
                  "SelectedChild"      => false,
                  "Include"            => array(),
                  "Exclude"            => array(),
                  "EvaluationDetail"   => $evaluationVendorDetail
              );

              array_push($result, $PRVendor);
          }
      }
      return $result;
    }

    function GetEvalItemDet($PRId)
    {
        $PRItem = new PRItem($this->conn);
        $stmt = $PRItem->GetPRItem($_GET['PRId']);

        $result =array();

        if($stmt->rowCount() >0){
          $_PRItem = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $PRVendor = new PRVendor($this->conn);
          $stmt = $PRVendor->GetPRVendor($_GET['PRId']);
          if($stmt->rowCount() > 0){
             $Vendor = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($_PRItem as $rowItem) {
                extract($rowItem);

                    $tempItem = array(
                        'PRItemId' => $PRItemId,
                        'ItemId'   => $ItemId,
                        'ItemName' => $ItemName,
                        'Qty'      => $Qty,
                        'UoMName'  => $UoMName,
                        'Vendor'   => array()
                    );

                    foreach ($Vendor as $rowVendor){
                        extract($rowVendor);

                        $temp=array(
                            "EvaluationDetailVendorId" => null,
                            "Delivery"     => null,
                            "UnitPrice"    => null,
                            "TotalPrice"   => null,
                            "Description"  => null,
                            "PRVendorId"   => $PRVendorId,
                            "PRItemId"     => $PRItemId,
                            "EvaluationId" => null,
                            "Selected"     => false,
                            "Disabled"     => false
                        );
                      array_push($tempItem["Vendor"],$temp);
                    }

                array_push($result, $tempItem);
            }
          }
          else
          {
            http_response_code(404);
          }
        }
        else
        {
          http_response_code(404);
        }

      return $result;
    }

    function GetEvaluations($userId)
    {

      $result = array();
      $query = "SELECT A.EvaluationId
                ,B.PRId
                ,A.EvaluationCode
                ,B.PRName
                ,B.PRCode
                ,B.Project
                ,A.IsApprove
                ,C.ApprovalId
                ,(SELECT IsApprove FROM approvaluser WHERE ApprovalId = C.ApprovalId AND UserId ='$userId' LIMIT 1) AS ApprovalUser
                FROM evaluation A
                INNER JOIN pr B ON A.PRId = B.PRId
                INNER JOIN approval C ON C.EvaluationId = A.EvaluationId
                INNER JOIN approvaluser D ON D.ApprovalId = C.ApprovalId
                WHERE D.UserId = '$userId'
                ORDER BY A.IsApprove ASC";
      $stmt = $this->conn->prepare($query);
      try
      {
      $stmt->execute();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
      {
              extract($row);
              $_evaluation = array(
                                "EvaluationId"     => $EvaluationId,
                                "PRId"             => $PRId,
                                "EvaluationCode"   => $EvaluationCode,
                                "PRName"           => $PRName,
                                "Project"          => $Project,
                                "PRCode"           => $PRCode,
                                "IsApprove"        => $IsApprove,
                                "IsEvaluation"     => $ApprovalUser
              );
             array_push($result, $_evaluation);
      }
      }catch (Exception $e){
          http_response_code(404);
          throw $e;
      }
      return $result;
    }

    function InsertEvaluation($Evaluation,$userId)
    {
        $evaluation = ['PRId'           => $Evaluation->PRId,
                       'EvaluationCode' => "EVL-".strtoupper(uniqid()),
                       'Note'           => $Evaluation->Note,
                       'IsApprove'      => '0',
                       'UserId'         => $userId
                      ];
        $sql_evaluation = "INSERT INTO ".$this->table_name." (PRId, EvaluationCode,Note,IsApprove,UserId
) VALUES (:PRId, :EvaluationCode, :Note, :IsApprove, :UserId)";
        $stmt= $this->conn->prepare($sql_evaluation);

        try
         {
            $this->conn->beginTransaction();
            $stmt->execute($evaluation);
            $this->EvaluationId = $this->conn->lastInsertId();

            // Insert Detail
              $this->InsertEvaluationdetailvendor($Evaluation);
              $this->InsertInclude($Evaluation);
              $this->InsertExclude($Evaluation);
              $this->InsertEvaluationdetail($Evaluation);
              $this->InsertApproval();
              $this->UpdatePRIsEvaluation($Evaluation->PRId);
            // End Insert Detail

            $this->conn->commit();
        }catch (Exception $e){
          $this->conn->rollback();
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

    function InsertEvaluationdetailvendor($Evaluation)
    {
            $insert_values = array();
            $question_marks =null;
            foreach ($Evaluation->ItemEvaluation as $keyItem => $Item)
            {
              foreach ($Item->Vendor as $keyVendor => $Vendor)
              {
                   $_vendorDetail = array(
                                  "Delivery"            => $Vendor->Delivery,
                                  "UnitPrice"           => $Vendor->UnitPrice,
                                  "TotalPrice"          => $Vendor->TotalPrice,
                                  "Description"         => $Vendor->Description,
                                  "PRVendorId"  => $Vendor->PRVendorId,
                                  "PRItemId"            => $Vendor->PRItemId,
                                  "EvaluationId"        => $this->EvaluationId
                   );
                  $question_marks[] = '('  . $this->Placeholders('?', sizeof($_vendorDetail)) . ')';
                  $insert_values = array_merge($insert_values, array_values($_vendorDetail));
              }
            }

            $sql_evaluationDetailVendor = "INSERT INTO evaluationdetailvendor (Delivery, UnitPrice, TotalPrice,Description,PRVendorId,PRItemId,EvaluationId) VALUES " .
                 implode(',', $question_marks);
            $stmt = $this->conn->prepare($sql_evaluationDetailVendor);
            $stmt->execute($insert_values);
    }

    function InsertInclude($Evaluation)
    {
            $insert_values = array();
            $question_marks =null;
            foreach ($Evaluation->PRVendor as $keyInvitation => $Invitation)
             {
              foreach ($Invitation->Include as $keyInclude => $include)
              {
                if($include->ItemName !== null && $include->ItemName !== "")
                {
                   $_include = array(
                                  "ItemName"            =>$include->ItemName,
                                  "EvaluationId"        =>$this->EvaluationId,
                                  "PRVendorId"          =>$Invitation->PRVendorId
                   );
                  $question_marks[] = '('  . $this->Placeholders('?', sizeof($_include)) . ')';
                  $insert_values = array_merge($insert_values, array_values($_include));
                }
              }
            }

            $sql_include = "INSERT INTO include (ItemName,EvaluationId,PRVendorId) VALUES " .
                 implode(',', $question_marks);
            $stmt = $this->conn->prepare($sql_include);
            $stmt->execute($insert_values);
    }

    function InsertExclude($Evaluation)
    {
            $insert_values = array();
            $question_marks =null;
            foreach ($Evaluation->PRVendor as $keyInvitation => $Invitation)
            {
              foreach ($Invitation->Exclude as $keyExclude => $exclude)
              {
                if($exclude->ItemName !== null && $exclude->ItemName !== "")
                {
                   $_exclude = array(
                                  "ItemName"            =>$exclude->ItemName,
                                  "EvaluationId"        =>$this->EvaluationId,
                                  "PRVendorId"          =>$Invitation->PRVendorId
                   );
                  $question_marks[] = '('  . $this->Placeholders('?', sizeof($_exclude)) . ')';
                  $insert_values = array_merge($insert_values, array_values($_exclude));
                }
              }
            }

            $sql_exclude = "INSERT INTO exclude (ItemName,EvaluationId ,PRVendorId) VALUES " .
                 implode(',', $question_marks);
            $stmt = $this->conn->prepare($sql_exclude);
            $stmt->execute($insert_values);
    }

    function InsertEvaluationdetail($Evaluation)
    {
            $insert_values = array();
            $question_marks =null;
            foreach ($Evaluation->PRVendor as $keyInvitation => $Invitation)
            {
                   $_evaluationdetail = array(
                                  "SubTotal"            => $Invitation->EvaluationDetail->SubTotal,
                                  "Total"               => $Invitation->EvaluationDetail->Total,
                                  "Discount"            => $Invitation->EvaluationDetail->Discount,
                                  "PPN"                 => $Invitation->EvaluationDetail->PPN,
                                  "GrandTotal"          => $Invitation->EvaluationDetail->GrandTotal,
                                  "DeliveryPoint"       => $Invitation->EvaluationDetail->DeliveryPoint,
                                  "PaymentTerms"        => $Invitation->EvaluationDetail->PaymentTerms,
                                  "PRVendorId"          => $Invitation->PRVendorId,
                                  "EvaluationId"        => $this->EvaluationId,
                                  "IDRId"                 => $Invitation->EvaluationDetail->IDRId
                   );
                  $question_marks[] = '('  . $this->Placeholders('?', sizeof($_evaluationdetail)) . ')';
                  $insert_values = array_merge($insert_values, array_values($_evaluationdetail));
            }

            $sql_evaluationdetail = "INSERT INTO evaluationdetail (SubTotal,Total,Discount,PPN,GrandTotal,DeliveryPoint,PaymentTerms,PRVendorId,EvaluationId,IDRId) VALUES " .
                 implode(',', $question_marks);
            $stmt = $this->conn->prepare($sql_evaluationdetail);
            $stmt->execute($insert_values);
    }

    function InsertApproval()
    {

        $approval = ['EvaluationId' => $this->EvaluationId];
        $sql_approval = "INSERT INTO approval (EvaluationId) VALUES (:EvaluationId)";
        $stmt= $this->conn->prepare($sql_approval);
        $stmt->execute($approval);
        $approvalId = $this->conn->lastInsertId();

        $insert_values = array();
        $question_marks =null;

        $apprivalSetting = $this->GetApprovalSetting();
        while ($row = $apprivalSetting->fetch(PDO::FETCH_ASSOC))
          {
              extract($row);
              $_approvalUser = array(
                                  "ApprovalId"        => $approvalId,
                                  "UserId"            => $UserId,
                                  "IsApprove"         => 0,
                                  "Status"            => 0,
                                  "ApproveNumber"     => $ApproveNumber,
                                  "ApprovalSettingId" => $ApprovalSettingId
              );
              $question_marks[] = '('  . $this->Placeholders('?', sizeof($_approvalUser)) . ')';
              $insert_values = array_merge($insert_values, array_values($_approvalUser));
          }
        $sql_approvalUser = "INSERT INTO approvaluser (ApprovalId, UserId, IsApprove, Status, ApproveNumber, ApprovalSettingId) VALUES " .implode(',', $question_marks);
        $stmt = $this->conn->prepare($sql_approvalUser);
        $stmt->execute($insert_values);
    }

    function GetApprovalSetting()
    {
        $query = "SELECT Version FROM approvalsetting ORDER BY ApprovalSettingId DESC LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $appVersion = $stmt->fetch();
        extract($appVersion);

        $query = "SELECT * FROM approvalsetting WHERE Version = '$Version' ORDER BY ApprovalSettingId ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    function GetEvaluationView($evaluationId)
    {
        $PR = new PR($this->conn);
        $query = "SELECT
                  A.EvaluationId,
                  A.EvaluationCode,
                  A.PRId,
                  A.Note,
                  B.UserId,
                  B.FullName,
                  B.Department
                  FROM evaluation A
                  INNER JOIN user B ON A.UserId = B.UserId
                  WHERE EvaluationId = $evaluationId";
        $result = array();
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $evaluation = $stmt->fetch();
        extract($evaluation);
        if($EvaluationId !== null){
          $pr = $PR->GetPRById($PRId);
          if(count($pr) > 0){
              $User = array(
                'UserId'      => $UserId,
                'FullName' => $FullName,
                'Department'  => $Department
              );
              $result = array(
                  "EvaluationId"           => $EvaluationId,
                  "EvaluationCode"         => $EvaluationCode,
                  "PRId"                   => $PRId,
                  "PR"                     => $pr,
                  "User"                   => $User,
                  "PRVendor"               => $this->GetVendorInvormation($PRId,$evaluationId),
                  "Evaluationdetailvendor" => $this->GetEvaluationDetailVendorInformation($PRId,$EvaluationId),
                  "Note"                   => $Note,
                  "Approval"               => $this->GetApprovalInformation($EvaluationId),
              );
          }
        }
        return $result;
    }

    function GetVendorInvormation($PRId,$evaluationId)
    {
      $result = array();
      $prvendor = new PRVendor($this->conn);
      $stmt = $prvendor->GetPRVendor($PRId);

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $prvendor=array(
                "PRVendorId" => $PRVendorId,
                "VendorId"           => $VendorId,
                "VendorName"         => $VendorName,
                "Include"            => $this->GetIncludeEvaluation($evaluationId,$PRVendorId),
                "Exclude"            => $this->GetExcludeEvaluation($evaluationId,$PRVendorId),
                "EvaluationDetail"   => $this->GetEvaluationDetailVendor($evaluationId,$PRVendorId)
            );
            array_push($result, $prvendor);
      }
      return $result;
  }
  function GetEvaluationDetailVendor($evaluationId,$PRVendorId)
  {
    $sql = "SELECT * FROM  evaluationdetail A INNER JOIN idr B ON A.IDRId = B.IDRId WHERE EvaluationId = '$evaluationId' AND PRVendorId = '$PRVendorId'";
     $stmt = $this->conn->prepare($sql);
     $stmt->execute();
     $evaluationDetail = $stmt->fetch();

      if(isset($evaluationDetail['EvaluationDetailId'])){
       $result  = array(
                 "EvaluationDetailId" => $evaluationDetail['EvaluationDetailId'],
                 "SubTotal"           => $evaluationDetail['SubTotal'],
                 "Total"              => $evaluationDetail['Total'],
                 "Discount"           => $evaluationDetail['Discount'],
                 "PPN"                => $evaluationDetail['PPN'],
                 "GrandTotal"         => $evaluationDetail['GrandTotal'],
                 "DeliveryPoint"      => $evaluationDetail['DeliveryPoint'],
                 "PaymentTerms"       => $evaluationDetail['PaymentTerms'],
                 "PRVendorId"         => $evaluationDetail['PRVendorId'],
                 "EvaluationId"       => $evaluationDetail['EvaluationId'],
                 "IDRCode"            => $evaluationDetail['IDRCode']
            );
      }
      else
      {
       $result  = array(
                   "EvaluationDetailId" => '',
                   "SubTotal"           => '',
                   "Total"              => '',
                   "Discount"           => '',
                   "PPN"                => '',
                   "GrandTotal"         => '',
                   "DeliveryPoint"      => '',
                   "PaymentTerms"       => '',
                   "PRVendorId"         => $PRVendorId,
                   "EvaluationId"       => $evaluationId,
                   "IDRCode"            => ''
              );
      }

   return $result;
  }

  function GetIncludeEvaluation($evaluationId,$PRVendorId)
  {
      $sql = "SELECT * FROM include WHERE EvaluationId = '$evaluationId' AND PRVendorId = '$PRVendorId'";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $result = array();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
      {
            extract($row);
            if($PRVendorId == $PRVendorId)
            {
              $include=array(
                  "IncludeId"          => $IncludeId,
                  "ItemName"           => $ItemName,
                  "EvaluationId"       => $EvaluationId,
                  "PRVendorId" => $PRVendorId
              );
              array_push($result, $include);
            }
      }

      if(count($result) <  $stmt->rowCount())
      {
        $loop = $stmt->rowCount() - count($result);
          for ($i=0; $i < $loop; $i++) {
              $include=array(
                  "IncludeId"          => "",
                  "ItemName"           => "",
                  "EvaluationId"       => "",
                  "PRVendorId" => ""
              );
              array_push($result, $include);
          }
      }

      return $result;
  }

  function GetExcludeEvaluation($evaluationId,$PRVendorId)
  {
      $sql = "SELECT * FROM exclude WHERE EvaluationId = '$evaluationId' AND PRVendorId ='$PRVendorId'";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $result = array();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
      {
            extract($row);
            if($PRVendorId == $PRVendorId)
            {
              $exclude =array(
                  "ExcludeId"          => $ExcludeId,
                  "ItemName"           => $ItemName,
                  "EvaluationId"       => $EvaluationId,
                  "PRVendorId" => $PRVendorId
              );
              array_push($result, $exclude);
            }
      }

      if(count($result) <  $stmt->rowCount())
      {
        $loop = $stmt->rowCount() - count($result);
          for ($i=0; $i < $loop; $i++) {
              $exclude=array(
                  "ExcludeId"          => "",
                  "ItemName"           => "",
                  "EvaluationId"       => "",
                  "PRVendorId" => ""
              );
              array_push($result, $exclude);
          }
      }

      return $result;
  }

  function GetEvaluationDetailVendorInformation($PRId,$evaluationId)
  {
      $result = array();

      $prvendor = new PRVendor($this->conn);
      $stmtvendor = $prvendor->GetPRVendor($PRId);
      $prvendor = $stmtvendor->fetchAll(PDO::FETCH_ASSOC);

      $itemPR = new EvaluationDetail($this->conn);
      $stmt = $itemPR->GetItemPR($PRId);

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
          extract($row);
          $_vendor = array();
          foreach ($prvendor  as $vendor)
          {
            extract($vendor);
            $sql = "SELECT EvaluationDetailVendorId,Delivery,UnitPrice,TotalPrice,Description,PRVendorId,EvaluationId
                    FROM evaluationdetailvendor
                    WHERE EvaluationId = '$evaluationId'
                    AND PRItemId = '$PRItemId'
                    AND PRVendorId = '$PRVendorId'";
            $stmt1 = $this->conn->prepare($sql);
            $stmt1->execute();
            $_evaluationdetailvendor = $stmt1->fetch();
            $_review = null;
            if(isset($_evaluationdetailvendor['EvaluationDetailVendorId']))
            {
              $_review= $this->GetReviewApproval($_evaluationdetailvendor['EvaluationDetailVendorId']);
              $evaluationDetail = array(
              "EvaluationDetailVendorId" => $_evaluationdetailvendor['EvaluationDetailVendorId'],
              "Delivery"                 => $_evaluationdetailvendor['Delivery'],
              "UnitPrice"                => $_evaluationdetailvendor['UnitPrice'],
              "TotalPrice"               => $_evaluationdetailvendor['TotalPrice'],
              "Description"              => $_evaluationdetailvendor['Description'],
              "PRVendorId"               => $_evaluationdetailvendor['PRVendorId'],
              "PRItemId"                 => $PRItemId,
              "EvaluationId"             => $_evaluationdetailvendor['EvaluationId'],
              "Description"              => $_evaluationdetailvendor['Description'],
              "Reviews"                  => $_review
             );
            }
            else
            {
              $evaluationDetail = array(
              "EvaluationDetailVendorId" => '',
              "Delivery"                 => '',
              "UnitPrice"                => '',
              "TotalPrice"               => '',
              "Description"              => '',
              "PRVendorId"               => '',
              "PRItemId"                 => '',
              "EvaluationId"             => '',
              "Description"              => '',
              "Reviews"                  => null
             );
            }
            array_push($_vendor, $evaluationDetail);
          }

          $item = array(
                    'PRItemId' => $PRItemId,
                    'ItemId'   => $ItemId,
                    'ItemName' => $ItemName,
                    'Qty'      => $Qty,
                    'UoMName'  => $UoMName,
                    'Vendor'   => $_vendor
          );
          array_push($result, $item);
        }

    return $result;
  }
  function GetReviewApproval($evaluationDetailVendorId)
  {
        $sql = "SELECT 
              A.EvaluationReviewId,
              A.EvaluationDetailVendorId,
              B.UserId,
              B.FullName
              FROM evaluationreview A
              INNER JOIN user B ON A.UserId = B.UserId
              WHERE A.EvaluationDetailVendorId = '$evaluationDetailVendorId'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
        extract($row);
        $review = array(
              'EvaluationReviewId'       => $EvaluationReviewId,
              'EvaluationDetailVendorId' => $EvaluationDetailVendorId,
              'UserId'                   => $UserId,
              'FullName'                 => $FullName,
              );
        array_push($result, $review);
        }
        return $result;
  }
  function GetApprovalInformation($evaluationid)
  {
      $sql = "SELECT A.ApprovalUserId,
                     A.ApprovalId,
                     A.UserId,
                     A.IsApprove,
                     A.Status,
                     A.ApproveNumber,
                     A.ApprovalSettingId,
                     C.FullName,
                     C.Department
              FROM approvaluser A
              INNER JOIN approval B ON A.ApprovalId = B.ApprovalId
              INNER JOIN user C ON C.UserId = A.UserId
              WHERE B.EvaluationId = '$evaluationid'
              ORDER BY A.ApproveNumber ASC";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $approval_user = array();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
      {
            extract($row);
            $approval = array(
                        'ApprovalUserId'    => $ApprovalUserId,
                        'ApprovalId'        => $ApprovalId,
                        'UserId'            => $UserId,
                        'IsApprove'         => $IsApprove,
                        'Status'            => $Status,
                        'ApproveNumber'     => $ApproveNumber,
                        'ApprovalSettingId' => $ApprovalSettingId,
                        'FullName'       => $FullName,
                        'Department'        => $Department
                         );
        array_push($approval_user, $approval);
      }
    return $approval_user;

  }

  function GetEvaluationViewApproval($evaluationid,$userId)
  {
      $evaluation = $this->GetEvaluationView($evaluationid);
      if(count($evaluation) > 0)
      {
        $currentApproval = $this->GetCurrentApproval($userId);
        if(count($currentApproval) > 0){
           $evaluation = array_merge($evaluation,$currentApproval);
        }
      }

    return $evaluation;
  }

  function GetCurrentApproval($userId)
  {
      $sql = "SELECT * FROM approvaluser WHERE UserId = '$userId'";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $result = array();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
      {
            extract($row);
            $approval_user = array(
              'ApprovalUserId'    => $ApprovalUserId,
              'ApprovalId'        => $ApprovalId,
              'UserId'            => $UserId,
              'IsApprove'         => $IsApprove,
              'Status'            => $Status,
              'ApproveNumber'     => $ApproveNumber,
              'ApprovalSettingId' => $ApprovalSettingId
            );
            $result = array (
              'CurrentApproval' => $approval_user
            );
      }
    return $result;
  }
  function InsertReviwer($Evaluation)
  {
      $insert_values = array();
      $question_marks =null;
      foreach ($Evaluation->Evaluationdetailvendor as $keyDetail => $Detail)
      {
        foreach ($Detail->Vendor as $keyVendor => $Vendor)
        {
            $_evaluationreview = array(
                            "EvaluationDetailVendorId" => $Vendor->EvaluationDetailVendorId,
                            "UserId"                   => $Evaluation->CurrentApproval->UserId,
            );
            $question_marks[] = '('  . $this->Placeholders('?', sizeof($_evaluationreview)) . ')';
            $insert_values = array_merge($insert_values, array_values($_evaluationreview));
        }
      }

      $sql_evaluationreview = "INSERT INTO evaluationreview (EvaluationDetailVendorId, UserId) VALUES " .
          implode(',', $question_marks);
      $stmt = $this->conn->prepare($sql_evaluationreview);
      $stmt->execute($insert_values);
  }
  function Approval($Evaluation)
  {
        $lastapproval = $this->GetLastApprovalUser($Evaluation->CurrentApproval->ApprovalId);
        if(count($lastapproval) > 0){
            $sql_approval = "UPDATE approvaluser SET IsApprove=:IsApprove, Status=:Status WHERE ApprovalUserId=:ApprovalUserId";
            $stmt= $this->conn->prepare($sql_approval);

            $stmt->bindValue(':IsApprove', 1);
            $stmt->bindValue(':Status', $Evaluation->CurrentApproval->Status);
            $stmt->bindValue(':ApprovalUserId', $Evaluation->CurrentApproval->ApprovalUserId);

            try
            {
              $this->conn->beginTransaction();
              $stmt->execute();
              extract($lastapproval);
              if($Evaluation->CurrentApproval->ApproveNumber == $ApproveNumber)
              {
                $this->UpdateEvaluationApprovalComplate($Evaluation->CurrentApproval->ApprovalId);
              }
              $this->InsertReviwer($Evaluation);
              $this->conn->commit();
            }catch (Exception $e){
              $this->conn->rollback();
              http_response_code(404);
              throw $e;
            }
        }
        else
        {
          http_response_code(404);
        }
  }

  function GetLastApprovalUser($ApprovalId)
  {
     $sql_approvalUser = "SELECT * FROM approvaluser  WHERE ApprovalId = '$ApprovalId' ORDER BY ApproveNumber DESC LIMIT 1";
     $stmt = $this->conn->prepare($sql_approvalUser);
     $result = array();
     try {
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
      {
            extract($row);
            $result = array(
              'ApprovalUserId'    => $ApprovalUserId,
              'ApprovalId'        => $ApprovalId,
              'UserId'            => $UserId,
              'IsApprove'         => $IsApprove,
              'Status'            => $Status,
              'ApproveNumber'     => $ApproveNumber,
              'ApprovalSettingId' => $ApprovalSettingId
            );
      }
     } catch (Exception $e) {
        http_response_code(404);
        throw $e;
     }
    return $result;

  }

  function UpdateEvaluationApprovalComplate($approvalId)
  {
      $query = "SELECT
                  A.EvaluationId
                  FROM evaluation A
                  INNER JOIN approval B ON A.EvaluationId = B.EvaluationId
                  WHERE B.ApprovalId = '$approvalId'";
      $result = array();
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $evaluation = $stmt->fetch();

      $sql_evaluation = "UPDATE evaluation SET IsApprove=:IsApprove WHERE EvaluationId=:EvaluationId";
      $stmt= $this->conn->prepare($sql_evaluation);

      $stmt->bindValue(':IsApprove', '1');
      $stmt->bindValue(':EvaluationId', $evaluation['EvaluationId']);

      $stmt->execute();
  }

  function UpdatePRIsEvaluation($PRId)
  {
      $sql_pr = "UPDATE pr SET IsEvaluation=:IsEvaluation WHERE PRId=:PRId";
      $stmt= $this->conn->prepare($sql_pr);

      $stmt->bindValue(':IsEvaluation', '1');
      $stmt->bindValue(':PRId', $PRId);

      try
      {
        $stmt->execute();
      }catch (Exception $e){
        throw $e;
      }
  }

}
