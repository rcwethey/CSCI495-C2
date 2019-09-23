<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/20/2019
 * Time: 10:58 PM
 */


$pagename = "Delete Content";
include_once "header.inc.php";
checkLogin();
//SET INITIAL VARIABLES
$showform = 1;  // show form is true

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    /* ***********************************************************************
     * DELETE FROM THE DATABASE
    */
    try{
        //query the data
        $sql = "DELETE FROM content WHERE ID = :ID";
        //prepares a statement for execution
        $stmt = $pdo->prepare($sql);
        //binds the actual value of $_GET['ID'] to
        $stmt->bindValue(':ID', $_POST['ID']);  //notice this is NOT submitted from the form
        //executes a prepared statement
        $stmt->execute();
        //hide the form
        $showform = 0;
        //provide useful confirmation to user
        echo "<p id='content' id='box1'>" . $_POST['title'] . " has been deleted. 
                 <a href='contentmanage.php'>Return to list</a>?</p>";
    }
    catch (PDOException $e)
    {
        die( $e->getMessage() );
    }
}//submit

//display form if Show Form Flag is true
if($showform == 1)
{
?>
    <div id='content' id='box1'>
    <p>Are you sure you want to delete <?php echo $_GET['T'];?>?</p>
    <form id="deletecat" name="deletecontent" method="post" action="contentdelete.php">
        <input type="hidden" id="ID" name="ID" value="<?php echo $_GET['ID'];?>" />
        <input type="hidden" id="title" name="title" value="<?php echo $_GET['T'];?>" />
        <input type="submit" id="delete" name="delete" value="YES" />
        <input type="button" id="nodelete" name="nodelete" value="NO" onClick="window.location='contentmanage.php'" />
    </form>
    </div>
<?php
}//end showform
include_once "footer.inc.php";
?>








