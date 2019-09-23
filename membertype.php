<?php
$pagename = "Change Member Type - Details";
require_once "header.inc.php";
checkAdmin();

$showform =1;
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

if($_SERVER['REQUEST_METHOD'] == "POST") {

    try {
        $sql = "UPDATE members 
                    SET membertype = :membertype
                    WHERE ID = :ID";
        $stmt = $pdo->prepare($sql);
        //binds the actual value of $_GET['ID'] to
        $stmt->bindValue(':membertype', $_POST['membertype']);
        $stmt->bindValue(':ID', $ID); //notice this is NOT submitted from the form
        //executes a prepared statement
        $stmt->execute();
        //hide the form
        $showform = 0;
        //provide useful confirmation to user
        echo "<p id='content' id='box1'> Access Level has been changed. 
                 <a href='membermanage.php'>Return to member manage page</a>?</p>";
    }
    catch (PDOException $e)
    {
        die( $e->getMessage() );
    }
}

if($showform == 1){

    //query the data
    $sql = "SELECT membertype, ID FROM members WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $_GET['ID']);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //display to the screen
        echo "<p id='content' id='box1'> Member ID Num: {$row['ID']} is currently a "?><?php if($row['membertype']== 1){echo "<b>ADMIN</b>";}else{echo "User";}?> <?php echo"</p> ";
        echo "";
        echo "<p id='content' id='box1'>Would you like to change their member type to "?><?php if($row['membertype']== 1){echo "User";}else{echo "<b>ADMIN</b>";}?> <?php echo"?</p> ";
        echo "";
        echo "";
        ?>
        <form id="changmemtype" name="changmemtype" method="post" action="membertype.php">
            <div id='content' id='box1'>
                <input type="hidden" id="ID" name="ID" value="<?php echo $_GET['ID'];?>" />
                <input type="hidden" id="membertype" name="membertype" value="<?php if($row['membertype']==1){echo $row['membertype'] =0;}else{echo $row['membertype']=1;};?>"/>
                <input type="submit" id="change" name="change" value="YES" />
                <input type="button" id="nochange" name="nochange" value="NO" onClick="window.location='membermanage.php'"/>
            </div>
        </form>
    <?php
    }
require_once "footer.inc.php";

?>