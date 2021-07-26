<?php
//Buscar como agregar imágenes

include "config.php";
include "utils.php";
$dbConn =  connect($db);
/*
  listar todos los posts o solo uno
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['id_smartphones']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * FROM smartphones_category where id_smartphones=:id_smartphones");
      $sql->bindValue(':id_smartphones', $_GET['id_smartphones']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
    else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM smartphones_category");
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
    $sql = "INSERT INTO smartphones_category
          (nombre, marca, tamanio, modelo, ram, almacenamiento, camaras, procesador, sistema_operativo, bateria, precio)
          VALUES
          (:nombre, :marca, :tamanio, :modelo,  :ram, :almacenamiento, :camaras, :procesador, :sistema_operativo, :bateria, :precio)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    if($postId)
    {
      $input['id_smartphones'] = $postId;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
	 }
}
//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$id = $_GET['id_smartphones'];
  $statement = $dbConn->prepare("DELETE FROM smartphones_category where id_smartphones=:id_smartphones");
  $statement->bindValue(':id_smartphones', $id);
  $statement->execute();
	header("HTTP/1.1 200 OK");
	exit();
}
//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['id_smartphones'];
    $fields = getParams($input);
    $sql = "
          UPDATE smartphones_category
          SET $fields
          WHERE id_smartphones='$postId'
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