<?php
ini_set('session.gc_maxlifetime', 3600); //sets session time for one hour
session_set_cookie_params(3600); //caches cookie for one hour
session_start();
include 'db.php';

// Check if election name and candidate choice were submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["election_name"]) && isset($_POST["voter_choice"])) {
        $electionName = $_POST["election_name"];  // Election position name
        $candidateName = $_POST["voter_choice"];  // Voter's selected candidate
        $voterId = $_SESSION['user_id'];  // Assuming you store the user id in the session

	//echo "Voter ID: " . $voterId . "<br>"; // This is to make sure i have a voter ID to insert

        // Sanitize inputs
        //$electionName = pg_escape_string($electionName);
        //$candidateName = pg_escape_string($candidateName);

	

	// Query to get the election ID based on the election name (position)
        $QElectionId = "SELECT elecid FROM tv.election WHERE position = $1";
        $Result = pg_query_params($conn, $QElectionId, array($electionName));

        if (!$Result) {
            echo "An error occurred while fetching election ID.\n";
            echo "Error: " . pg_last_error($conn);
            exit;
        }

        // Fetch the election ID from the result
        $electionData = pg_fetch_assoc($Result);
        if (!$electionData) {
            echo "No such election found.\n";
            exit;
        }

        $electionId = $electionData['elecid'];  // Get the election ID

	// Check if the voter has already voted in the castvote table
        //$QCheckVote = "SELECT * FROM tv.castvote WHERE voterid = $1";
        $QCheckVote = "SELECT castvote.voterid, castvote.elecid FROM tv.castvote WHERE voterid = $1 and elecid = $2;";
        $VoteResult = pg_query_params($conn, $QCheckVote, array($voterId, $electionId));

        if (!$VoteResult) {
            echo "An error occurred while checking if the voter has already voted.\n";
            echo "Error: " . pg_last_error($conn);
            exit;
        }

        // If the voter has already voted, display an error message and provide a link back to welcome.php
        if (pg_num_rows($VoteResult) > 0) {
            //$voteData = pg_fetch_assoc($VoteResult);
            //$voteDate = $voteData['datecast']; 
            //echo "<p>You already voted on " . $voteDate . "! <br><a href='welcome.php'>Click here to go back home.</a> <br>You cheat worse than at bingo!</p>";
            echo "<script>alert('You already voted on this. You cheat worse than at bingo!'); window.location.href = 'welcome.php';</script>";
            exit;
        }
	
        // Query to get the candidate's ID from the name
        $QCandidateId = "SELECT candid FROM tv.candidate WHERE candname = $1";
        $Result = pg_query_params($conn, $QCandidateId, array($candidateName));

        if (!$Result) {
            echo "An error occurred while fetching candidate ID.\n";
            echo "Error: " . pg_last_error($conn);
            exit;
        }

        // Fetch the candidate ID from the result
        $candidateData = pg_fetch_assoc($Result);
        if (!$candidateData) {
            echo "No such candidate found.\n";
            exit;
        }

        $candidateId = $candidateData['candid'];

	//create new castid cuz dumbdumb
	$Q1 = "SELECT MAX(castid) FROM tv.castvote";
	$Q2 = pg_query($conn, $Q1);
	$Q3 = pg_fetch_row($Q2);
	$Q4 = $Q3[0]+1;
	
	//set date variable to insert to db
	$Date = date('Y-m-d');
        // Insert the vote into the database
        $QInsertVote = "INSERT INTO tv.castvote (castid, voterid, candid, elecid, datecast) VALUES ($1, $2, $3, $4, $5)";
        $InsertResult = pg_query_params($conn, $QInsertVote, array($Q4, $voterId, $candidateId, $electionId, $Date));

        if (!$InsertResult) {
            echo "An error occurred while submitting your vote.\n";
            echo "Error: " . pg_last_error($conn);
            exit;
        }

        // If the vote was successfully inserted, show a success message
        //echo "Your vote for " . htmlspecialchars($candidateName) . " in the " . htmlspecialchars($electionName) . " election has been submitted successfully!<br>";
        //echo "<a href='welcome.php'>Land Back</a>";
        echo "<script>alert('Your vote has been counted. Aho!'); window.location.href = 'welcome.php';</script>";
        //echo "Error: " . pg_last_error($conn);
    } else {
        echo "Invalid request. Please try again.";
    }
} else {
    // If the request method is not POST, show an error
    echo "Invalid request method.";
}

?>
