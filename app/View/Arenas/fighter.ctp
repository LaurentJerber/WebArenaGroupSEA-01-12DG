<section class="left half" id="createFighter">
	<?php if (isset($fighter)) { ?>
	<h2>Combattant actuel</h2>
	<p><strong>Nom : </strong> <?php echo $fighter['name'];?></p>
	<p><strong>Niveau : </strong> <?php echo floor($fighter['level'] / 4);?></p>
	<p><strong>Expérience non utilisé : </strong> <?php echo $fighter['xp'];?></p>
	<p><strong>Compétence de vue : </strong> <?php echo $fighter['skill_sight'];?> pts</p>
	<p><strong>Compétence de force : </strong> <?php echo $fighter['skill_strength'];?> pts</p>
	<p><strong>Santé actuelle : </strong> <?php echo $fighter['current_health'];?> pts</p>
	<p><strong>Santé maximum : </strong> <?php echo $fighter['skill_health'];?> pts</p>
	<p><strong>Avatar :</strong></p>
	
	<?php if (strlen($this -> Html -> image('avatars/' . $fighter['id'] . '.png'))) echo $this -> Html -> image('avatars/' . $fighter['id'] . '.jpg');
	elseif (strlen($this -> Html -> image('avatars/' . $fighter['id'] . '.jpg')))  echo $this -> Html -> image('avatars/' . $fighter['id'] . '.jpg');
	else echo $this -> Html -> image('fighter.png');?>
	
	<h2>Modifier l'avatar</h2>
	<form action="<?php echo $this -> Html -> url(array('controller' => 'Arenas', 'action' => 'fighter'));?>" enctype="multipart/form-data" method="post">
		<?php echo FileUploader::HTMLForm('avatar', "3Mo"); ?>
		<input type="submit" value="Envoyer">
	</form>
<?php } ?>
	<h2>Créer un nouveau combattant</h2>
	
	<?php echo $this -> Form -> create('fighter');
	echo $this -> Form -> input('name', array('input', 'label' => 'Nom :'));
	echo $this -> Form -> end('Créer'); ?>
</section>
<?php if (isset($fighter)) { ?>
	<section class="right half">
		<h2>Liste de vos combattants</h2>
		<ul>
		<?php foreach ($fighters as $f) { ?>
			<li><?php echo $this -> Html -> link("Sélectionner " . $f['Fighter']['name'], array('controller' => 'Arenas', 'fighter' => $f['Fighter']['id']));?></li>
		<?php } ?>
		</ul>
	</section>
<?php } ?>

<section class="right half">

</section>