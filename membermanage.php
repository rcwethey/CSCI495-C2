<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/21/2019
 * Time: 3:34 PM
 */


$pagename = "Manage Members";
require_once "header.inc.php";
try {

    //query the data
    $sql = "SELECT * FROM members";
    //executes a query.
    $result = $pdo->query($sql);
    ?>
    <?php
    if ((isset($_SESSION['ID'])) && ($_SESSION['membertype'] == 1)){
        echo "<div id='content'><div id='box1'><p><a href='memberupdate.php?ID=" . $_SESSION['ID'] . "'> Update My Membership Info </a>" . " | " . "<a href='memberpwd.php'>Change Password</a>" . " | " . "<a href='membercsv.php'>Member Export</a></p></div></div>";

        echo "<div id='content'><table id='box1'><tr><th>ID</th><th>Username</th><th>Joined</th><th>Last Updated</th><th>Member Type</th><th>Admin Options</th>";
        //loop through the results and display to the screen
        foreach ($result as $row) {
            echo "<tr><td>" . $row['ID'] . "</td> <td><a href='memberupdate.php?ID=" . $row['ID'] . "'>" . $row['username'] . "</a></td><td> ";
            echo date("l, F j, Y", $row['inputdate']);
            echo "</td><td>";
            echo date("l, F j, Y", $row['updatedate']);
            echo "</td>";

            if ($row['membertype'] == 0) {
                echo "<td><p>User</p></td>";
            } else {
                echo "<td><p><b>ADMIN</b></p></td>";
            }
            if($row['ID'] !== $_SESSION['ID']) {
                echo "<td><a href='membertype.php?ID=" . $row['ID'] . "&Memtype=" . $row['membertype'] . "'>Change Access</a></td>";
            }
            echo "</tr>\n";
        }
        echo "</table></div>";
    }
}
catch (PDOException $e)
{
    die( $e->getMessage());
}

try{
    //query the data
    $sql = "SELECT * FROM members";
    //executes a query.
    $result = $pdo->query($sql);
?>
<?php
    if((isset($_SESSION['ID'])) && ($_SESSION['membertype'] == 0)){
        echo "<div id='content'><div id='box1'><p><a href='memberupdate.php?ID=" . $_SESSION['ID'] . "'>Update My Membership Info</a>" . " | " . "<a href='memberpwd.php'>Change Password</a></p></div></div>";

        echo "<div id='content'><table id='box1'><tr><th>ID</th><th>Username</th><th>Joined</th><th>Last Updated</th>";
        //loop through the results and display to the screen
        foreach ($result as $row) {
            echo "<tr><td>" . $row['ID'] . "</td> <td><a href='memberdetails.php?ID=" . $row['ID'] . "'>" . $row['username'] . "</a></td><td> ";
            echo date("l, F j, Y", $row['inputdate']);
            echo "</td><td>";
            echo date("l, F j, Y", $row['updatedate']);
            echo "</td></tr>\n";
        }
        echo "</table></div>";
    }
}
catch (PDOException $e)
{
    die( $e->getMessage() );
}
require_once "footer.inc.php";








