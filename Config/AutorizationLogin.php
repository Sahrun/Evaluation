<?php
include_once 'Autorization.php';

$aut = new Autorization();
if(!$aut->IsAutorization()){
   echo "<script>window.location.replace('http://localhost/Evaluation/pages/User/login.php');</script>";
}
?>