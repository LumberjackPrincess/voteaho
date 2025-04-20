<?php
ini_set('session.gc_maxlifetime', 3600); //sets session time for one hour
session_set_cookie_params(3600); //caches cookie for one hour
// this ^ is needed to retain the user_id and user_name value to use in submitvote.php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT voterid, name, pass FROM tv.voter WHERE email = $1";
    $result = pg_query_params($conn, $query, array($email));

    if (pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);
        
        //echo "Stored pass: " . $user['pass'] . "<br>";
        //echo "Given pass: " . $password . "<br>";
	if ($password === $user['pass']) {
        //if (password_verify($password, $user['pass'])) {
            $_SESSION['user_id'] = $user['voterid'];
            $_SESSION['user_name'] = $user['name'];
            //echo "Login successful!<br>";
            //echo "Aho, " . $_SESSION['user_name'] . "!<br>"; 
            //echo "<a href='welcome.php'>Go where the path takes you!</a>";
            //sleep(1); // Wait for 5 seconds
            header("Location: welcome.php");
            //echo "<script>alert('Aho! '); window.location.href = 'index.php';</script>";
            exit();
        } else {
            //echo "Incorrect password. <a href='signin.php'>Try Again</a>";
            //sleep(5); // Wait for 5 seconds
            //header("Location: signin.php");
            echo "<script>alert('Wrong password, ya dummy!'); window.location.href = 'signin.php';</script>";
            exit();
        }
    } else {
        //echo "No account found with that email. <a href='signup.php'>Join the Tribe!</a><br>";
        //echo "<a href='index.php'>Go back to where you came from, holay!</a>";
        //sleep(1); // Wait for 10 seconds
        //header("Location: index.php");
        echo "<script>alert('Account not found.'); window.location.href = 'index.php';</script>";
        exit();
    }

    pg_close($conn);
}
?>
