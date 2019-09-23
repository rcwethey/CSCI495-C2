<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/21/2019
 * Time: 3:11 PM
 */



require_once "connect.inc.php";

header("Content-Transfer-Encoding: ascii");
header( "Content-Disposition: attachment: filename-report.csv ");
header("Content-Type: text/comma-separated-values");

$sql ="SELECT * FROM members";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

?>Username    and     Email
<?php
foreach ($result as $row)
    {
        //cleans up data for /n /r , using str_replace()
        //[array]
        //$cleancomments = str_replace(['"' , ',' ,"\r", "\n"], '', $row['comments']);
        echo " " . $row['username'] . " " . $row['email'] . "\n";
    }
?>