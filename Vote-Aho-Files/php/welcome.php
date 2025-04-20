<?php
ini_set('session.gc_maxlifetime', 3600); // Set session time for one hour
session_set_cookie_params(3600); // Cache cookie for one hour
session_start();
include 'db.php';
include 'EFunct.php'; // Include the new functions file

$Unique = getElections($conn); // Get elections using the function
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Vote-Aho!</title>
    <link rel="stylesheet" type="text/css" href="VDesign.css">
</head>
<header>
	<?php $imagePath = 'Images/Ojibwe_Banner.png'; showImage($imagePath); ?>
	</header>
<body>

<div class="container">
    <h1>Elect all your Aunties and Uncles!</h1>
    
    <?php if (count($Unique) > 0): ?>
        <?php foreach ($Unique as $election): ?>
            <div class="election-box">
                <h2><?php echo htmlspecialchars($election['position']); ?></h2>
                <p><strong>Start Date:</strong> <?php echo htmlspecialchars($election['elecstart']); ?></p>
                <p><strong>End Date:</strong> <?php echo htmlspecialchars($election['elecend']); ?></p>
                <form action="castvote.php" method="POST">
                    <input type="hidden" name="election_name" value="<?php echo urlencode($election['position']); ?>">
                    <?php
                        // Get vote counts for each candidate in the current election using the function
                        $voteData = getVoteCounts($conn, $election['position']);
                        $person = $voteData['person'];
                        $wins = $voteData['wins'];
                    ?>
                    <?php 
                    $uniqueBarChartId = 'barChart-' . htmlspecialchars($election['position']) . '-' . uniqid();
                    ?>
                    <canvas id="<?php echo $uniqueBarChartId; ?>"></canvas>
                    <button class="btn signin" type="submit">Vote</button>
                </form>
            </div>
            
            <script>
		    var person = <?php echo json_encode($person); ?>;
		    var wins = <?php echo json_encode($wins); ?>;
		    var barCtx = document.getElementById('<?php echo $uniqueBarChartId; ?>').getContext('2d');
		    new Chart(barCtx, {
		        type: 'bar',
		        data: {
		            labels: person,
		            datasets: [{
		                label: 'Vote Count',
		                data: wins,
		                backgroundColor: 'rgba(138, 34, 210, 0.7)',
		                borderColor: 'rgba(138, 34, 210, 1)',
		                borderWidth: 1
		            }]
		        },
		        options: {
		            responsive: true,
		            scales: {
		                y: {
		                    beginAtZero: true,
		                    precision: 0
		                }
		            }
		        }
		    });
            </script>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No elections found.</p>
    <?php endif; ?>
</div>

<a class="btn admsin" href="logout.php">Logout</a>
</body>
<div class="footer">
<p>Vote-Aho &copy; 2025 - All Rights Reserved.</p>
</div>

</html>
