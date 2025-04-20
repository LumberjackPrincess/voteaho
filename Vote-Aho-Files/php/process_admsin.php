<?php
ini_set('session.gc_maxlifetime', 3600); //sets session time for one hour
session_set_cookie_params(3600); //caches cookie for one hour
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT voter.name, voter.email, admin.adminpass FROM tv.voter INNER JOIN tv.admin on voter.voterid = admin.voterid WHERE email = $1";
    $result = pg_query_params($conn, $query, array($email));

    if (pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);
        //echo "Stored pass: " . $user['adminpass'] . "<br>";
        //echo "Given pass: " . $password . "<br>";
	if ($password === $user['adminpass']) {
        //if (password_verify($password, $user['pass'])) {
            $_SESSION['user_id'] = $user['voterid'];
            $_SESSION['user_name'] = $user['name'];
            //echo "Login successful!<br>";
            //echo "Boozhoo, " . $_SESSION['user_name'] . "!<br>"; 
            //echo "<a href='admhome.php'>Lead us down the red road, Chief!</a>";
            //sleep(2); // Wait for 5 seconds
            header("Location: admhome.php");  //CHANGE THIS AFTER TESTING
            exit();
        } else {
            //echo "Incorrect password. <a href='admsin.php'>Fok, Try Again Den</a>";
            //echo "Error: " . pg_last_error($conn);
            //sleep(5); // Wait for 5 seconds
            //header("Location: admsin.php");
            echo "<script>alert('Wrong password... You been drinking again?'); window.location.href = 'index.php';</script>";
            exit();
        }
    } else {
        //echo "Account not found... like my old snag. <a href='admsin.php'>Sko back!</a><br>";
        //echo "<a href='index.php'>You've had enough to drink, eeee!</a>";
        //sleep(5); // Wait for 5 seconds
        //header("Location: admsin.php");
        echo "<script>alert('Account not found...just like your old man'); window.location.href = 'index.php';</script>";
        exit();
    }

    pg_close($conn);
}
?>
