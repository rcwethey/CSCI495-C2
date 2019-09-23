<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/19/2019
 * Time: 11:12 AM
 */


$pagename = "File - Upload";
include_once "header.inc.php";
CheckLogin();

//SET INITIAL VARIABLES
$showform = 1;  // show form is true
$errmsg = 0;
$errfile = "";
$pathparts;
$userfile;
$extension;
$finalfile;
$workingfile;

if($_SERVER['REQUEST_METHOD'] == "POST")
{
   // print_r($_FILES);
    /* ***********************************************************************
         * FILE - ERROR CHECKING
         * Check for errors with the file as well as duplicates
     * ***********************************************************************
    */

    if($_FILES['image']['error'] == 0)
    {
        //echo $_FILES['image']['name'] . "<br>";
        if ($_FILES['image']['type'] != "image/jepg" || "image/png"){
            $errmsg = 1;
            $errfile = "This file is not a or a png";
        }
        //echo $_FILES['image']['size'] . "<br>";
        //echo $_FILES['image']['tmp_name'] . "<br>";
        //echo $_FILES['image']['error'] . "<br>";

        $userfile = $_FILES['image']['name'];
        $pathparts = pathinfo($userfile);
        //print_r($pathparts);

        $extension = $pathparts['extenstion'];
        $finalfile = $_SESSION['username'] . "_". $extenstion;
        $workingfile = "../images/" . $finalfile;

        if(file_exists($workingfile))
        {
            $errmsg = 1;
            $errfile = "File already exists!";
        }

        if(move_uploaded_file($_FILES['image']['tmp_name'], $workingfile.$userfile))
        {
            $errmsg = 1;
            $errfile = "Could not move file!";
        }

    }
    else
    {
        $errmsg = 1;
        $errfile = "Cannot process file!";
    }

    /* ***********************************************************************
      * CONTROL STATEMENT TO HANDLE ERRORS
      * ***********************************************************************
      */
    if($errmsg == 1)
    {
        echo "<p id='content' id='box1' class='error'>There are errors.  Please make corrections and resubmit.</p>";
    }
    else
    {
        echo "<p id='content' id='box1' class='success'>Your file has been uploaded.  You can view your file at <a href='http://ccuresearch.coastal.edu/rcwethey/csci409sp19/project/images/" .$finalfile . "' target='_blank'>" .$finalfile."</a></p>";
        $showform = 0;
    } // else errormsg

}//submit

//display form if Show Form Flag is true
if($showform == 1)
{
?>
    <form name="fileupload" id="fileupload" method="post" action="filetest.php" enctype="multipart/form-data">
        <table id='content' id='box1'>
            <tr><th><label for="myfile">Upload Your File:</label><span class="error">*</span></th>
                <td><input name="myfile" id="myfile" type="file" />
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



