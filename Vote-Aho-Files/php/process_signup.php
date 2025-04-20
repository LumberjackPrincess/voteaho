<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$name = htmlspecialchars($_POST['name']);
    $tribalid = $_POST['tribalid'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $birthday =$_POST['birthday'];
    
    $query = "SELECT regdate FROM tv.tribal WHERE tribalid = $1";
    $check_tribID = pg_query_params($conn, $query, array($tribalid));

    if (pg_num_rows($check_tribID) > 0) {
        //echo "Account already registered, you weenuk.<br> <a href='signup.php'>Try Again</a>";
        //sleep(5); // Wait for 5 seconds
        //header("Location: signup.php");
        echo "<script>alert('Account already registered, you weenuk!'); window.location.href = 'signup.php';</script>";
        exit();
    } else {
    
    	//inserts info into tribal table we must create tribal ID first because it's used elsewhere
        $query2 = "INSERT INTO tv.tribal (tribalid, regdate) VALUES ($1, CURRENT_DATE)"; //works
        $result2 = pg_query_params($conn, $query2, array($tribalid));
        
    	//get max id of voter id cuz im dumb and used int instead of serial
    	$query0 = "SELECT MAX(voterid) FROM tv.voter";
    	$result0 = pg_query($conn, $query0);
    	$row = pg_fetch_row($result0);
    	$newid = $row[0]+1;
    	//inserts info into voter table
        $query1 = "INSERT INTO tv.voter (voterid, tribalid, name, email, pass, birthday) VALUES ($1, $2, $3, $4, $5, $6)"; 
        
        $prepared_result = pg_prepare($conn, "test_query", $query1);
        
        $result = pg_execute($conn, "test_query", array($newid, $tribalid, $name, $email, $password, $birthday)); //testing this
        
   

	// verifies that both tables were updated successfully
        if ($result and $result2) {
            //echo "Sign-up successful! <a href='signin.php'>Skoden</a>";

            //sleep(5); // Wait for 5 seconds
            //header("Location: signin.php");
            echo "<script>alert('Account created, thank Creator!'); window.location.href = 'signin.php';</script>";
            exit();
        } else {
            //echo "Error: " . pg_last_error($conn);
            //echo "<a href='index.php'>Gwaan Back</a>";
            //sleep(5); // Wait for 5 seconds
            //header("Location: index.php");
            echo "<script>alert('ERROR'); window.location.href = 'signup.php';</script>";
            exit();
        }
    }

    pg_close($conn);
}
?>
