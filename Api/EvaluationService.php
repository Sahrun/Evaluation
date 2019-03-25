<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../Config/Connection.php';
include_once 'Object/Evaluation.php';
include_once '../Config/Autorization.php';

$database = new Connection();
$db = $database->getConnection();
$evaluation = new Evaluation($db);
$aut = new Autorization();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) 
{
  case 'GET':
    if(isset($_GET['route']))
    {
      if($_GET['route'] == "getevaluations"){
         GetEvaluations($evaluation,$aut);
      }
      else if($_GET['route'] == "getevaluationview")
      {
        if(isset($_GET['evaluationId']) && !empty($_GET['evaluationId']))
        {
          GetEvaluationView($evaluation,$_GET['evaluationId']);
        }
        else
        {
          RequestFail();
        }
      }
      else if($_GET['route'] == "getevaluationapproval")
      {
        if(isset($_GET['evaluationId']) && !empty($_GET['evaluationId']))
        {
           GetEvaluationViewApproval($evaluation,$_GET['evaluationId'],$aut);
        }
      }
      else if($_GET['route'] == "getevalvendordet")
      {
        if(isset($_GET['PRId']) && !empty($_GET['PRId']))
        {
          GetEvalVendorDet($evaluation,$_GET['PRId']);
        }
        else 
        {
          RequestFail();
        }
      }
      else if($_GET['route'] == "getevalitemdet")
      {
        if(isset($_GET['PRId']) && !empty($_GET['PRId']))
        {
          GetEvalItemDet($evaluation,$_GET['PRId']);
        }
        else 
        {
          RequestFail();
        }
      }
      else{
         RequestFail();
      }
    }else{
      RequestFail();
    }
    break;
  case 'PUT':
    break;
  case 'POST':
   $_POST = json_decode(file_get_contents("php://input"));
   if(isset($_GET['route']) && !empty($_GET['route']))
   {
     if($_GET['route'] == "approve"){
        Approval($evaluation,$_POST);
     }
     else
     {
      RequestFail();
     }
   }
   else
   {
     SaveEvaluation($evaluation,$_POST,$aut);
   }
    break;
  case 'DELETE': 
    break;
}


function GetEvaluations($evaluation,$aut)
{

    $aut = $aut->GetAutorization();
    if(!$aut)
    {
      RequestFail();
    }
    else
    {
      $result = $evaluation->GetEvaluations($aut);
      http_response_code(200);
      echo json_encode($result);
    }
}


function GetEvaluationView($evaluation,$evaluationId)
{
  $result = $evaluation->GetEvaluationView($evaluationId);
  if(count($result) > 0){
    http_response_code(200);
    echo json_encode($result); 
  }
  else
  {
   RequestFail();
  }
}

function GetEvaluationViewApproval($evaluation,$evaluationId,$aut)
{
    $aut = $aut->GetAutorization();
    if(!$aut)
    {
      RequestFail();
    }
    else
    {
        $result = $evaluation->GetEvaluationViewApproval($evaluationId,$aut);
        if(count($result) > 0){
          http_response_code(200);
          echo json_encode($result); 
        }
        else
        {
         RequestFail();
        }
    }
}

function SaveEvaluation($evaluation,$POST,$aut){
    $aut = $aut->GetAutorization();
    if(!$aut)
    {
      RequestFail();
    }
    else
    {
      $evaluation->InsertEvaluation($POST,$aut);
    }
}

function Approval($evaluation,$POST){
      $evaluation->Approval($POST);
}

function GetEvalVendorDet($Evaluation,$PRId)
{
  $result =  $Evaluation->GetEvalVendorDet($PRId);
  http_response_code(200);
  echo json_encode($result);
}
function GetEvalItemDet($Evaluation,$PRId)
{
  $result =  $Evaluation->GetEvalItemDet($PRId);
  http_response_code(200);
  echo json_encode($result);
}

function RequestFail(){
  http_response_code(404);
      echo json_encode(
            array("message" => "Request Fail.")
        );
}
?>