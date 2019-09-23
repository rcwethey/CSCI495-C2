<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/21/2019
 * Time: 12:51 AM
 */


$pagename = "Update Content";
include_once "header.inc.php";
checkLogin();
//SET INITIAL VARIABLES
$showform = 1;  // show form is true
$errmsg = 0;
$errtitle = "";
$errdetails = "";

if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['ID']))
{
    $ID = $_GET['ID'];
}
elseif($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['ID']))
{
    $ID = $_POST['ID'];
}
else
{
    echo "<p class='error'>Something happened!  Cannot obtain the correct entry.</p>";
    $errormsg = 1;
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    /* ***********************************************************************
     * SANITIZE USER DATA
     * Use strtolower()  for emails, usernames and other case-sensitive info
     * Use trim() for ALL user-typed data -- even those not required
     * CAUTION:  Radio buttons are a bit different.
     *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
     * ***********************************************************************
     */
    $formdata['title'] = trim(strtoupper($_POST['title']));
    $formdata['details'] = trim($_POST['details']);

    /* ***********************************************************************
     * CHECK EMPTY FIELDS
     * Check for empty data for every required field
     * Do not do for things like apartment number, middle initial, etc.
     * CAUTION:  Radio buttons with 0 as a value = use isset() not empty()
     *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
     * ***********************************************************************
     */
    if (empty($formdata['title'])) {$errtitle = "The title is required."; $errmsg = 1;}
    if (empty($formdata['details'])) {$errdetails = "The details are required."; $errmsg = 1;}

    /* ***********************************************************************
     * CHECK MATCHING FIELDS
     * Check to see if important fields match
     * Usually used for passwords and sometimes emails.  We'll do passwords.
     * ***********************************************************************
     */

    //nothing here

    /* ***********************************************************************
     * CHECK EXISTING DATA
     * Check data to avoid duplicates
     * Usually used with emails and usernames - We'll do usernames
     * ***********************************************************************
     */
    if($formdata['title'] != $_POST['origtitle'])
    {
        try
        {
            $sql = "SELECT LCASE(title) FROM content WHERE title = :title";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':title', strtolower($formdata['title']));
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count > 0)
            {
                $errmsg = 1;
                $errtitle = "Title already taken.";
            }
        }
        catch (PDOException $e)
        {
            echo "<p class='error'>Error checking duplicate titles!" . $e->getMessage() . "</p>";
            exit();
        }
    }

    /* ***********************************************************************
     * CONTROL STATEMENT TO HANDLE ERRORS
     * ***********************************************************************
     */
    if($errmsg == 1)
    {
        echo "<p id='content' id='box1' class='error'>There are errors.  Please make corrections and resubmit.</p>";
    }
    else{

        /* ***********************************************************************
         * HASH SENSITIVE DATA
         * Used for passwords and other sensitive data
         * If checked for matching fields, do NOT hash and insert both to the DB
         * ***********************************************************************
         */
        //nothing here

        /* ***********************************************************************
         * INSERT INTO THE DATABASE
         * NOT ALL data comes from the form - Watch for this!
         *    For example, input dates are not entered from the form
         * ***********************************************************************
         */

        try{
            $sql = "UPDATE content 
                    SET title = :title, details = :details 
                    WHERE ID = :ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':title', $formdata['title']);
            $stmt->bindValue(':details', $formdata['details']);
            $stmt->bindValue(':ID', $ID);
            $stmt->execute();

            $showform =0; //hide the form
            echo "<p id='content' id='box1' class='success'>Thanks for updating the information.</p>";
        }
        catch (PDOException $e)
        {
            die( $e->getMessage() );
        }
    } // else errormsg
}//submit

//display form if Show Form Flag is true
if($showform == 1)
{
    $sql = "SELECT * FROM content WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID',$ID); // VARIALE WE CREATED
    $stmt->execute();
    $row = $stmt->fetch();
?>
    <form name="contentupdate" id="contentupdate" method="post" action="contentupdate.php">
        <div id="content">
        <table id="box1">
            <tr><th><label for="title">Title:</label></th>
                <td>
                    <input name="title"
                           id="title"
                           type="text"
                           placeholder="Required title"
                           value="<?php
                           if(isset($formdata['title']) && !empty($formdata['title']))           {
                               echo $formdata['title'];
                           }
                           else
                           {
                               echo $row['title'];
                           }

                           ?>"/>
                    <span class="error">*<?php if(isset($errcat)){echo $errtitle;}?></span></td>
            </tr>
            <tr><th><label for="details">Details:</label></th>
                <td><span class="error">* <?php if(isset($errdetails)){echo $errdetails;}?></span>
                    <textarea name="details" id="details" placeholder="Required Details"><?php
                        if(isset($formdata['details']) && !empty($formdata['details']))           {
                            echo $formdata['details'];
                        }
                        else
                        {
                            echo $row['details'];
                        }

                        ?></textarea>
                </td>
            </tr>
            <tr><th><label for="submit">Submit:</label></th>
                <td><input type="hidden" name="ID" id="ID" value="<?php echo $row['ID'];?>"/>
                    <input type="hidden" name="origtitle" id="origtitle"
                           value="<?php echo $row['title'];?>"/>
                    <input type="submit" name="submit" id="submit" value="submit"/></td>
            </tr>

        </table>
        </div>
    </form>
    <?php
}//end showform
include_once "footer.inc.php";
?>














