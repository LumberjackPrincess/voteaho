<?php
ini_set('session.gc_maxlifetime', 3600); //sets session time for one hour
session_set_cookie_params(3600); //caches cookie for one hour
session_start();
include 'db.php';

//$section = isset($_GET['section']) ? (int)$_GET['section'] : 1; //NEEDED?

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission and insert data into the database
    if (isset($_POST['input1']) && isset($_POST['input2'])) {
        $input1 = pg_escape_string($_POST['input1']);
        $input2 = pg_escape_string($_POST['input2']);
        
        // CREATE AdminID #
        $crtAdm = "SELECT MAX(adminid) FROM tv.admin";
    	$resAdm = pg_query($conn, $crtAdm);
    	$rowAdm = pg_fetch_row($resAdm);
    	$newAdmid = $rowAdm[0]+1;
    	
    	//echo "adminid: " . $newAdmid . "<br>";
    	//echo "Error: " . pg_last_error($conn);
    	//echo "input1: " . $input1 . "<br>";
    	// GET VOTER ID FROM NAME
    	$getVid = "SELECT voterid FROM tv.voter WHERE voter.name = $1";
    	$prepVid = pg_prepare($conn, "voteid", $getVid);
    	if (!$prepVid) {
    	echo "Error preparing query: " . pg_last_error($conn);
    	exit;
}
    	$setVidRes = pg_execute($conn, "voteid", array($input1));
    	if (!$setVidRes) {
    	echo "Error executing query: " . pg_last_error($conn);
    	exit;
}
    	// Fetch the voter ID
	$row = pg_fetch_assoc($setVidRes);
	if ($row) {
    	    $setVid = $row['voterid']; // Get the voterid from the result
    	    //echo "Voter ID: " . $setVid . "<br>";
	} else {
    	    echo "No voter found with the name: " . htmlspecialchars($input1);
    	    exit;
}
//    	echo "prepvid: " . $setVid . "";
    	

        // Query to insert data into the database for each section
        $query = "INSERT INTO tv.admin (adminid, voterid, adminpass) VALUES ($1, $2, $3)";
        $prepare = pg_prepare($conn, "newAdmin", $query);
        if (!$prepare) {
    	    echo "Error preparing insert query: " . pg_last_error($conn);
    	    exit;
	}
        $result = pg_execute($conn, "newAdmin", array($newAdmid, $setVid, $input2));
        if (!$result) {	
    	echo "Error executing insert query: " . pg_last_error($conn);
    	exit;
}

        if ($result) {
            // Redirect back to the main page with a success alert
            echo "<script>alert('New Admin Added!'); window.location.href = 'admhome.php';</script>";
            exit;
        } else {
            echo "An error occurred: " . pg_last_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link rel="stylesheet" type="text/css" href="VDesign.css">
</head>
<header>
	<?php include 'EFunct.php'; $imagePath = 'Images/Ojibwe_Banner.png'; showImage($imagePath); ?>
</header>
<body>

<h1>Create a new ADMIN login</h1>
<form method="POST">
    <label for="input1">Admin Name:</label>
    <input type="text" id="input1" name="input1" required>
    
    <label for="input2">Admin Password:</label>
    <input type="text" id="input2" name="input2" required>
    
    <button type="submit">Skoden</button>
</form>

</body>
<div>
<a class="btn admsin" href="admhome.php">Cancel</a>
</div>
<div class="footer">
    <p>Vote-Aho &copy; 2025 - All Rights Reserved.</p>
    </div>
</html>
