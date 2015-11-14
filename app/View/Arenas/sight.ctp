<?php if (isset($error)) { ?>
	<section><p><?php echo $error;?></p></section>
<?php } else { ?>
	<?php if (count($events) > 0) { ?>
		<section>
			<?php foreach($events as $event) {
				echo "<p>" . $event . "</p>";
			} ?>
		</section>
	<?php } ?>
	<?php if (isset($levelUp)) { ?>
		<section>
			<?php echo $this -> Form -> create('levelUp'); 
			for ($i = 0; $i < $levelUp; $i++) {
				echo $this -> Form -> input('Amélioration n° ' . $i, array('type' => 'radio', 'name' => 'levelUp' . $i, 'options' => array('sight' => '+1 Vue', 'strength' => '+1 Force', 'health' => '+3 Vie')));
			}
			echo $this -> Form -> end('Choisir'); ?>
		</section>
	<?php } ?>
	<section id="game">
		<?php
		for ($y = 0; $y < $arenaSize[1]; $y++) {
			for ($x = 0; $x < $arenaSize[0]; $x++) {
				if ($arena[$y][$x]['movable']) {
					$movable = "true";
					$class = 'caseMovable';
				} else {
					$movable = "false";
					$class ='caseNotReachable';
				}
				if ($arena[$y][$x]['attaquable']) {
					$attaquable = "true";
					$class = 'caseMovable';
					$url = $this -> Html -> url(array('controller' => 'Arenas', 'action' => 'sight'));
				} else  {	
					$attaquable = "false";
					$url = $this -> Html -> url(array('controller' => 'Arenas', 'action' => 'sight'));
				}
				echo $this -> Html -> image(
					$arena[$y][$x]['src'] . '.png', 
					array(
						'class' => $class, 
						'onclick' => 'seeCase("' . $url . '",' . $x . ',' . $y . ',' . $movable . ',"' . addslashes($arena[$y][$x]['message']) . '","' . addslashes($arena[$y][$x]['submessage']) . '",' . $attaquable . ');'));
			}
		} ?>
		<div class="clear"></div>
	</section>
	<section class="left half">
		<h2>Votre combattant</h2>
		<p><strong>Nom : </strong> <?php echo $fighter['name'];?></p>
		<p><strong>Niveau : </strong> <?php echo floor($fighter['level'] / 4);?></p>
		<p><strong>Expérience non utilisé : </strong> <?php echo $fighter['xp'];?></p>
		<p><strong>Compétence de vue : </strong> <?php echo $fighter['skill_sight'];?> pts</p>
		<p><strong>Compétence de force : </strong> <?php echo $fighter['skill_strength'];?> pts</p>
		<p><strong>Santé actuelle : </strong> <?php echo $fighter['current_health'];?> pts</p>
		<p><strong>Santé maximum : </strong> <?php echo $fighter['skill_health'];?> pts</p>
	</section>

	<div id="gamePopup" style="display: none;">
		<div id="gamePopupContent">
			<div id="gamePopupX"></div>
			<div id="gamePopupY"></div>
			<div class="clear"></div>
			<p id="gamePopupMessage">
			</p>
			<p id="gamePopupSubmessage">
			</p>
			<a href="#" id="gamePopupMoveTo" style="display: none;">Aller</a>
			<a href="#" id="gamePopupAttack" style="display: none;">Attaque</a>
			<div class="clear"></div>
		</div>
	</div>
<?php } ?>