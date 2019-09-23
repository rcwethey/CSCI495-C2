<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/18/2019
 * Time: 11:47 PM
 */

?>
<ul>
    <?php
    if(!isset($_SESSION['ID']))
    {
        echo ($currentfile == "index.php") ? "<li> Home</li>" : "<li><a href='index.php'> Home</a></li>";
        echo ($currentfile == "memberinsert.php") ? "<li> Register</li>" : "<li><a href='memberinsert.php'> Register</a></li>";
        echo "<li id='log'><a href='login.php'>Log In</a></li>";

        //echo ($currentfile == "filemanage.php") ? "<li> Manage Files</li>" : "<li><a href='filemanage.php'> Manage Files</a></li>";
    }

    if(isset($_SESSION['ID']))
    {
        echo ($currentfile == "index.php") ? "<li> Home</li>" : "<li><a href='index.php'> Home</a></li>";
        echo ($currentfile == "membermanage.php") ? "<li> Manage Members</li>" : "<li><a href='membermanage.php'> Manage Members</a></li>";
        //echo ($currentfile == "contentmanage.php") ? "<li> Manage Content</li>" : "<li><a href='contentmanage.php'> Manage Content</a></li>";
        echo ($currentfile == "profile.php") ? "<li> Profile</li>" : "<li><a href='profile.php'> Profile</a></li>";
        //echo ($currentfile == "contentmanage.php") ? "<li> Manage Content</li>" : "<li><a href='contentmanage.php'> Manage Content</a></li>";
        echo "<li id ='log' id='content' id='box1'><a href='logout.php'>Log Out</a></li>";
        echo "Welcome back, " . $_SESSION['username'];
    }
    ?>
</ul>