<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../Config/Connection.php';
include_once 'Object/PR.php';
include_once '../Config/Autorization.php';

$database = new Connection();
$db = $database->getConnection();
$PR = new PR($db);
$aut = new Autorization();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) 
{
  case 'GET':
    if(isset($_GET['route']))
    {
      if($_GET['route'] == "getpr"){
        GetPR($PR);
      }else if($_GET['route'] == "getprdetail")
      {
        if(isset($_GET['id']) && !empty($_GET['id']))
        {
          GetPRById($PR,$_GET['id']);
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
     SavePR($PR,$_POST);
   }
    break;
  case 'DELETE': 
    break;
}

function SavePR($PR,$pr){
    $PR->InsertPR($pr);
}

function GetPR($PR)
{ 
  $result = $PR->GetPR();
  http_response_code(200);
  echo json_encode($result);
}

function GetPRById($PR,$PRId){
  $result = $PR->GetPRById($PRId);
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