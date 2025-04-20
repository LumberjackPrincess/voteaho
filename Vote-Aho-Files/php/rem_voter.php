<?php
ini_set('session.gc_maxlifetime', 3600); //sets session time for one hour
session_set_cookie_params(3600); //caches cookie for one hour
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission and insert data into the database
    if (isset($_POST['input1'])) {
        $input1 = pg_escape_string($_POST['input1']);
    	
    	// GET VOTER ID FROM NAME
    	$getVid = "SELECT voterid, tribalid FROM tv.voter WHERE name = $1";
    	$prepVid = pg_prepare($conn, "voterid", $getVid);
    	$setVidRes = pg_execute($conn, "voterid", array($input1));

    	// Fetch the candidate ID
	$row = pg_fetch_assoc($setVidRes);
	if ($row) {
    	    $setVid = $row['voterid'];
    	    $setTid = $row['tribalid'];
	} else {
    	    echo "No voter found with the name: " . htmlspecialchars($input1);
    	    exit;
}
        // Query to voter table delete data from the database
        $query = "DELETE FROM tv.voter WHERE voter.voterid = $1";
        $prepare = pg_prepare($conn, "remvoter", $query);
        $result = pg_execute($conn, "remvoter", array($setVid));
        
        // Query to delete tribal table data from the database
        $query2 = "DELETE FROM tv.tribal WHERE tribal.tribalid = $1";
        $prepare2 = pg_prepare($conn, "remtrb", $query2);
        $result2 = pg_execute($conn, "remtrb", array($setTid));


        if (($result) && ($result2)) {
            // Redirect back to the main page with a success alert
            echo "<script>alert('Voter Removed!'); window.location.href = 'admhome.php';</script>";
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
    <title>Remove a Voter</title>
    <link rel="stylesheet" type="text/css" href="VDesign.css">
</head>
<header>
	<?php include 'EFunct.php'; $imagePath = 'Images/Ojibwe_Banner.png'; showImage($imagePath); ?>
</header>
<body>

<h1>Remove a Voter</h1>
<form method="POST">
    <label for="input1">Voter Name:</label>
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
