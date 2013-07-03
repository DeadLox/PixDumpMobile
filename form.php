<?php
	include_once 'pdo.php';

	// Vérifie si un vote a déjà été soumis
	$ip = $_SERVER['REMOTE_ADDR'];
	$req = $pdo->prepare("SELECT COUNT(*) AS nbVote FROM vote WHERE numPixDump = :numPixDump AND ip = :ip");
	$req->bindParam(':numPixDump', $num);
	$req->bindParam(':ip', $ip);	
	$req->execute();
	$data = $req->Fetch();
	if ($data['nbVote'] == 0) {
		$showForm = true;
	// Si il a déjà voté, on affiche les résultats du vote
	} else {
		$showForm = false;
		$req = $pdo->prepare("SELECT COUNT(*) AS nbVote, numImage FROM vote WHERE numPixDump = :numPixDump GROUP BY numImage");
		$req->bindParam(':numPixDump', $num);
		$req->execute();
		$votes = $req->FetchAll();
		$nbTotalVotes = 0;
		foreach ($votes as $vote) {
			$nbTotalVotes += $vote['nbVote'];
		}
	}
	$nbImage = sizeof($listPixDump);
?>
<div class="form">
	<h1>Meilleure image du PixDump</h1>
	<div class="msgVote alert alert-info" style="display:none;"></div>
	<a href="" class="button buttonStat" style="display:none;">Voir les votes</a>
	<?php if ($showForm) { ?>
		<div class="formVote">
			<label for="numVote">Numéro de l'image:</label>
			<input type="hidden" id="pixDumpNum" value="<?php echo $num; ?>" />
			<select id="numVote">
				<?php for ($i=1; $i <= $nbImage; $i++) { ?>
					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
			<br/>
			<a href="" class="button buttonVote">Voter</a>
		</div>
	<?php } else { ?>
		<?php if (sizeof($votes) > 0) { ?>
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
		<?php } ?>
	<?php } ?>
</div>
<script>
	$(document).ready(function(){
		// Soumission du formulaire
		$('.form .buttonVote').on('click', function(){
			var num = $('.form #pixDumpNum').val();
			var numVote = $('.form #numVote').val();
			$.ajax({
				type: 'POST',
				url:  'ajaxForm.php',
				data: 'num='+num+'&numVote='+numVote,
				success: function(msg){
					$('.formVote').fadeOut(function(){
						var msgDiv = $('.msgVote');
						if (msg =  'ok') {
							msgDiv.text('Votre vote a été pris en compte.').fadeIn();
							$('.buttonStat').fadeIn();
						} else {
							msgDiv.text('Une erreur est survenue lors du vote.').fadeIn();
						}
					});
				}
			})
			return false;
		});
		$('.form .buttonStat').on('click', function(){
			var num = $('.form #pixDumpNum').val();
			$.ajax({
				type: 'POST',
				url:  'ajaxVote.php',
				data: 'num='+num,
				success: function(msg){
					$('.msgVote').hide();
					$('.buttonStat').hide();
					$('.form').append(msg);
				}
			})
			return false;
		});
	})
</script>