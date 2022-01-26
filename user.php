<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);
/*
  listar todos los posts o solo uno
 */
/*if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['id']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * FROM user where id=:id");
      $sql->bindValue(':id', $_GET['id']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
    else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM user");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
	}
}*/


if ($_SERVER['REQUEST_METHOD'] == 'GET'){

    if(isset($_GET['user_name']) && isset($_GET['user_password'])){
      $hello = "SELECT * FROM user WHERE nombre_de_usuario=".$_GET['user_name'] . " AND  userPassword= " . $_GET['user_password'];
      $sql = $dbConn->prepare( $hello);
      //$sql-> bindAllValues(); 
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetch(PDO::FETCH_ASSOC) );
      exit();       
    }
    else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM user");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
    }
}

// Crear un nuevo post
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    
    $sql = "INSERT INTO user
          (nombres, apellidos, correo_electronico, direccion, nombre_de_usuario, userPassword)
          VALUES
          (:nombres, :apellidos, :correo_electronico, :direccion, :nombre_de_usuario, :userPassword)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    if($postId)
    {
      $input['id'] = $postId;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
	 }
}
//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$id = $_GET['id'];
  $statement = $dbConn->prepare("DELETE FROM user where id=:id");
  $statement->bindValue(':id', $id);
  $statement->execute();
	header("HTTP/1.1 200 OK");
	exit();
}
//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['id'];
    $fields = getParams($input);
    $sql = "
          UPDATE user
          SET $fields
          WHERE id='$postId'
           ";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    header("HTTP/1.1 200 OK");
    echo json_encode(  $sql );
    exit();
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>