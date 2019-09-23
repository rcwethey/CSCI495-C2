<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 2/21/2019
 * Time: 12:39 AM
 */

//This function checks to see if someone is logged in
function checkLogin()
{
    if(!isset($_SESSION['ID']))
    {
        echo "<p class='error'>This page requires authentication.  Please log in to view details.</p>";
        require_once "footer.inc.php";
        exit();
    }
}

function checkAdmin()
{
    if($_SESSION['membertype'] == 0)
    {
        echo "<p class='error'>This page requires authentication.  Please log in to view details.</p>";
        require_once "footer.inc.php";
        exit();
    }
}

function checkDup($pdo, $sql, $userentry)
{
    try
    {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $userentry);
        $stmt->execute();
        return $stmt->rowCount();
    }
    catch(PDOException $e)
    {
        echo "<p class='error'> Error checking duplicate members!" . $e->getMessage() . "</p>";
        exit();
    }
}

function pwdCheck($pdo, $sql1, $userentry)
{
    try
    {
        $stmt = $pdo->prepare($sql1);
        $stmt->bindValue(1, $userentry);
        $stmt->execute();
        return $stmt->rowCount();
    }
    catch(PDOException $e)
    {
        echo "<p class='error'> You picked a bad password!" . $e->getMessage() . "</p>";
        exit();
    }
}

?>
