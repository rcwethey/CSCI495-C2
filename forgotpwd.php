<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/21/2019
 * Time: 7:36 PM
 */


$pagename = "Insert New Member";
include_once "header.inc.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../phpmailer/vendor/autoload.php';

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
$number = rand(1, 2147483647);


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

    $formdata['email'] = trim(strtolower($_POST['email']));
    $formdata['phone'] = trim($_POST['phone']);

    /* ***********************************************************************
     * CHECK EMPTY FIELDS
     * Check for empty data for every required field
     * Do not do for things like apartment number, middle initial, etc.
     * CAUTION:  Radio buttons with 0 as a value = use isset() not empty()
     *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
     * ***********************************************************************
     */

    if (empty($formdata['email'])) {$erremail = "The email is required."; $errmsg = 1; }
    if (empty($formdata['phone'])) {$errphone_num = "The phone number is required."; $errmsg = 1; }

    /* ***********************************************************************
     * CHECK EXISTING DATA
     * Check data to avoid duplicates
     * Usually used with emails and usernames - We'll do usernames
     * ***********************************************************************
     */

    //checking for exiting username
    //checking for duplicate email.
    $sql = "SELECT * FROM members WHERE email = ?";
    $count = checkDup($pdo, $sql, $formdata['email']);
    if($count = 0)
    {
        $errmsg = 1;
        $erremail = "There is no such email in the database.";
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

            try {
                //query the data
                $sql = "SELECT * FROM members WHERE email = :email";
                //prepares a statement for execution
                $stmt = $pdo->prepare($sql);
                //binds the actual value of $_GET['ID'] to
                $stmt->bindValue(':email', $formdata['email']);
                //executes a prepared statement
                $stmt->execute();
                //fetches the next row from a result set / returns an array
                //default:  array indexed by both column name and 0-indexed column number
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                $to = $row['email'];
                                $toname = $row['fname'];
                                $tophone = $row['phone'] . "@" . $row['carrier'];
                                $subject = 'Forget Something?';
                                $message = 'Forgot Password, lets change that!';
                                $msghtml = "<p>Since you forgot your password, please use this link to get to the change your password! 
                                <a href='http://ccuresearch.coastal.edu/rcwethey/csci409sp19/project/memberpwd.php?ID=" . $row['ID'] . "&rnd=" . $number . "'> THIS IS THE LINK </a></p>";

                                $msgnohtml = strip_tags($msghtml);

                                $mail = new PHPMailer(true);

                                try {
                                    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
                                    $mail->isSMTP();                                            // Set mailer to use SMTP
                                    $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
                                    $mail->SMTPAuth = true;                                   // Enable SMTP authentication
                                    $mail->Username = 'ccucsciweb';                           // SMTP username
                                    $mail->Password = 'csci303&409';                          // SMTP password
                                    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
                                    $mail->Port = 587;

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
                                    $mail->Body = $msghtml;
                                    $mail->AltBody = $msgnohtml;

                                    $mail->send();
                                    echo "<p class='success'>Message has been sent</p>";
                                } catch (Exception $e) {
                                    echo "<p class='error'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
                                }

            }
            catch (PDOException $e)
            {
                die( $e->getMessage() );
            }
                            //END SENDING EMAILS USING PHP MAILER ----------------------------

        $showform = 0; //hide the form
        echo "<p id='content' id='box1' class='success'>Thanks for entering your information.</p>";
        header('Location: http://ccuresearch.coastal.edu/rcwethey/csci409sp19/project/index.php');


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
                //}
            }
    } // else errormsg
//submit

//display form if Show Form Flag is true
if($showform == 1)
{
    ?>

    <form name="forogtpwd" id="forogtpwd" method="post" action="forgotpwd.php">
        <div id="content">
            <table id="box1">
                <tr><th><label for="phone">10 digit Phone number:</label><span class="error">*</span></th>
                    <td><input name="phone" id="phone" type="text" size="20" placeholder="Required phone number"
                               value="<?php if(isset($formdata['phone'])){echo $formdata['phone'];}?>"/>
                        <span class="error"><?php if(isset($errphone_num)){echo $errphone_num;}?></span></td>
                </tr>
                <tr><th><label for="email">email:</label><span class="error">*</span></th>
                    <td><input name="email" id="email" type="text" size="50" placeholder="Required email"
                               value="<?php if(isset($formdata['email'])){echo $formdata['email'];}?>"/>
                        <span class="error"><?php if(isset($erremail)){echo $erremail;}?></span></td>
                </tr>
                <tr><th><label for="submit">Submit:</label></th>
                    <td><span class="error"><?php if(isset($errcap)) {echo $errcap;}?></span>
                        <input type="submit" name="submit" id="submit" value="submit"/></td>
                </tr>

            </table>
        </div>
    </form>

    <?php
}//end showform
include_once "footer.inc.php";
?>