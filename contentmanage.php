<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/21/2019
 * Time: 12:25 AM
 */

$pagename = "Manage Content";
require_once "header.inc.php";
checkLogin();
try{
    //query the data
    $sql = "SELECT * FROM content";
    //executes a query.
    $result = $pdo->query($sql);

    ?>
    <?php
    echo "<div id='content'><div id='box1'><p><b><a href='contentinsert.php?ID=" . $_SESSION['ID'] . "'>Content - Insert</a></b></p></div></div>";
    echo "<div id='content'><table id='box1'><tr><th>Options</th><th>Title</th></tr>";
    //loop through the results and display to the screen
    foreach ($result as $row){
        echo "<tr><td><a href='contentdetails.php?ID=" . $row['ID'] . "'>VIEW</a> | <a href='contentdelete.php?ID=" . $row['ID'] . "&T=" . $row['title'] . "'>DELETE</a> | <a href='contentupdate.php?ID=" . $row['ID'] . "'>UPDATE</a></td>";
        echo "<td>". $row['title']. "</td></tr>\n";
    }
    echo "</table></div>";
}
catch (PDOException $e)
{
    die( $e->getMessage() );
}
require_once "footer.inc.php";









