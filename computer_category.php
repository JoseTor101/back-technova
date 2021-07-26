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
    if (isset($_GET['id_computer']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * FROM computer_category where id_computer=:id_computer");
      $sql->bindValue(':id_computer', $_GET['id_computer']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
    else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM computer_category");
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
    $sql = "INSERT INTO computer_category
          (nombre, marca, tamanio, modelo, ram, almacenamiento, tipo_disco, procesador, sistema_operativo, precio)
          VALUES
          (:nombre, :marca, :tamanio, :modelo,  :ram, :almacenamiento, :tipo_disco, :procesador, :sistema_operativo, :precio)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    if($postId)
    {
      $input['id_computer'] = $postId;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
	 }
}
//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$id = $_GET['id_computer'];
  $statement = $dbConn->prepare("DELETE FROM computer_category where id_computer=:id_computer");
  $statement->bindValue(':id_computer', $id);
  $statement->execute();
	header("HTTP/1.1 200 OK");
	exit();
}
//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['id_computer'];
    $fields = getParams($input);
    $sql = "
          UPDATE computer_category
          SET $fields
          WHERE id_computer='$postId'
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