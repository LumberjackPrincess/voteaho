<?php
function getElections($conn) {
    // Initialize the result array and the unique positions array
    $Result = [];
    $Unique = [];

    // Query to get election data where the date is within the range
    $Qelecid = "SELECT election.position, election.elecend, election.elecstart FROM tv.election WHERE election.elecstart < CURRENT_DATE AND election.elecend > CURRENT_DATE";
    $Relecid = pg_query($conn, $Qelecid);
    if ($Relecid) {
        $Result = pg_fetch_all($Relecid);
    } else {
        // Handle the case where the query failed
        return [];
    }

    // Remove duplicate positions
    foreach ($Result as $election) {
        if (!in_array($election['position'], array_column($Unique, 'position'))) {
            $Unique[] = $election;
        }
    }

    return $Unique;
}

function getVoteCounts($conn, $position) {
    $person = [];
    $wins = [];

    $Query = "SELECT castvote.candid, count(castvote.candid) as count, 
              election.position, candidate.candname 
              FROM tv.castvote 
              INNER JOIN tv.election 
              ON castvote.candid = election.candid
              INNER JOIN tv.candidate
              ON castvote.candid = candidate.candid
              WHERE election.position = $1
              GROUP BY castvote.candid, election.position, candidate.candname";

    $Rezult = pg_query_params($conn, $Query, array($position));

    if ($Rezult) {
        while ($row = pg_fetch_assoc($Rezult)) {
            $person[] = $row['candname'];  // Candidate names
            $wins[] = (int)$row['count'];  // Vote count for each candidate
        }
    } else {
        // Handle the case where the query failed
        return ['person' => [], 'wins' => []];
    }

    return ['person' => $person, 'wins' => $wins];
}

function showImage($path) {
	$safePath = htmlspecialchars($path, ENT_QUOTES, 'utf-8');
	if (file_exists($path)) {
		echo "<img src=\"$safePath\" alt=\"image\" style=\"max-width: 100%; height: auto;\">";
	} else {
		echo "<p>Image not found: $safePath</p>";
	}
}
?>
