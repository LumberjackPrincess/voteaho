<?php
ini_set('session.gc_maxlifetime', 3600); //sets session time for one hour
session_set_cookie_params(3600); //caches cookie for one hour
session_start();
include 'db.php';

//$section = isset($_GET['section']) ? (int)$_GET['section'] : 1; //NEEDED?

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission and insert data into the database
    if (isset($_POST['input1'])) {
        $input1 = pg_escape_string($_POST['input1']);
    	//echo "Error: " . pg_last_error($conn);
    	
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
	} else {
    	    echo "No voter found with the name: " . htmlspecialchars($input1);
    	    exit;
}
        // Query to delete data from the database
        $query = "DELETE FROM tv.admin WHERE admin.voterid = $1";
        $prepare = pg_prepare($conn, "remAdmin", $query);
        if (!$prepare) {
    	    echo "Error preparing delete query: " . pg_last_error($conn);
    	    exit;
	}
        $result = pg_execute($conn, "remAdmin", array($setVid));
        if (!$result) {	
    	echo "Error executing delete query: " . pg_last_error($conn);
    	exit;
}

        if ($result) {
            // Redirect back to the main page with a success alert
            echo "<script>alert('Admin Removed!'); window.location.href = 'admhome.php';</script>";
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
    <title>Remove Admin</title>
    <link rel="stylesheet" type="text/css" href="VDesign.css">
</head>
<header>
	<?php include 'EFunct.php'; $imagePath = 'Images/Ojibwe_Banner.png'; showImage($imagePath); ?>
</header>
<body>

<h1>Remove an ADMIN login</h1>
<form method="POST">
    <label for="input1">Admin Name:</label>
    <input type="text" id="input1" name="input1" required>
    
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
