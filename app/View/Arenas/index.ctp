<section>
	<p>
		Le jeu WebArena est un jeu développé par Laurent Jerber et Xavier Besse dans le cadre d'un projet scolaire. Ce jeu, un RPG, se présente sous la forme d'une carte - un damier - où 
		vos combattants peuvent se déplacer. Sur cette carte, votre combattant pourra affronter d'autres combattants, trouver des objets, affronter des monstres et eviter des pièges !
	</p>
	<p>
		Commencez dès maintenant en vous rendant sur la page <?php echo $this -> Html -> link('Connexion', array('controller' => 'Arenas', 'action' => 'login'));?> pour vous inscrire. L'inscription 
		se fait en deux clicks !
	</p>
	<p>
		Vous pouvez gérer vos combattants sur la page <?php echo $this -> Html -> link('Combattant', array('controller' => 'Arenas', 'action' => 'fighter'));?>, et vous pouvez accéder à l'arène 
		en allant sur <?php echo $this -> Html -> link('Vue', array('controller' => 'Arenas', 'action' => 'sight'));?>. Enfin, consultez toutes les actions effectués près de votre combattant au cours 
		de ces dernières 24h sur la page <?php echo $this -> Html -> link('Journal', array('controller' => 'Arenas', 'action' => 'diary'));?>.
	</p>
</section>