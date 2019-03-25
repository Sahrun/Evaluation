<?php
class Navigation{
 
    // database connection and table name
    private $conn;
    private $table_name = "navigation";
 
    // object properties
    public $NavigationId;
    public $NavigationName;
    public $Url;
    public $GroupId;
    public $Icon;
    public $Order;
    // constructor with $db as database connection

    public function __construct($db){
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

  function GetNavigation(){
      $result = array(); 
      $query = "SELECT A.NavigationId
                      ,A.NavigationName
                      ,A.Url
                      ,B.GroupName
                      ,A.Icon
                      ,A.Order
                FROM ".$this->table_name." 
                A INNER JOIN `group` B ON A.GroupId = B.GroupId 
                ORDER BY A.GroupId,A.`Order` ASC";
   
      $stmt = $this->conn->prepare($query);
      try
      {
       $stmt->execute();
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            $_navigation = array(
                      "NavigationId"    => $NavigationId,
                      "NavigationName"  => $NavigationName,
                      "Url"             => $Url,
                      "GroupName"       => $GroupName,
                      "Icon"            => $Icon,
                      "Order"           => $Order
            );
            array_push($result, $_navigation);
        }
      }catch (Exception $e){
            http_response_code(404);
            throw $e;
      }
      return $result;
  }
  function GetNavigationById($NavigationId)
  {
        $result = array();
        $query = "SELECT * FROM ".$this->table_name." WHERE NavigationId ='$NavigationId'";

        $stmt = $this->conn->prepare($query); 
        try
        {
          $stmt->execute();
          $idr = $stmt->fetch();
          extract($idr);
          $result = array(
                    "NavigationId"    => $NavigationId,
                    "NavigationName"  => $NavigationName,
                    "Url"             => $Url,
                    "GroupId"         => $GroupId,
                    "Icon"            => $Icon,
                    "Order"           => $Order
          );
        }catch (Exception $e){
          http_response_code(404);
          throw $e;
        }
        return $result;
  }
  function SaveNavigation($post){
    $_navigation = [
              'NavigationName'     => $post->NavigationName,
              'Url'                => $post->Url,
              'GroupId'            => $post->GroupId,
              'Icon'               => $post->Icon,
              'Order'              => $post->Order
            ];
    $sql_navigation = "INSERT INTO ".$this->table_name." (NavigationName, Url, GroupId, Icon, `Order`) VALUES (:NavigationName, :Url, :GroupId, :Icon, :Order)";
    $stmt= $this->conn->prepare($sql_navigation);
    try
    {
      $stmt->execute($_navigation);
    }catch (Exception $e){
      http_response_code(404);
      throw $e;
    }
}

function UpdateNavigation($navigation)
{
    $sql_navigation = "UPDATE ".$this->table_name." SET NavigationName=:NavigationName, Url=:Url , GroupId=:GroupId, Icon=:Icon, `Order`=:Order WHERE NavigationId=:NavigationId";
    $stmt= $this->conn->prepare($sql_navigation);

    $stmt->bindValue(':NavigationName', $navigation->NavigationName);
    $stmt->bindValue(':Url', $navigation->Url);
    $stmt->bindValue(':GroupId', $navigation->GroupId);
    $stmt->bindValue(':Icon', $navigation->Icon);
    $stmt->bindValue(':Order', $navigation->Order);
    $stmt->bindValue(':NavigationId', $navigation->NavigationId);

    try
    {
      $stmt->execute();
    }catch (Exception $e){
      http_response_code(404);
      throw $e;
    }
}
}