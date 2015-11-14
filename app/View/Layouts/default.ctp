<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title;?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('style');
		echo $this -> Html -> css('header');
		echo $this -> Html -> css('game');
		echo $this -> Html -> css('fb-buttons');
		echo $this -> Html -> script('jquery-1.11.2');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<ul>
				<li><?php echo $this -> Html -> link("Accueil", array('controller' => 'Arenas', 'action' => 'index')); ?></li>
				<li><?php echo $this -> Html -> link("Combattant", array('controller' => 'Arenas', 'action' => 'fighter')); ?></li>
				<li><?php echo $this -> Html -> link("Vue", array('controller' => 'Arenas', 'action' => 'sight')); ?></li>
				<li><?php echo $this -> Html -> link("Journal", array('controller' => 'Arenas', 'action' => 'diary')); ?></li>
				<li><?php echo $this -> Html -> link($menuConnexion, array('controller' => 'Arenas', 'action' => 'login')); ?></li>
				<div class="clear"></div>
			</ul>
		</div>
		<div id="content">
			<?php echo $this -> fetch('content'); ?>
			<div class="clear"></div>
		</div>
		<div id="footer">
			Projet de Technologies Web : Groupe SE options A, D* & G* (* obligatoires)<br/>
			Laurent Jerber & Xavier Besse<br/>
			GitHub : https://github.com/LaurentJerber/WebArenaGroupSEA-01-12DG.git (Envoyer votre username pour ajout en tant que collaborateurs)<br/>
			Version en ligne : <a href="http://www.laurentjerber.com/webarena/Arenas/index" style="color: white;">http://www.laurentjerber.com/webarena/Arenas/index</a>
		</div>
		
		<?php echo $this -> Html -> script('game'); ?>
	</div>
</body>
</html>
