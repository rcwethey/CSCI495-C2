<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/20/2019
 * Time: 11:50 PM
 */

require_once "connect.inc.php";
try{
    //query the data
    $sql = "SELECT ID,title FROM content ORDER BY title";
    //executes a query
    $result = $pdo->query($sql);

    //loop through the results and display to the screen
    foreach ($result as $row){
        echo "<div id='content'><div id='box1'><a href='contentdetails.php?ID=" . $row['ID'] . "'>" . $row['title']. "</a><br /></div></div>\n";
    }
}
catch (PDOException $e)
{
    die( $e->getMessage() );
};