<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/20/2019
 * Time: 10:50 PM
 */

/* CREATE A CONNECTION TO THE SERVER */
try{
    $connString = "mysql:host=localhost;dbname=csci409rcwethey";
    $user = "csci409rcwethey";
    $pass = "Northmen16!";
    $pdo = new PDO($connString,$user,$pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    die( $e->getMessage() );
}

