<?php
$pagename = "Member Login";  //pagename var is used in the header
require_once "header.inc.php";
//set initial variables
$showform = 1;  // show form is true
$errormsg = 0;
$errorusername = "";
$errorpassword = "";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //create variables to store data from form - we never use POST directly w/ user input
    /* ****** NEW - CHANGED USERNAME TO LOWERCASE ****** */
    $formdata['username'] = trim(strtolower($_POST['username']));
    $formdata['password'] = trim($_POST['password']);

    //check for empty fields
    if (empty($formdata['username'])) {
        $errorusername = "The username is required.";
        $errormsg = 1;
    }

    if (empty($formdata['password'])) {
        $errorpassword = "The password is required.";
        $errormsg = 1;
    }

    if($errormsg == 1)
    {
        echo "<p id='content' id='box1' class='error'>There are errors. Please make corrections and resubmit.</p>";
    }
    else{
        /* --VERIFY THE PASSWORD-- */
        try
        {
            $sqlusers = "SELECT password, username, ID, membertype FROM members WHERE username = :username";
            $stmtusers = $pdo->prepare($sqlusers);
            $stmtusers->bindValue(':username', $formdata['username']);
            $stmtusers->execute();
            $row = $stmtusers->fetch();
            $countusers = $stmtusers->rowCount();
            if ($countusers < 1)
            {
                echo  "<p class='error'>This member cannot be found.</p>";
            }
            else {
                $_SESSION['ID'] = $row['ID'];
                $_SESSION['membertype'] = $row['membertype'];
                if (password_verify($formdata['password'], $row['password'])) {

                    $_SESSION['username'] = $row['username'];
                    $showform = 0;
                    header('Location: http://ccuresearch.coastal.edu/rcwethey/CSCI495-C2/index.php');
                }
                else {
                    echo "<p class='error'>The username and password combination you entered is not correct.  Please try again.</p>";
                }
            }//if countusers

        }//try
        catch (PDOException $e)
        {
            echo "<div id='content' id='box1' class='error'><p>ERROR selecting members!" .$e->getMessage() . "</p></div>";
            exit();
        }
    } // else errormsg
}//submit
if($showform == 1){
?>
<form name="login" id="login" method="POST" action="login.php">

    <div id='content'>
        <table id='box1'>
        <tr><th><label for="username">Username:</label><span class="error">*</span></th>
            <td><input name="username" id="username" type="text" placeholder="Required Username"
                       value="<?php if(isset($formdata['username']))
                       {echo $formdata['username'];
                       }?>" /><span class="error"><?php if(isset($errorusername)){echo $errorusername;}?></span></td>
        </tr>
        <tr><th><label for="password">Password:</label><span class="error">*</span></th>
            <td><input name="password" id="password" type="password" placeholder="Required Password" /><span class="error"><?php if(isset($errorpassword)){echo $errorpassword;}?></span></td>
        </tr>
        <tr><th><label for="submit">Submit: </label></th>
            <td><input type="submit" name="submit" id="submit" value="submit"/></td>
        </tr>
        </table >
        <a href="http://ccuresearch.coastal.edu/rcwethey/CSCI495-C2/forgotpwd.php">Forgot Password</a>
    </div>

<?php
    }//end showform
require_once "footer.inc.php";
?>








