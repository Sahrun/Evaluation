<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../Config/Connection.php';
include_once 'Object/IDR.php';
include_once '../Config/Autorization.php';

$database = new Connection();
$db = $database->getConnection();
$IDR = new IDR($db);
$aut = new Autorization();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) 
{
  case 'GET':
    if(isset($_GET['route']))
    {
      if($_GET['route'] == "getidr"){
        GetIDR($IDR);
      }else if($_GET['route'] == "edit")
      {
        if(isset($_GET['id']) && !empty($_GET['id']))
        {
          GetIDRById($IDR,$_GET['id']);
        }
        else
        {
          RequestFail();
        }
      }else{
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
   }
   else
   {
     SaveIDR($IDR,$_POST);
   }
    break;
  case 'DELETE': 
    break;
}

function SaveIDR($IDR,$idr){
    if($idr->IDRId == null){
      $IDR->SaveIDR($idr);
    }else
    {
      $IDR->UpdateIDR($idr);
    }
}

function GetIDR($IDR)
{ 
  $result = $IDR->GetIDR();
  http_response_code(200);
  echo json_encode($result);
}

function GetIDRById($IDR,$IDRId){
  $result = $IDR->GetIDRById($IDRId);
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