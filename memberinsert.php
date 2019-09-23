<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/21/2019
 * Time: 4:22 PM
 */

$pagename = "Insert New Member";
include_once "header.inc.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'phpmailer/vendor/autoload.php';

//SET INITIAL VARIABLES
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
$errmemtype = "";



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
    $formdata['password'] = $_POST['password'];
    $formdata['password2'] = $_POST['password2'];
    $formdata['bio'] = trim($_POST['bio']);
    $formdata['address'] = trim(strtolower($_POST['address']));
    $formdata['city'] = trim(strtolower($_POST['city']));
    $formdata['state'] = trim(strtolower($_POST['state']));
    $formdata['zip_code'] = trim($_POST['zip_code']);
    $formdata['phone'] = trim($_POST['phone']);
    $formdata['carrier'] = trim($_POST['carrier']);
    $formdata['membertype'] = trim($_POST['membertype']);



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
    if (empty($formdata['password'])) {$errpassword = "The password is required."; $errmsg = 1; }
    if (empty($formdata['password2'])) {$errpassword2 = "The confirmation password is required."; $errmsg = 1; }
    if (empty($formdata['bio'])) {$errbio = "A bio is required."; $errmsg = 1; }
    if (empty($formdata['address'])) {$erraddress = "An address is required."; $errmsg = 1; }
    if (empty($formdata['city'])) {$errcity = "A city is required."; $errmsg = 1; }
    if (empty($formdata['state'])) {$errstate = "A state is required."; $errmsg = 1; }
    if (empty($formdata['zip_code'])) {$errzip_code = "A zip code is required."; $errmsg = 1; }
    if (empty($formdata['phone'])) {$errphone_num = "A phone number is required."; $errmsg = 1; }
    if (empty($formdata['carrier'])) {$errcarrier = "A carrier is required."; $errmsg = 1; }
    if (empty($formdata['membertype'])) {$errmemtype = "A membertype is required."; $errmsg = 1; }
    if (empty($_POST['g-recaptcha-response'])) {$errcap = "The reCAPTCHA is required."; $errmsg = 1;}

    /* ***********************************************************************
     * REGEX patterns used in the checking of lengths and alphanumeric or
     * numeric field of the form
     * ***********************************************************************
     */
    $pattern = '/(\R|\t|\0|\x0B)/';
    //$pattern2 = '/^(\d{5}(\d{4})?)?$/';
    $pattern3 = '/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/';


    if(preg_match($pattern, $formdata['password']))
    {
        $errmsg = 1;
        $errpassword .= "The password does not meet Regex parameters.";
    }

    /* if(preg_match($pattern2, $formdata['zip_code']))
        {
            $errmsg = 1;
            $errzip_code .= "The zip code does not meet Regex parameters.";
        }
    */

   if(preg_match($pattern3, $formdata['phone']))
    {
        $errmsg = 1;
        $errphone_num .= "The phone number does not meet Regex parameters.";
    }

    /* ***********************************************************************
     * CHECK MATCHING FIELDS
     * Check to see if important fields match
     * Usually used for passwords and sometimes emails.  We'll do passwords.
     * ***********************************************************************
     */
    if(strlen($formdata['password']) < 8 || strlen($formdata['password']) > 64 )
    {
        $errmsg = 1;
        $errpassword .= "The password does not meet length requirements. Must be greater than 10 characters or less than 64.";
    }
    if(count_chars($formdata['password'], 1)==3)
    {
        $errmsg = 1;
        $errpassword .= "You have repeated a character more than is allowed!";
    }
    $sql1 = "SELECT * FROM badpwd WHERE pwd = ?";
    $pwdcount = pwdCheck($pdo, $sql1, $formdata['password']);
    if($pwdcount > 0)
    {
        $errmsg = 1;
        $errpassword .= "You picked a bad password.";
    }

    if ($formdata['password'] != $formdata['password2'])
    {
        $errmsg = 1;
        $errpassword2 = "The passwords do not match.";
    }

    /* ***********************************************************************
     * CHECK EXISTING DATA
     * Check data to avoid duplicates
     * Usually used with emails and usernames - We'll do usernames
     * ***********************************************************************
     */
    //checking for exiting username
    $sql = "SELECT * FROM members WHERE username = ?";
    $count = checkDup($pdo, $sql, $formdata['username']);
    if($count > 0)
    {
        $errmsg = 1;
        $errusername = "The username is already taken.";
    }

    //checking for duplicate email.
    $sql = "SELECT * FROM members WHERE email = ?";
    $count = checkDup($pdo, $sql, $formdata['email']);
    if($count > 0)
    {
        $errmsg = 1;
        $erremail = "The email is already taken.";
    }

    //$email = $formdata['email'];
    //if(filter_var($email, FILTER_VALIDATE_EMAIL)){
    //    echo $email + " is a valid email address";
    //}
    //else {
    //    echo $email + " is not a valid email address";
    //}

    $email = $formdata['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        echo("<p id='content' id='box1'>$email is a valid email address</p>");
    } else {
        echo("<p id='content' id='box1'> $email is not a valid email address</p>");
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
        $cookie_name = "user";
        $cookie_value = $formdata['fname'];
        setcookie($cookie_name, $cookie_value, time($rightnow) + (86400 * 30), "/");

        try{
            $sql = "INSERT INTO members (fname, lname, username, email, password, bio, inputdate, updatedate, address, city, state, zip_code, phone, carrier, membertype)
                    VALUES (:fname, :lname, :username, :email, :password, :bio, :inputdate, :updatedate, :address, :city, :state, :zip_code, :phone, :carrier, :membertype) ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':fname', $formdata['fname']);
            $stmt->bindValue(':lname', $formdata['lname']);
            $stmt->bindValue(':username', $formdata['username']);
            $stmt->bindValue(':email', $formdata['email']);
            $stmt->bindValue(':password', $hashedpwd);
            $stmt->bindValue(':bio', $formdata['bio']);
            $stmt->bindValue(':inputdate', $rightnow);
            $stmt->bindValue(':updatedate', $rightnow);
            $stmt->bindValue(':address', $formdata['address']);
            $stmt->bindValue(':city', $formdata['city']);
            $stmt->bindValue(':state', $formdata['state']);
            $stmt->bindValue(':zip_code', $formdata['zip_code']);
            $stmt->bindValue(':phone', $formdata['phone']);
            $stmt->bindValue(':carrier', $formdata['carrier']);
            $stmt->bindValue(':membertype', $formdata['membertype']);
            $stmt->execute();


            //BEGIN SENDING EMAILS USING PHP MAILER ------------------------

            $to = $formdata['email'];
            $toname = $formdata['fname'];
            $tophone = $formdata['phone'] . "@" . $formdata['carrier'];
            $subject = 'Welcome';
            $message = 'This is a message, thank you fro joining our site';
            $msghtml = "<p><strong>Thank you </strong> for joining!</p>";
            $msgnohtml = strip_tags($msghtml);

            $mail = new PHPMailer(true);

            try{
                $mail->SMTPDebug = 0;                                       // Enable verbose debug output
                $mail->isSMTP();                                            // Set mailer to use SMTP
                $mail->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = 'ccucsciweb';                           // SMTP username
                $mail->Password   = 'csci303&409';                          // SMTP password
                $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('ccucsciweb@coastal.edu', 'CSCI 409');
                $mail->addAddress($to, $toname);     // Add a recipient
                $mail->addAddress($tophone);         // Name is optional
                //$mail->addReplyTo('info@example.com', 'Information');
                //$mail->addCC('cc@example.com');
                //$mail->addBCC('bcc@example.com');

                // Attachments
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    = $msghtml;
                $mail->AltBody = $msgnohtml;

                $mail->send();
                echo "<p class='success'>Message has been sent</p>";
            } catch (Exception $e) {
                echo "<p class='error'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
            }
            //END SENDING EMAILS USING PHP MAILER ----------------------------

            $showform =0; //hide the form
            echo "<p id='content' id='box1' class='success'>Thanks for entering your information.</p>";
            header('Location: http://ccuresearch.coastal.edu/rcwethey/CSCI495-C2/index.php');

           // $to = $formdata['email'];
           // $from = 'Week 11 Changes';
           // $subject = 'Welcome';
           // $message = 'This is a message, thank you for joining our site';

           // $success = mail($to, $from, $subject, $message);
           // if ($success)
           // {
           //     echo "<p id='content' id='box1' class='success'>Thank you for joining, we sent you an email!</p>";
           // }
           // else{
           //         $errorEmailMessage = 'There was an error sending the message!';
           // }

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

    <form name="memberinsert" id="memberinsert" method="post" action="memberinsert.php">
        <div id="content">
        <table id="box1">
            <tr><th><label for="fname">First Name:</label><span class="error">*</span></th>
                <td><input name="fname" id="fname" type="text" size="20" placeholder="Required fname"
                           value="<?php if(isset($formdata['fname'])){echo $formdata['fname'];}?>"/>
                    <span class="error"><?php if(isset($errfname)){echo $errfname;}?></span></td>
            </tr>
            <tr><th><label for="lname">Last Name:</label><span class="error">*</span></th>
                <td><input name="lname" id="lname" type="text" size="20" placeholder="Required lname"
                           value="<?php if(isset($formdata['lname'])){echo $formdata['lname'];}?>"/>
                    <span class="error"><?php if(isset($errlname)){echo $errlname;}?></span></td>
            </tr>
            <tr><th><label for="username">Username:</label><span class="error">*</span></th>
                <td><input name="username" id="username" type="text" size="20" placeholder="Required username"
                           value="<?php if(isset($formdata['username'])){echo $formdata['username'];}?>"/>
                    <span class="error"><?php if(isset($errusername)){echo $errusername;}?></span></td>
            </tr>
            <tr><th><label for="email">email:</label><span class="error">*</span></th>
                <td><input name="email" id="email" type="text" size="50" placeholder="Required email"
                           value="<?php if(isset($formdata['email'])){echo $formdata['email'];}?>"/>
                    <span class="error"><?php if(isset($erremail)){echo $erremail;}?></span></td>
            </tr>
            <tr><th><label for="password">Password:</label><span class="error">*</span></th>
                <td><input name="password" id="password" type="password" size="40" onkeyup="passwordStrength(this.value)" placeholder="Required password"  />
                    <span class="error"><?php if(isset($errpassword)){echo $errpassword;}?></span></td>
            </tr>
            <tr><th><label for="password2">Password Confirmation:</label><span class="error">*</span></th>
                <td><input name="password2" id="password2" type="password" size="40" placeholder="Required confirmation password" />
                    <br>
                    <label for="passwordStrength"><strong>Password strength:</strong></label>
                    <div id="passwordDescription">Password not entered</div>
                    <div id="passwordStrength" class="strength0"></div>


                    <script>
                        function passwordStrength(password)
                        {
                            var desc = new Array();
                            desc[0] = "Very Weak";
                            desc[1] = "Weak";
                            desc[2] = "Better";
                            desc[3] = "Good";
                            desc[4] = "Strong";
                            var score   = 0;

                            //if password bigger than 10 give 1 point

                            if (password.length > 9) score++;

                            //if password has both lower and uppercase characters give 1 point

                            //if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;

                            //if password has at least one number give 1 point

                            if (password.length > 14 ) score++;
                            if (password.length > 19 ) score++;
                            if (password.length > 24 ) score++;


                            //if (password.match(/d+/)) score++;

                            //if password has at least one special caracther give 1 point

                            // if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ) score++;

                            document.getElementById("passwordDescription").innerHTML = desc[score];
                            document.getElementById("passwordStrength").className = "strength" + score;
                        }
                        var pass = document.getElementById('password');
                        pass.addEventListener('keyup', function(event) {
                            // set value of input field to variable
                            var value = this.value;

                            // fire function to test password strength
                            passwordStrength(value);
                        });
                    </script>
                    <span class="error"><?php if(isset($errpassword2)){echo $errpassword2;}?></span></td>
            </tr>
            <tr><th><label for="bio">Bio:</label><span class="error">*</span></th>
                <td><span class="error"><?php if(isset($errbio)){echo $errbio;}?></span>
                    <textarea name="bio" id="bio" placeholder="Required bio"><?php if(isset($formdata['bio'])){echo $formdata['bio'];}?></textarea>
                </td>
            </tr>
            <tr><th><label for="address">Address:</label><span class="error">*</span></th>
                <td><input name="address" id="address" type="text" size="20" placeholder="Required address"
                           value="<?php if(isset($formdata['address'])){echo $formdata['address'];}?>"/>
                    <span class="error"><?php if(isset($erraddress)){echo $erraddress;}?></span></td>
            </tr>
            <tr><th><label for="city">City:</label><span class="error">*</span></th>
                <td><input name="city" id="city" type="text" size="20" placeholder="Required city"
                           value="<?php if(isset($formdata['city'])){echo $formdata['city'];}?>"/>
                    <span class="error"><?php if(isset($errcity)){echo $errcity;}?></span></td>
            </tr>
            <tr><th><label for="state">State:</label><span class="error">*</span></th>
                <td><input name="state" id="state" type="text" size="20" placeholder="Required state"
                           value="<?php if(isset($formdata['state'])){echo $formdata['state'];}?>"/>
                    <span class="error"><?php if(isset($errstate)){echo $errstate;}?></span></td>
            </tr>
            <tr><th><label for="zip_code">Zip Code:</label><span class="error">*</span></th>
                <td><input name="zip_code" id="zip_code" type="text" size="20" placeholder="Required zip code"
                           value="<?php if(isset($formdata['zip_code'])){echo $formdata['zip_code'];}?>"/>
                    <span class="error"><?php if(isset($errzip_code)){echo $errzip_code;}?></span></td>
            </tr>
            <tr><th><label for="phone">10 Digit Phone Number:</label><span class="error">*</span></th>
                <td><input name="phone" id="phone" type="text" size="20" placeholder="Required phone number"
                           value="<?php if(isset($formdata['phone'])){echo $formdata['phone'];}?>"/>
                    <span class="error"><?php if(isset($errphone_num)){echo $errphone_num;}?></span></td>
            </tr>
            <tr><th><label for="carrier">Carrier:</label><span class="error">*</span></th>
                <td><select name="carrier" id="carrier">
                    <option value='' <?php if(isset($formdata['carrier']) && $formdata['carrier'] == '') {echo "SELECTED";} ?>>CHOSE</option>
                    <option value='sms.alltellwireless.com' <?php if(isset($formdata['carrier']) && $formdata['carrier'] == 'sms.atellwireless.com') {echo "SELECTED";} ?>>Alltell</option>
                    <option value='txt.att.net' <?php if(isset($formdata['carrier']) && $formdata['carrier'] == 'txt.att.net') {echo "SELECTED";} ?>>AT&T</option>
                    <option value='sms.myboostmobile.com' <?php if(isset($formdata['carrier']) && $formdata['carrier'] == 'sms.myboostmobile.com') { echo "SELECTED";} ?>>Boost Mobile</option>
                    <option value='mms.cricketwireless.net' <?php if(isset($formdata['carrier']) && $formdata['carrier'] == 'mms.cricketwireless.net') {echo "SELECTED";} ?>>Cricket Wirless</option>
                    <option value='mymetropcs.com' <?php if(isset($formdata['carrier']) && $formdata['carrier'] == 'mymetropcs.com') {echo "SELECTED";} ?>>Metro PCS</option>
                    <option value='msg.fi.google.com' <?php if(isset($formdata['carrier']) && $formdata['carrier'] == 'msg.fi.google.com') {echo "SELECTED";} ?>>Google Fi</option>
                    <option value='text.reupiblicwireless.com' <?php if(isset($formdata['carrier']) && $formdata['carrier'] == 'text.reupiblicwireless.com') {echo "SELECTED";} ?>>Republic Wirelss</option>
                    <option value='messaging.sprintpcs.com' <?php if(isset($formdata['carrier']) && $formdata['carrier'] == 'messaging.sprintpcs.com') {echo "SELECTED";} ?>>Sprint</option>
                    <option value='tmomail.net' <?php if(isset($formdata['carrier']) && $formdata['carrier'] == 'tmomail.net') {echo "SELECTED";} ?>>T-Mobile</option>
                    <option value='email.uscc.net' <?php if(isset($formdata['carrier']) && $formdata['carrier'] == 'email.uscc.net') {echo "SELECTED";} ?>>U.S. Cellular</option>
                    <option value='vtext.com' <?php if(isset($formdata['carrier']) && $formdata['carrier'] == 'vtext.com') {echo "SELECTED";} ?>>Verizon</option>
                    <option value='vmobl.com' <?php if(isset($formdata['carrier']) && $formdata['carrier'] == 'vmobl.com') {echo "SELECTED";} ?>>Virgin Mobile</option>
                </td>
            </tr>
            <tr><th><label for="membertype">Access Level:</label><span class="error">*</span></th>
                <td><select name="membertype" id="membertype">
                        <option value='' <?php if(isset($formdata['membertype']) && $formdata['membertype'] == '') {echo "SELECTED";} ?>>CHOSE</option>
                        <option value='0' <?php if(isset($formdata['membertype']) && $formdata['membertype'] == 0) {echo "SELECTED";} ?>>USER</option>
                        <option value='1' <?php if(isset($formdata['membertype']) && $formdata['membertype'] == 1) {echo "SELECTED";} ?>>ADMIN</option>
                </td>
            </tr>
            <tr><th><label for="submit">Submit:</label></th>
                <td><span class="error"><?php if(isset($errcap)) {echo $errcap;}?></span>
                <div class="g-recaptcha" data-sitekey="6LevcB0UAAAAAI_Y_dKMg-bT_USxicPojFxWTgp_"></div>
                <input type="submit" name="submit" id="submit" value="submit"/></td>
            </tr>

        </table>
    </div>
    </form>

    <?php
}//end showform
include_once "footer.inc.php";
?>








