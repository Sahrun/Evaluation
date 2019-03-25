<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../Config/Connection.php';
include_once 'Object/Item.php';
include_once '../Config/Autorization.php';

$database = new Connection();
$db = $database->getConnection();
$Item = new Item($db);
$aut = new Autorization();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) 
{
  case 'GET':
    if(isset($_GET['route']))
    {
      if($_GET['route'] == "getitem"){
        GetItem($Item);
      }else if($_GET['route'] == "edit")
      {
        if(isset($_GET['id']) && !empty($_GET['id']))
        {
          GetItemById($Item,$_GET['id']);
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
     SaveItem($Item,$_POST);
   }
    break;
  case 'DELETE': 
    break;
}

function SaveItem($Item,$item){
    if($item->ItemId == null){
      $Item->SaveItem($item);
    }else
    {
      $Item->UpdateItem($item);
    }
}

function GetItem($Item)
{ 
  $result = $Item->GetItem();
  http_response_code(200);
  echo json_encode($result);
}

function GetItemById($Item,$ItemId){
  $result = $Item->GetItemById($ItemId);
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