<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" type="text/css" href="VDesign.css">
</head>
<header>
	<?php include 'EFunct.php'; $imagePath = 'Images/Ojibwe_Banner.png'; showImage($imagePath); ?>
	</header>
<body>

    <h2>Sign In</h2>
    <form action="process_signin.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button class="btn signin" type="submit">Sign In</button>
        
    </form>
    <br>
<a class="btn admsin" href="index.php">Cancel</a>
</body>
<div class="footer">
<p>Vote-Aho &copy; 2025 - All Rights Reserved.</p>
</div>
</html>
