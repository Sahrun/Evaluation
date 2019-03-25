<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../Config/Connection.php';
include_once 'Object/Vendor.php';
include_once '../Config/Autorization.php';

$database = new Connection();
$db = $database->getConnection();
$Vendor = new Vendor($db);
$aut = new Autorization();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) 
{
  case 'GET':
    if(isset($_GET['route']))
    {
      if($_GET['route'] == "getvendor"){
        GetVendor($Vendor);
      }else if($_GET['route'] == "edit")
      {
        if(isset($_GET['id']) && !empty($_GET['id']))
        {
          GetVendorById($Vendor,$_GET['id']);
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
     SaveVendor($Vendor,$_POST);
   }
    break;
  case 'DELETE': 
    break;
}

function SaveVendor($Vendor,$vendor){
    if($vendor->VendorId == null){
      $Vendor->SaveVendor($vendor);
    }else
    {
      $Vendor->UpdateVendor($vendor);
    }
}

function GetVendor($Vendor)
{ 
  $result = $Vendor->GetVendor();
  http_response_code(200);
  echo json_encode($result);
}

function GetVendorById($Vendor,$VendorId){
  $result = $Vendor->GetVendorById($VendorId);
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