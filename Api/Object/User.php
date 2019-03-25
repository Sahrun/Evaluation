<?php
class User{

    // database connection and table name
    private $conn;
    private $table_name = "user";

    // object properties
    public $UserId;
    public $UserName;
    public $DisplayName;
    public $GroupId;
    public $Password;
    // constructor with $db as database connection

    public function __construct($db){
        $this->conn = $db;
    }

  function GetAutUser($user_name,$password){
    $query = "SELECT * FROM ".$this->table_name." WHERE UserName ='$user_name'";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $user = $stmt->fetch();
    if(isset($user['UserId']) && !empty($user['UserId'])){
      if(password_verify($password,$user['Password'])){
        return array(
            'UserId'          => $user['UserId'],
            'FullName'        => $user['FullName'],
            'GroupId'         => $user['GroupId'],
            'HasConfirmation' => $user['HasConfirmation']
        );
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }

  function GetNavigation($userId)
  {
    $result = array();
    $query = "SELECT NavigationId,NavigationName,Url,A.GroupId,Icon FROM `navigation`  A
              INNER JOIN `group` B ON A.GroupId = B.GroupId
              INNER JOIN ".$this->table_name." C ON C.GroupId = B.GroupId
              WHERE C.UserId = '$userId' ORDER BY A.Order ASC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
              extract($row);
              $_navigation = array(
                                "NavigationId"    => $NavigationId,
                                "NavigationName"  => $NavigationName,
                                "Url"             => $Url,
                                "GroupId"         => $GroupId,
                                "Icon"            => $Icon,
              );
             array_push($result, $_navigation);
    }
    return $result;
  }
  function GetUser()
  {
    $result = array();
    $query = "SELECT A.UserId
                     ,A.FullName
                     ,A.GroupId
                     ,B.GroupName
                     ,A.Department
                     ,A.Status
              FROM ".$this->table_name." A INNER JOIN `group` B ON A.GroupId = B.GroupId";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
              extract($row);
              $_user = array(
                                "UserId"     => $UserId,
                                "FullName"   => $FullName,
                                "GroupId"    => $GroupId,
                                "GroupName"  => $GroupName,
                                "Department" => $Department,
                                "Status"     => $Status
              );
             array_push($result, $_user);
    }
    return $result;
  }
  function CreateUser($User)
  {
    $_user = [
              'UserName'        => $User->UserName,
              'FullName'        => $User->FullName,
              'GroupId'         => $User->GroupId,
              'Password'        => password_hash("password_default", PASSWORD_DEFAULT),
              'Department'      => $User->Department,
              'Status'          => 'Active',
              'HasConfirmation' => '0'
            ];
    $sql_user = "INSERT INTO ".$this->table_name." (UserName, FullName, GroupId, Password, Department, Status, HasConfirmation) VALUES (:UserName, :FullName, :GroupId, :Password, :Department, :Status, :HasConfirmation)";
    $stmt= $this->conn->prepare($sql_user);
    try
    {
      $stmt->execute($_user);
    }catch (Exception $e){
      http_response_code(404);
      throw $e;
    }
  }
  function GetUserLogin($userId)
  {
      $query = "SELECT * FROM ".$this->table_name." WHERE UserId ='$userId'";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $user = $stmt->fetch();
      if(isset($user['UserId']) && !empty($user['UserId'])){
          return array(
              'UserId'          => $user['UserId'],
              'FullName'        => $user['FullName'],
              'GroupId'         => $user['GroupId'],
              'HasConfirmation' => $user['HasConfirmation']
          );
      }
      else
      {
        return false;
      }
  }
  function Register($post)
  {
    $AutUser = $this->GetAutUser($post->UserName,$post->Password);
    if(!$AutUser)
    {
      http_response_code(404);
    }
    else
    {
        $sql_user = "UPDATE ".$this->table_name." SET UserName=:UserName, Password=:Password, HasConfirmation=:HasConfirmation WHERE UserId=:UserId";
        $stmt= $this->conn->prepare($sql_user);

        $stmt->bindValue(':UserName', $post->UserName);
        $stmt->bindValue(':Password', password_hash($post->ConfirmPassword, PASSWORD_DEFAULT));
        $stmt->bindValue(':HasConfirmation', '1');
        $stmt->bindValue(':UserId', $AutUser['UserId']);
      try
      {
        $stmt->execute();
        return true;
      }catch (Exception $e){
        http_response_code(404);
        throw $e;
      }
    }
  }
}
