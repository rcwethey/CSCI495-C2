<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/19/2019
 * Time: 10:47 AM
 */


$pagename = "File - Upload";
include_once "header.inc.php";


$showform = 1;  // show form is true
$errmsg = 0;
$errfile = "";

if($_SERVER['REQUEST_METHOD']== $_POST)
{
    print_r($_FILES);
    echo "<br>";

    //echo $_FILES['myfile']['name'] . "<br>";
    //echo $_FILES['myfile']['type'] . "<br>";
    //echo $_FILES['myfile']['size'] . "<br>";
    //echo $_FILES['myfile']['tmp_name'] . "<br>";
    //echo $_FILES['myfile']['error'] . "<br>";

    //Control Statements

    if($errmsg ==1)
    {
        echo "<p id='content' id='box1' class= 'error'>There are errors. Please makes corrections and resubmit</p>";
    }
    else
    {
        $showform = 0;
    }
}

if($showform == 1)
{
    ?>
    <form name="fileupload" id="fileupload" method="post" action="filetest.php" enctype="multipart/form-data">
        <table id='content' id='box1'>
            <tr><th><label for="image">Upload Your File:</label><span class="error">*</span></th>
                <td><input name="image" id="image" type="file" />
                    <span class="error"><?php if(isset($errfile)){echo $errfile;}?></span></td>
            </tr>
            <tr><th><label for="submit">Submit:</label></th>
                <td><input type="submit" name="submit" id="submit" value="UPLOAD"/></td>
            </tr>

        </table>
    </form>
    <?php
}//end showform
include_once "footer.inc.php";
?>