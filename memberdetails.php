<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/21/2019
 * Time: 6:42 PM
 */

$pagename = "Member - Details";
require_once "header.inc.php";
try{
    //query the data
    $sql = "SELECT * FROM members WHERE ID = :ID";
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
                <tr><th>Email:</th><td>{$row['email']}</td></tr>
                <tr><th>ID:</th><td>{$row['ID']}</td></tr>
                <tr><th>Username:</th><td>{$row['username']}</td></tr>
                <tr><th>First Name:</th><td>{$row['fname']}</td></tr>
                <tr><th>Last Name:</th><td>{$row['lname']}</td></tr>
                <tr><th>Bio:</th><td>{$row['bio']}</td></tr>
                <tr><th>Address:</th><td>{$row['address']}</td></tr>
                <tr><th>City:</th><td>{$row['city']}</td></tr>
                <tr><th>State:</th><td>{$row['state']}</td></tr>
                <tr><th>Zip:</th><td>{$row['zip_code']}</td></tr>
                <tr><th>Phone Number:</th><td>{$row['phone']}</td></tr>
                <tr><th>Carrier:</th><td>{$row['carrier']}</td></tr>
                <tr><th>Last Updated:</th><td>";
    echo date("l, F j, Y", $row['updatedate']);
    echo "</td></tr>
                <tr><th>Last Updated:</th><td>";
    echo date("l, F j, Y", $row['inputdate']);
    echo "</td></tr></table></div>";
}
catch (PDOException $e)
{
    die( $e->getMessage() );
}
require_once "footer.inc.php";

?>