<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../Config/Connection.php';
include_once '../Config/Autorization.php';
include_once 'Object/User.php';

$database = new Connection();
$db = $database->getConnection();
$user = new User($db);
$autConfig = new Autorization();

if($autConfig->IsAutorization())
{
  $method = $_SERVER['REQUEST_METHOD'];

  switch ($method)
  {
    case 'GET':
      if(isset($_GET['route']))
      {
        if($_GET['route'] == 'getnavigation')
        {
          GetNavigation($autConfig,$user);
        }
        else if($_GET['route']  == 'getuser')
        {
          GetUser($user);
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
          if($_GET['route'] == "register")
          {
              Register($user,$_POST);
          }
          else
          {
            RequestFail();
          }
        }
        else
        {
          CreateUser($user,$_POST);
        }
      break;
    case 'DELETE':
      break;
  }
}
else
{
RequestFail();
}

function GetNavigation($aut,$user)
{
      $autUser = $aut->GetAutorization();
      if($autUser ==  null){
          RequestFail();
      }
      else
      {
        $navigation = $user->GetNavigation($autUser);
          http_response_code(200);
          echo json_encode($navigation);
      }
}

function GetUser($user)
{
    $result = $user->GetUser();
    http_response_code(200);
    echo json_encode($result);
}

function CreateUser($user,$POST)
{
    $user->CreateUser($POST);
    http_response_code(200);
}

function Register($user,$post)
{
  if($user->Register($post))
  {
      http_response_code(200);
  }
}

function RequestFail(){
  http_response_code(404);
      echo json_encode(
            array("message" => "Request Fail.")
        );
}
?>
