<?php
ini_set('session.gc_maxlifetime', 3600); //sets session time for one hour
session_set_cookie_params(3600); //caches cookie for one hour
session_start();
include 'db.php';

//if (!isset($_SESSION['user_id'])) {
//    header("Location: signin.php");
//    exit();
//}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote-Aho!</title>
    <link rel="stylesheet" type="text/css" href="VDesign.css">

</head>
<header>
	<?php include 'EFunct.php'; $imagePath = 'Images/Ojibwe_Banner.png'; showImage($imagePath); ?>
</header>
<body>

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <p>You are now logged in as an <strong>ADMIN</strong> to the <strong>VOTE-AHO</strong> website!</p>
    

    <div class="section">
        <div class="section-title">Edit Admin Accounts</div>
        <button type="button" onclick="window.location.href='add_adm.php'">Add</button>
        <button2 type="button" onclick="window.location.href='rem_adm.php'">Remove</button>
    </div>
<br>
    <div class="section">
        <div class="section-title">Edit Candidate Info</div>
        <button type="button" onclick="window.location.href='add_cand.php'">Add</button>
        <button2 type="button" onclick="window.location.href='rem_cand.php'">Remove</button>
    </div>
<br>
    <div class="section">
        <div class="section-title">Edit Election Info</div>
        <button type="button" onclick="window.location.href='add_elec.php?section=3'">Add</button>
        <button2 type="button" onclick="window.location.href='rem_elec.php?section=3'">Remove</button>
    </div>
<br>
    <div class="section">
        <div class="section-title">Edit Voter Info</div>
        <button type="button" onclick="window.location.href='add_vot.php'">Add</button>
        <button2 type="button" onclick="window.location.href='rem_voter.php'">Remove</button>
    </div>
    <br>
    <iframe
    src="http://localhost:3000/public/dashboard/24e806c8-6d1b-4b57-a159-541f5f48ce8e"
    frameborder="0"
    width="800"
    height="600"
    allowtransparency
    ></iframe>
    <br>
    <a class="btn admsin" href="logout.php">Logout</a>
    <div class="footer">
    <p>Vote-Aho &copy; 2025 - All Rights Reserved.</p>
    </div>

</body>
</html>
