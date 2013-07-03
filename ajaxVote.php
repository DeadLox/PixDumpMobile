<?php
include_once 'pdo.php';

if (isset($_POST) && !empty($_POST)) {
	extract($_POST);
	$req = $pdo->prepare("SELECT COUNT(*) AS nbVote, numImage FROM vote WHERE numPixDump = :numPixDump GROUP BY numImage");
	$req->bindParam(':numPixDump', $num);
	$req->execute();
	$votes = $req->FetchAll();
	$nbTotalVotes = 0;
	foreach ($votes as $vote) {
		$nbTotalVotes += $vote['nbVote'];
	}
	if (sizeof($votes) > 0) { ?>
		<ul class="votesStat">
			<?php
			foreach ($votes as $key => $vote) { 
				$widthBar = round((100 / $nbTotalVotes) * $vote['nbVote']);
				?>
				<li>
					<label>Image <?php echo $vote['numImage']; ?></label>
				    <div class="progress progress-striped active">
				    	<div class="bar" style="width: <?php echo $widthBar; ?>%;"><?php echo $widthBar.'% ('.$vote['nbVote'].')'; ?></div>
				    </div>
				</li>
			<?php } ?>
		</ul>
	<?php }
}
?>