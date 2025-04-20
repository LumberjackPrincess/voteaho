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
        $input3 = pg_escape_string($_POST['input3']);
        $input4 = pg_escape_string($_POST['input4']);
       
           	// GET Cand ID FROM NAME
    	$getVid = "SELECT candid FROM tv.candidate WHERE candidate.candname = $1";
    	$prepVid = pg_prepare($conn, "voteid", $getVid);
    	if (!$prepVid) {
    	echo "Error preparing query: " . pg_last_error($conn);
    	exit;
}
    	$setVidRes = pg_execute($conn, "voteid", array($input2));
    	if (!$setVidRes) {
    	echo "Error executing query: " . pg_last_error($conn);
    	exit;
}
    	// Fetch the voter ID
	$row = pg_fetch_assoc($setVidRes);
	if ($row) {
    	    $setVid = $row['candid']; // Get the candid from the result
    	    //echo "Voter ID: " . $setVid . "<br>";
	} else {
    	    echo "No Candidate found with the name: " . htmlspecialchars($input1);
    	    exit;
}
       
        // CREATE ElecID #
        $crtE = "SELECT MAX(elecid) FROM tv.election";
    	$resE = pg_query($conn, $crtE);
    	$rowE = pg_fetch_row($resE);
    	$newEid = $rowE[0]+1;

        // Query to insert election data into the database
        $query = "INSERT INTO tv.election (elecid, candid, position, elecstart, elecend) VALUES ($1, $2, $3, $4, $5)";
        $prepare = pg_prepare($conn, "newE", $query);
        if (!$prepare) {
    	    echo "Error preparing insert query: " . pg_last_error($conn);
    	    exit;
	}
        $result = pg_execute($conn, "newE", array($newEid, $setVid, $input1, $input3, $input4));
        if (!$result) {	
    	echo "Error executing insert query: " . pg_last_error($conn);
    	exit;
}

        if ($result) {
            // Redirect back to the main page with a success alert
            echo "<script>alert('New Election Added!'); window.location.href = 'admhome.php';</script>";
            exit;
        } else {
            //echo "An error occurred: " . pg_last_error($conn);
            echo "<script>alert('ERROR'); window.location.href = 'admhome.php';</script>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Election</title>
    <link rel="stylesheet" type="text/css" href="VDesign.css">
</head>
<header>
	<?php include 'EFunct.php'; $imagePath = 'Images/Ojibwe_Banner.png'; showImage($imagePath); ?>
</header>
<body>

<h1>Create a new ELECTION</h1>
<form method="POST">
    <label for="input1">Position:</label>
    <input type="text" id="input1" name="input1" required>
    
    <label for="input2">Candidate:</label>
    <input type="text" id="input2" name="input2" required>
    
    <label for="input3">Start Date:</label>
    <input type="date" id="input3" name="input3" required>
    
    <label for="input4">End Date:</label>
    <input type="date" id="input4" name="input4" required>
    
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
