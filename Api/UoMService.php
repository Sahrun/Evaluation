<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../Config/Connection.php';
include_once 'Object/UoM.php';
include_once '../Config/Autorization.php';

$database = new Connection();
$db = $database->getConnection();
$UoM = new UoM($db);
$aut = new Autorization();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) 
{
  case 'GET':
    if(isset($_GET['route']))
    {
      if($_GET['route'] == "getuom"){
        GetUoM($UoM);
      }else if($_GET['route'] == "edit")
      {
        if(isset($_GET['id']) && !empty($_GET['id']))
        {
          GetUoMById($UoM,$_GET['id']);
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
     SaveUoM($UoM,$_POST);
   }
    break;
  case 'DELETE': 
    break;
}

function SaveUoM($UoM,$uom){
    if($uom->UoMId == null){
      $UoM->SaveUoM($uom);
    }else
    {
      $UoM->UpdateUoM($uom);
    }
}

function GetUoM($UoM)
{ 
  $result = $UoM->GetUoM();
  http_response_code(200);
  echo json_encode($result);
}

function GetUoMById($UoM,$uomId){
  $result = $UoM->GetUoMById($uomId);
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