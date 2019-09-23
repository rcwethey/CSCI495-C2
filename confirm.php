<?php
$pagename = "Authentication Confirmation";  //pagename var is used in the header
require_once "header.inc.php";

if($_GET['state']==1)
{
    echo "<p id='content' id='box1'>You have been logged out.  Please <a href='login.php'>log in</a> again to view restricted content.<p>";
}
elseif($_GET['state']==2)
{
    echo "<p>Welcome back, <b>" . $_SESSION['username'] . "</b>!</p>";
}
elseif($_GET['state']==3)
{
    echo "<p id='content' id='box1'>Your password has been changed and you have been logged out.  Please <a href='login.php'>log in</a> again to view restricted content.<p>";
}
else
{
    echo "<p id='content' id='box1'>Please continue by choosing an item from the menu.</p>";
}

require_once "footer.inc.php";










