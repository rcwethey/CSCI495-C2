<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/21/2019
 * Time: 3:54 PM
 */


$pagename = "Member - Update Password";
include_once "header.inc.php";

//SET INITIAL VARIABLES
$showform = 1;  // show form is true
$errmsg = 0;
$errpassword = "";
$errpassword2 = "";

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
    $formdata['password'] = trim($_POST['password']);
    $formdata['password2'] = trim($_POST['password2']);

    /* ***********************************************************************
     * CHECK EMPTY FIELDS
     * Check for empty data for every required field
     * Do not do for things like apartment number, middle initial, etc.
     * CAUTION:  Radio buttons with 0 as a value = use isset() not empty()
     *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
     * ***********************************************************************
     */

    if (empty($formdata['password'])) {$errpassword = "The password is required."; $errmsg = 1; }
    if (empty($formdata['password2'])) {$errpassword2 = "The confirmation password is required."; $errmsg = 1; }

    /* ***********************************************************************
     * CHECK MATCHING FIELDS
     * Check to see if important fields match
     * Usually used for passwords and sometimes emails.  We'll do passwords.
     * ***********************************************************************
     */

    if ($formdata['password'] != $formdata['password2'])
    {
        $errmsg = 1;
        $errpassword2 = "The passwords do not match.";
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
        $hashedpwd = password_hash($formdata['password'], PASSWORD_BCRYPT);

        /* ***********************************************************************
         * INSERT INTO THE DATABASE
         * NOT ALL data comes from the form - Watch for this!
         *    For example, input dates are not entered from the form
         * ***********************************************************************
         */

        try{
            $sql = "UPDATE members 
                    SET password = :password
                    WHERE ID = :ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':password', $hashedpwd);
            $stmt->bindValue(':ID', $_SESSION['ID']);
            $stmt->execute();

            $showform =0; //hide the form
            header("Location: logout.php?state=3");
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
?>
    <form name="memberpwd" id="memberpwd" method="post" action="memberpwd.php">
        <div id="content">
        <table id="box1">
           <tr><th><label for="password">Password:</label><span class="error">*</span></th>
                <td><input name="password" id="password" type="password" size="40" placeholder="Required password" />
                    <span class="error"><?php if(isset($errpassword)){echo $errpassword;}?></span></td>
            </tr>
            <tr><th><label for="password2">Password Confirmation:</label><span class="error">*</span></th>
                <td><input name="password2" id="password2" type="password" size="40" placeholder="Required confirmation password" />
                    <span class="error"><?php if(isset($errpassword2)){echo $errpassword2;}?></span></td>
            </tr>
            <tr><th><label for="submit">Submit:</label></th>
                <td><input type="submit" name="submit" id="submit" value="submit"/></td>
            </tr>

        </table>
        </div>
    </form>
    <?php
}//end showform
include_once "footer.inc.php";
?>








