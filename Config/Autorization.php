<?php
class Autorization{

function IsAutorization(){

   if(isset($_COOKIE[$this->CokkiesName()]) && !empty($_COOKIE[$this->CokkiesName()])){
     return true;
   }
   else
   {
    return false;
   }
}

function GetAutorization(){
    if($this->IsAutorization()){
       return $_COOKIE[$this->CokkiesName()];
    }
    else
    {
      return null;
    }
 }

function CokkiesName(){
    return "key_user";
}

}
?>
