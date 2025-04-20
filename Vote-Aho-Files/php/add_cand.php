<?php
ini_set('session.gc_maxlifetime', 3600); //sets session time for one hour
session_set_cookie_params(3600); //caches cookie for one hour
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission and insert data into the database
    if (isset($_POST['input1'])) {
        $input1 = pg_escape_string($_POST['input1']);
        
        // CREATE AdminID #
        $crtCan = "SELECT MAX(candid) FROM tv.candidate";
    	$resCan = pg_query($conn, $crtCan);
    	$rowCan = pg_fetch_row($resCan);
    	$remCanid = $rowCan[0]+1;
    	
        // Query to insert candidate data into the database
        $query = "INSERT INTO tv.candidate (candid, candname) VALUES ($1, $2)";
        $prepare = pg_prepare($conn, "newCan", $query);
        if (!$prepare) {
    	    echo "Error preparing insert query: " . pg_last_error($conn);
    	    exit;
	}
        $result = pg_execute($conn, "newCan", array($remCanid, $input1));
        if (!$result) {	
    	echo "Error executing insert query: " . pg_last_error($conn);
    	exit;
}

        if ($result) {
            // Redirect back to the main page with a success alert
            echo "<script>alert('New Candidate Added!'); window.location.href = 'admhome.php';</script>";
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
    <title>Add Candidate</title>
    <link rel="stylesheet" type="text/css" href="VDesign.css">
</head>
<header>
	<?php include 'EFunct.php'; $imagePath = 'Images/Ojibwe_Banner.png'; showImage($imagePath); ?>
</header>
<body>

<h1>Create a new CANDIDATE</h1>
<form method="POST">
    <label for="input1">Candidate Name:</label>
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
