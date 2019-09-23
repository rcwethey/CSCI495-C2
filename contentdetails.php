<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/20/2019
 * Time: 11:10 PM
 */

$pagename = "Content Details";
require_once "header.inc.php";
try{
    //query the data
    $sql = "SELECT * FROM content WHERE ID = :ID";
    //prepares a statement for execution
    $stmt = $pdo->prepare($sql);
    //binds the actual value of $_GET['ID'] to
    $stmt->bindValue(':ID', $_GET['ID']);
    //executes a prepared statement
    $stmt->execute();
    //fetches the next row from a result set / returns an array
    //default:  array indexed by both column name and 0-indexed column number
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //display to the screen
        echo "<div id='content'><table id='box1'>
                <tr><th>ID:</th><td>{$row['ID']}</td></tr>
                <tr><th>Title:</th><td>{$row['title']}</td></tr>
                <tr><th>Details:</th><td>{$row['details']}</td></tr>
                <tr><th>Last Updated:</th><td>";
                echo date("l, F j, Y", $row['inputdate']);
                echo "</td></tr>
              </table></div>";
}
catch (PDOException $e)
{
    die( $e->getMessage() );
}
require_once "footer.inc.php";









