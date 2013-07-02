<?php
	$nbImage = sizeof($listPixDump);

	// Traitement du formulaire
	if (isset($_POST) && !empty($_POST)) {
		extract($_POST);
	}
?>
<div class="form">
	<h1>Meilleur image du PixDump</h1>
	<label for="numVote">Numéro de l'image:</label>
	<input type="hidden" id="pixDumpNum" value="<?php echo $num; ?>" />
	<select id="numVote">
		<?php for ($i=1; $i <= $nbImage; $i++) { ?>
			<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
		<?php } ?>
	</select>
	<br/>
	<a href="" class="button">Voter</a>
</div>
<script>
	$(document).ready(function(){
		// Soumission du formulaire
		$('.form .button').on('click', function(){
			var num = $('.form #pixDumpNum').val();
			var numVote = $('.form #numVote').val();
			console.log(numVote);
			$.ajax({
				type: 'POST',
				url:  'form.php',
				data: 'num='+num+'&numVote='+numVote,
				success: function(msg){
					alert(ok);
				}
			})
			return false;
		});
	})
</script>