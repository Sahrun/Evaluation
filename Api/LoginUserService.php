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

$method = $_SERVER['REQUEST_METHOD'];

switch ($method)
{
  case 'GET':
    if(isset($_GET['route']))
    {
      if($_GET['route'] == 'logout')
      {
        LogOut($autConfig);
      }
      else if($_GET['route'] == 'getuserlogin')
      {
        GetUserLogin($user);
      }
    }else{
      RequestFail();
    }
    break;
  case 'PUT':
    break;
  case 'POST':
    $_POST = json_decode(file_get_contents("php://input"));
    Login($_POST,$user,$autConfig);
    break;
  case 'DELETE':
    break;
}

function Login($POST,$user,$autConfig){
    if((!isset($POST->UserName) && empty($POST->UserName)) || (!isset($POST->Password) && empty($POST->Password)))
    {
        RequestFail();
    }
    else
    {
        $resut_user = $user->GetAutUser($POST->UserName,$POST->Password);
        if(!$resut_user){
            RequestFail();
        }
        else
        {
          setcookie($autConfig->CokkiesName(),$resut_user['UserId'], time() + (86400 * 30), "/");
          http_response_code(200);
          echo json_encode($resut_user);
        }
    }
}

function LogOut($autConfig){
  setcookie("key_user", '', time() - 60*60*24,"/");
  unset($_COOKIE["key_user"]);
  http_response_code(200);
}

function GetUserLogin($user)
{
  $userId = $_COOKIE["key_user"];
  $resut_user = null;
   if($userId !== null){
     $resut_user = $user->GetUserLogin($userId);
     if(!$resut_user){
         RequestFail();
    }
  }
   http_response_code(200);
   echo json_encode($resut_user);
}

function RequestFail(){
  http_response_code(404);
      echo json_encode(
            array("message" => "Request Fail.")
        );
}
?>
