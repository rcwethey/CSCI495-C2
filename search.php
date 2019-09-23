<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/21/2019
 * Time: 8:41 PM
 */
$pagename = "Search";
require_once "header.inc.php";
require_once "functions.inc.php";
checkLogin();
$showform = 1;
$errmsg = 0;

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    // We are just echoing out the search term for the user's benefit
    echo "<p>Searching for:  " .  $_POST['term'] . "</p>";
    echo "<hr />";

    /* ***********************************************************************
     * SANITIZE USER DATA
     * Use strtolower()  for emails, usernames and other case-sensitive info
     * Use trim() for ALL user-typed data -- even those not required
     * CAUTION:  Radio buttons are a bit different.
     *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
     * ***********************************************************************
     */
    $formdata['term'] = trim($_POST['term']);

    /* ***********************************************************************
    * CHECK EMPTY FIELDS
    * Check for empty data for every required field
    * Do not do for things like apartment number, middle initial, etc.
     * CAUTION:  Radio buttons with 0 as a value = use isset() not empty()
    *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
     * ***********************************************************************
     */

    if (empty($formdata['term'])){
        $errterm = "The term is missing.";
        $errmsg = 1;
    }

    //only go into the try/catch if you have no errors
    if($errmsg == 1)
    {
        echo "<p class='error'>There are errors.  Please make corrections and resubmit.</p>";
    }
    else {
        try {
            //query the data
            $sql = "SELECT * 
                    FROM members 
                    WHERE username
                    LIKE '{$formdata['term']}%' 
                    ORDER BY username";
            //prepares a statement for execution
            $stmt = $pdo->prepare($sql);
            //executes a prepared statement
            $stmt->execute();
            //Returns an array containing all of the result set rows
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) {
                echo "<p class='error'>There are no results.  Please try a different search term.</p>";
            } else {
                //display the results for the user
                echo "<p class='success'>The following results matched your search:</p>";
                //loop through the results and display to the screen
                echo "<table><tr><th>Username</th><th>Member Since</th></tr>";
                foreach ($result as $row) {
                    echo "<tr><td>" . $row['username'] . "</td><td>";
                    echo date("F d, Y", $row['inputdate']);
                    echo "</td></tr>";
                }
                echo "</table>";
                $showform = 0;
            }
        }//try
        catch (PDOException $e) {
            die($e->getMessage());
        }
    } // if errors
}//if post
if($showform ==1) {
    ?>
    <form name="searchform" id="searchform" method="post" action="search.php">
        <label for="term">Search Categories:</label><span class="error">*</span>
        <input name="term" id="term" type="text" />
        <span class="error"><?php if(isset($errterm)){echo $errterm;}?></span>
        <br />
        <input type="submit" name="submit" id="submit" value="submit" />
    </form>
    <?php
}//showform
require_once "footer.inc.php";
?>








