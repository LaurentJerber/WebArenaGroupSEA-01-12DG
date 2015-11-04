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
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('style');
		echo $this -> Html -> css('header');
		echo $this -> Html -> css('game');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<ul>
				<li><a href="index">Accueil</a></li>
				<li><a href="fighter">Combattant</a></li>
				<li><a href="sight">Vision</a></li>
				<li><a href="diary">Journal</a></li>
				<li><a href="connexion" id="menu_connexion">Connexion</a></li>
				<div class="clear"></div>
			</ul>
		</div>
		<div id="content">
			<?php echo $this -> fetch('content'); ?>
		</div>
		<div id="footer">
		
		</div>
	</div>
</body>
</html>
