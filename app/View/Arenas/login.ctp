<section>
	<?php if (isset($error)) echo $error;?>
</section>
<?php if(isset($sendEmail)) { ?>
	<section>
		<p>
			Merci de préciser votre e-mail :
		</p>
		<?php echo $this -> Form -> create('reinitPw');
		echo $this -> Form -> input('email');
		echo $this -> Form -> end('Envoyer');?>
	</section>
<?php } elseif (isset($emailSent)) { ?>
	<section>
		<h2>Simulation de l'email</h2>
		<p>
			Vous souhaitez réinitialiser votre mot de passe après la perte de celui-ci. Pour cela, cliquez sur ce lien :
		</p>
		<p>
			<?php echo $this -> Html -> link('Réinitialiser mon mot de passe', array('controller' => 'Arenas', 'action' => 'login', 'u' => $unique, 'playerid' => $playerId));  ?>
		</p>
	</section>
<?php } elseif (isset($pickNewPassword)) { ?>
	<?php if (!isset($error)) { ?>
		<section>
			<p>
				Veuillez choisir un nouveau mot de passe :
			</p>
			<?php echo $this -> Form -> create('newPw');
			echo $this -> Form -> input('password', array('name' => 'pw', 'label' => 'Nouveau : '));
			echo $this -> Form -> input('password', array('name' => 'pwc', 'label' => 'Confirmation : '));
			echo $this -> Form -> end('Valider'); ?>
		</section>
	<?php } ?>
<?php } elseif(isset($passwordChanged)) { ?>
	<section>
		<p>
			Mot de passe modifié ! :D
		</p>
		<p>
			<?php echo $this -> Html -> link('Retour', array('controller' => 'Arenas', 'action' => 'login')); ?>
			
		</p>
	</section>	
<?php } else { ?>
	<section class="left half" id="login">
		<h2>Connexion / Inscription</h2>
		<?php echo $this -> Form -> create('login');
		echo $this -> Form -> input('email', array('label' => 'Email :'));
		echo $this -> Form -> input('password', array('label' => 'Mot de passe :'));
		echo $this -> Form  -> end('Log in | Sign in'); ?>
		<p>
			<?php echo $this -> Html -> link('Mot de passe oublié ?', array('controller' => 'Arenas', 'action' => 'login', 'password' => true));?>
		</p>
	</section>

	<section class="right half" id="facebook">
		<h2>Connexion via Facebook</h2>
		<a href="<?php echo $loginUrl;?>" class="uibutton confirm">Log in | Sign in</a>
	</section>
<?php } ?>