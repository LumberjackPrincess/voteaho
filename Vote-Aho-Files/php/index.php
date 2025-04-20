<html>
	<head>
		
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Vote-Aho Website</title>
		<link rel="stylesheet" type="text/css" href="VDesign.css">
		
	</head>
	
	<header>
	<?php include 'EFunct.php'; $imagePath = 'Images/Ojibwe_Banner.png'; showImage($imagePath); ?>
	</header>
	
	<body>
		<h1>Aho!</h1>
		<?php
        echo "<p>Welcome to the <strong>Vote-Aho</strong> website!</p>";
    	?>
    	<button class="btn signin" onclick="location.href='signin.php'">Sign In</button>
    	<button class="btn signup" onclick="location.href='signup.php'">Sign Up</button>
        <button class="btn admsin" onclick="location.href='admsin.php'">Admin</button>

	</body>
	<div class="footer">
<p>Vote-Aho &copy; 2025 - All Rights Reserved.</p>
</div>
</html>
