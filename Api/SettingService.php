<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../Config/Connection.php';
include_once 'Object/Navigation.php';
include_once 'Object/Group.php';
include_once '../Config/Autorization.php';

$database = new Connection();
$db = $database->getConnection();
$navigation = new Navigation($db);
$group = new Group($db);
$aut = new Autorization();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) 
{
  case 'GET':
    if(isset($_GET['route']) && !empty($_GET['route']))
    {
      if($_GET['route'] == "getnavigationbyid" && (isset($_GET['id']) && !empty($_GET['id'])))
      {
        GetNavigationById($navigation,$_GET['id']);
      }
      else if($_GET['route'] == "getnavigation")
      {
        GetNavigation($navigation);
      }
      else if($_GET['route'] == "getgroup")
      {
        GetGroup($group);
      }
      else
      {
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
        if($_GET['route'] == "navigationsave")
        {
            SaveNavigation($navigation,$_POST);
        }
   }
   else
   {
       RequestFail();
   }
    break;
  case 'DELETE': 
    break;
}

function SaveNavigation($navigation,$post)
{
    if($post->NavigationId == null){
        $navigation->SaveNavigation($post);
      }else
      {
        $navigation->UpdateNavigation($post);
      }
}
function GetNavigation($navigation)
{ 
    $result = $navigation->GetNavigation();
    http_response_code(200);
    echo json_encode($result);
}
function GetNavigationById($navigation,$navigationId)
{ 
    $result = $navigation->GetNavigationById($navigationId);
    http_response_code(200);
    echo json_encode($result);
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