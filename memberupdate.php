<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/21/2019
 * Time: 6:28 PM
 */

$pagename = "Member - Update";
include_once "header.inc.php";

//SET INITIAL VARIABLES
$showform = 1;  // show form is true
$showform = 1;          //show form is true
$errmsg = 0;
$errfname = "";
$errusername = "";
$erremail = "";
$errcap = "";
$errpassword = "";
$errpassword2 = "";
$errbio = "";
$errlname = "";
$erraddress = "";
$errcity = "";
$errstate = "";
$errzip_code = "";
$errphone_num= "";
$errcarrier = "";
$errfile = "";
$errusername = "";

$pathparts;
$userfile;
$extension;
$finalfile;
$workingfile;
$profilepic;


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
    echo "<p id='content' id='box1' class='error'>Something happened!  Cannot obtain the correct entry.</p>";
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
    $formdata['fname'] = trim($_POST['fname']);
    $formdata['lname'] = trim($_POST['lname']);
    $formdata['username'] = trim(strtolower($_POST['username']));
    $formdata['email'] = trim(strtolower($_POST['email']));
    $formdata['bio'] = trim($_POST['bio']);
    $formdata['address'] = trim(strtolower($_POST['address']));
    $formdata['city'] = trim(strtolower($_POST['city']));
    $formdata['state'] = trim(strtolower($_POST['state']));
    $formdata['zip_code'] = trim($_POST['zip_code']);
    $formdata['phone'] = trim($_POST['phone']);




    /* ***********************************************************************
     * CHECK EMPTY FIELDS
     * Check for empty data for every required field
     * Do not do for things like apartment number, middle initial, etc.
     * CAUTION:  Radio buttons with 0 as a value = use isset() not empty()
     *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
     * ***********************************************************************
     */

    if (empty($formdata['fname'])) {$errfname = "Your first name is required."; $errmsg = 1; }
    if (empty($formdata['lname'])) {$errlname = "Your last name is required."; $errmsg = 1; }
    if (empty($formdata['username'])) {$errusername = "The username is required."; $errmsg = 1; }
    if (empty($formdata['email'])) {$erremail = "The email is required."; $errmsg = 1; }
    if (empty($formdata['bio'])) {$errbio = "A bio is required."; $errmsg = 1; }
    if (empty($formdata['address'])) {$erraddress = "An address is required."; $errmsg = 1; }
    if (empty($formdata['city'])) {$errcity = "A city is required."; $errmsg = 1; }
    if (empty($formdata['state'])) {$errstate = "A state is required."; $errmsg = 1; }
    if (empty($formdata['zip_code'])) {$errzip_code = "A zip code is required."; $errmsg = 1; }
    if (empty($formdata['phone'])) {$errphone_num = "A phone number is required."; $errmsg = 1; }


    /* ***********************************************************************
     * CHECK EXISTING DATA
     * Check data to avoid duplicates
     * Usually used with emails and usernames - We'll do usernames
     * ***********************************************************************
     */
    if($formdata['email'] != $_POST['origemail'])
    {
            $sql = "SELECT * FROM members WHERE email = ?";
            $count = checkDup($pdo, $sql, $formdata['email']);
            if ($count > 0)
            {
                $errmsg = 1;
                $erremail = "email already taken.";
            }
    }
    $email = $formdata['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        echo("<p id='content' id='box1'>$email is a valid email address</p>");
    } else {
        echo("<p id='content' id='box1'> $email is not a valid email address</p>");
    }



    if($formdata['username'] != $_POST['origusername'])
    {
        $sql = "SELECT * FROM members WHERE username = ?";
        $count = checkDup($pdo, $sql, $formdata['username']);
        if ($count > 0)
        {
            $errmsg = 1;
            $erremail = "username already taken.";

        }
    }
    /*
        if($_FILES['image']['name'])
        {
        //if no errors...
            if(!$_FILES['image']['error'])
            {
            //now is the time to modify the future file name and validate the file
                $new_file_name = strtolower($_FILES['image']['tmp_name']); //rename file
                if($_FILES['image']['size'] > (1024000)) //can't be larger than 1 MB
                {
                    $valid_file = false;
                    $message = 'Oops! Your files size is to large.';
                }

                //if the file has passed the test
                if($valid_file)
                {
                    //move it to where we want it to be
                    move_uploaded_file($_FILES['image']['tmp_name'], 'images/'.$new_file_name);
                    $message = 'Congratulations! Your file was accepted.';
                }
            }
            //if there is an error...
            else
            {
            //set that to be the returned message
                $message = 'Ooops! Your upload triggered the following error: '.$_FILES['image']['error'];
            }
        }



        if($_FILES['image']['error'] == 0)
        {
            $userfile = $_FILES['image']['name'];
            $pathparts = pathinfo($_FILES['image']['name']);
            //print_r($pathparts);
            $extension = $pathparts['extension'];
            $profilepic = $_SESSION['username'] . "_" .$rightnow . "." .$extension;
            $workingfile = "/var/www/html/uploads/" . $profilepic;
            if(file_exists($workingfile))
            {
                $errmsg = 1;
                $errfile = "File already exists";
            }
            if(!move_uploaded_file($_FILES['image']['tmp_name'], $workingfile))
            {
                $errmsg = 1;
                $errfile = "Could not move file!";
            }
        }
        else {
            $errmsg = 1;
            $errfile = "Cannot process file";
        }
    */

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
         * INSERT INTO THE DATABASE
         * NOT ALL data comes from the form - Watch for this!
         *    For example, input dates are not entered from the form
         * ***********************************************************************
         */

        try{
            $sql = "UPDATE members 
                    SET email = :email, username = :username, updatedate = :updatedate, fname = :fname, lname = :lname, bio = :bio, address = :address, city = :city, state =:state, zip_code = :zip_code, phone = :phone
                    WHERE ID = :ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':fname', $formdata['fname']);
            $stmt->bindValue(':lname', $formdata['lname']);
            $stmt->bindValue(':username', $formdata['username']);
            $stmt->bindValue(':email', $formdata['email']);
            $stmt->bindValue(':bio', $formdata['bio']);
            $stmt->bindValue(':updatedate', $rightnow);
            $stmt->bindValue(':address', $formdata['address']);
            $stmt->bindValue(':city', $formdata['city']);
            $stmt->bindValue(':state', $formdata['state']);
            $stmt->bindValue(':zip_code', $formdata['zip_code']);
            $stmt->bindValue(':phone', $formdata['phone']);
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
    $sql = "SELECT * FROM members WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID',$ID); // VARIALE WE CREATED
    $stmt->execute();
    $row = $stmt->fetch();
    ?>
    <form name="memberupdate" id="memberupdate" method="post" action="memberupdate.php"  >
        <div id="content">
        <table id="box1">
                <tr><th><label for="fname">First Name:</label><span class="error">*</span></th>
                    <td><input name="fname" id="fname" type="text" size="20" placeholder="Required fname"
                               value="<?php if(isset($formdata['fname']) && !empty($formdata['fname'])){echo $formdata['fname']; } else {echo $row['fname'];} ?>"/>
                        <span class="error"><?php if(isset($errfname)){echo $errfname;}?></span></td>
                </tr>
                <tr><th><label for="lname">Last Name:</label><span class="error">*</span></th>
                    <td><input name="lname" id="lname" type="text" size="20" placeholder="Required lname"
                               value="<?php if(isset($formdata['lname']) && !empty($formdata['lname'])){echo $formdata['lname']; } else {echo $row['lname'];} ?>"/>
                        <span class="error"><?php if(isset($errlname)){echo $errlname;}?></span></td>
                </tr>
                <tr><th><label for="username">Username:</label><span class="error">*</span></th>
                    <td><input name="username" id="username" type="text" size="20" placeholder="Required username"
                               value="<?php if(isset($formdata['username']) && !empty($formdata['username'])){echo $formdata['username']; } else {echo $row['username'];} ?>"/>
                        <span class="error"><?php if(isset($errusername)){echo $errusername;}?></span></td>
                </tr>
                <tr><th><label for="email">email:</label><span class="error">*</span></th>
                    <td><input name="email" id="email" type="text" size="50" placeholder="Required email"
                               value="<?php if(isset($formdata['email']) && !empty($formdata['email'])){echo $formdata['email']; } else {echo $row['email'];} ?>"/>
                        <span class="error"><?php if(isset($erremail)){echo $erremail;}?></span></td>
                </tr>
                <tr><th><label for="bio">Bio:</label><span class="error">*</span></th>
                    <td><span class="error"><?php if(isset($errbio)){echo $errbio;}?></span>
                        <textarea name="bio" id="bio" placeholder="Required bio"><?php if(isset($formdata['bio']) && !empty($formdata['bio'])){echo $formdata['bio']; } else {echo $row['bio'];} ?>"</textarea>
                    </td>
                </tr>
                <tr><th><label for="address">Address:</label><span class="error">*</span></th>
                    <td><input name="address" id="address" type="text" size="20" placeholder="Required address"
                               value="<?php if(isset($formdata['address']) && !empty($formdata['address'])){echo $formdata['address']; } else {echo $row['address'];} ?>"/>
                        <span class="error"><?php if(isset($erraddress)){echo $erraddress;}?></span></td>
                </tr>
                <tr><th><label for="city">City:</label><span class="error">*</span></th>
                    <td><input name="city" id="city" type="text" size="20" placeholder="Required city"
                               value="<?php if(isset($formdata['city']) && !empty($formdata['city'])){echo $formdata['city']; } else {echo $row['city'];} ?>"/>
                        <span class="error"><?php if(isset($errcity)){echo $errcity;}?></span></td>
                </tr>
                <tr><th><label for="state">State:</label><span class="error">*</span></th>
                    <td><input name="state" id="state" type="text" size="20" placeholder="Required state"
                               value="<?php if(isset($formdata['state']) && !empty($formdata['state'])){echo $formdata['state']; } else {echo $row['state'];} ?>"/>
                        <span class="error"><?php if(isset($errstate)){echo $errstate;}?></span></td>
                </tr>
                <tr><th><label for="zip_code">Zip Code:</label><span class="error">*</span></th>
                    <td><input name="zip_code" id="zip_code" type="text" size="20" placeholder="Required zip code"
                               value="<?php if(isset($formdata['zip_code']) && !empty($formdata['zip_code'])){echo $formdata['zip_code']; } else {echo $row['zip_code'];} ?>"/>
                        <span class="error"><?php if(isset($errzip_code)){echo $errzip_code;}?></span></td>
                </tr>
                <tr><th><label for="phone">10 Digit Phone Number:</label><span class="error">*</span></th>
                    <td><input name="phone" id="phone" type="text" size="20" placeholder="Required phone number"
                               value="<?php if(isset($formdata['phone']) && !empty($formdata['phone'])){echo $formdata['phone']; } else {echo $row['phone'];} ?>"/>
                        <span class="error"><?php if(isset($errphone_num)){echo $errphone_num;}?></span></td>
                </tr>

                <tr><th><label for="submit">Submit:</label></th>
                    <td><input type="hidden" name="ID" id="ID" value="<?php echo $row['ID'];?>"/>
                        <input type="hidden" name="origemail" id="origemail" value="<?php echo $row['email'];?>"/>
                        <input type="hidden" name="origusername" id="origusername" value="<?php echo $row['username'];?>"/>
                        <input type="submit" name="submit" id="submit" value="submit"/></td>
                </tr>
            </table>
        </div>
    </form>
    <?php
}//end showform
include_once "footer.inc.php";
?>