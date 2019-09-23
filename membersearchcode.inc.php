<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/21/2019
 * Time: 5:39 PM
 */
include_once "header.inc.php";
try{

    //
    if(isset($_REQUEST["term"])){
        $sql="SELECT * 
            FROM members
            WHERE username
            LIKE :term
            ORDER BY username";

        $stmt = $pdo->prepare($sql);
        $term = $_REQUEST["term"].'%';
        $stmt->bindParam(":term", $term);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($stmt->rowCount() == 0)
        {
            echo "<p class='error'>There are no result. Please try a different search term.</p>";
        }
        else
            {
            echo "<p class='success'>The following results matched with your search:</p>";
            echo "<table><tr><th>ID</th><th>First</th><th>Username</th><th>Email</th></tr>";
            foreach($result as $row){
                echo "<tr><td>" . $row['ID'] . "</td><td>" . $row['fname'] . "</td><td><a href='memberdetails.php'>" . $row['username'] . "</a></td><td>" . $row['username'] . "</td><td>" . $row['email'] . "</td></tr>";
            }
            echo "</table>";
        }
    }
}
catch(PDOException $e){
    die("ERROR: Could not able to execute $sql . " . $e->getMessage());
}
unset($stmt);
unset($pdo);



?>