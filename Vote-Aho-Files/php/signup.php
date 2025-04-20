<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="VDesign.css">

</head>
<header>
	<?php include 'EFunct.php'; $imagePath = 'Images/Ojibwe_Banner.png'; showImage($imagePath); ?>
	</header>
<body>

    <h2>Sign Up</h2>
    <form class="form2" action="process_signup.php" method="POST">
    <div class='form-group'>
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>
        </div>
	<br>
	<div class='form-group'>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        </div>
        <br>
        <div class='form-group'>
        <label for="tribalid">Tribal ID #</label>
        <input type="text" id="tribalid" name="tribalid" required>
        </div>
	<br>
	<div class='form-group'>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        </div>
        <br>
        <div class='form-group'>
        <label for="birthday">Birthday:</label>
        <input type="date" id="birthday" name="birthday" required>
        </div>
	<br>
        <button class="btn signup" type="submit">Skoden</button>
        
    </form>
        <a class="btn admsin" href="index.php">Cancel</a>
</body>
<div class="footer">
    <p>Vote-Aho &copy; 2025 - All Rights Reserved.</p>
    </div>
</html>
