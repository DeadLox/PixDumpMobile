<?php
include_once 'pdo.php';

// Traitement du formulaire
if (isset($_POST) && !empty($_POST)) {
	extract($_POST);
	if (isset($num) && !empty($num) && isset($numVote) && !empty($numVote)) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$req = $pdo->prepare("SELECT COUNT(*) AS nbVote FROM vote WHERE numPixDump = :numPixDump AND numImage = :numImage AND ip = :ip");
		$req->bindParam(':numPixDump', $num);
		$req->bindParam(':numImage', $numVote);
		$req->bindParam(':ip', $ip);	
		$req->execute();
		$data = $req->Fetch();
		if ($data['nbVote'] == 0) {
			$req = $pdo->prepare("INSERT INTO vote (numPixDump, numImage, ip) VALUES (:numPixDump, :numImage, :ip)");
			$req->bindParam(':numPixDump', $num);
			$req->bindParam(':numImage', $numVote);
			$req->bindParam(':ip', $ip);	
			$req->execute();
			echo 'ok';
		} else {
			echo 'ko';
		}
	}
}
?>