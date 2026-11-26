<?php

$HOST = "db";
$DBNAME = "ejercicios";
$USER = "root";
$PASSWORD = "root";

try {

    $CONEXION = new PDO(
        "mysql:host=$HOST;dbname=$DBNAME;charset=utf8mb4",
        $USER,
        $PASSWORD
    );

    $CONEXION->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("ERROR DE CONEXION: " . $e->getMessage());

}