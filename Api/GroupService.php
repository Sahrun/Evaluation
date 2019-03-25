<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../Config/Connection.php';
include_once '../Config/Autorization.php';
include_once 'Object/Group.php';

$database = new Connection();
$db = $database->getConnection();
$group = new Group($db);
$autConfig = new Autorization();

if($autConfig->IsAutorization())
{
  $method = $_SERVER['REQUEST_METHOD'];

  switch ($method)
  {
    case 'GET':
      if(isset($_GET['route']))
      {
        if($_GET['route'] == 'getgroup')
        {
          GetGroup($group);
        }
      }else{
        RequestFail();
      }
      break;
    case 'PUT':
      break;
    case 'POST':
      break;
    case 'DELETE':
      break;
  }
}
else
{
RequestFail();
}

function GetGroup($group)
{
    $result = $group->GetGroup();
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
