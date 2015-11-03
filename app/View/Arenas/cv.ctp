<!DOCTYPE html>
<html>
	<head>
		<title>CV - Laurent Jerber</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="style.css"/>
		<script src="script.js"></script>
	</head>
	<body>
		<!-- En-tête -->
		<header>
			<section id="infos">
				<h3>Laurent JERBER</h3>
				<!-- Utilisation des br/ pertinente car on va à la ligne pour cette en-tête-->
				1, rue Raymond Frot<br/>
				Montigny/Loing 77690, France<br/>
				<em>+33 6 12 34 56 78</em><br/>
				Nationalité: française <img src="france_flag_256.png" alt="France" height="16" style="margin-left: 10px;"/><br/>
				<a href="mailto:jerber@edu.ece.fr">jerber@edu.ece.fr</a>
			</section>
			
			<section id="title">
				<h1>Curriculum Vitae</h1>
				<h2>Laurent JERBER</h2>
			</section>
			
			<!-- Image en flottante à droit -->
			<img src="portrait.jpg" id="photo" alt="photo"/>
		</header>
		
		<!-- Contenu du CV -->
		<article>
		
			<!-- DIPLOMES / FORMATIONS --> 
			<!-- Le titre contient le lien pour cacher / afficher le texte -->
			<h3>Diplômes et formations<a href="#" onclick="return afficherCacherCateg('Formations');" id="buttonFormations">Cacher</a></h3>
			<ul id="listFormations" style="display: block">
				<!-- Liste des expériences -->
				<li><strong>2014-2017</strong> : Ecole d'ingénieur ECE Paris - Majeure Systèmes embarqués en apprentissage</li>
				<li>
					<strong>2012-2014</strong> : DUT Mesures Physiques à l'IUT Paris Jussieu
					<!-- Cette ligne contient également une liste -->
					<ul>
						<li>Spectrométrie de masse, UV, IR, fluorescence et diffraction X, microscopie électronique</li>
						<li>Traitement du signal, montage amplificateurs, circuits logiques, filtres, micro-contrôleurs</li>
						<li>Chromatographie HPLC, électrochimie et cinétique chimique, calorimétrie</li>
						<li>Optique géométrique et ondulatoire, interféromètre de Michelson et LASER</li>
						<li>Techniques du vide : pompes à palettes et turbomoléculaires</li>
					</ul>
				</li>
				<li><strong>2012</strong> : Baccalauréat Scientifique Mention Bien au lycée Uruguay France</li>
			</ul>
			
			<!-- EXPERIENCES PRO -->
			<h3>Expériences pro<a href="#" onclick="return afficherCacherCateg('ExpPro');" id="buttonExpPro">Cacher</a></h3>
			<ul id="listExpPro" style="display: block">
				<li><strong>2014-2017</strong> : Apprenti chez Thales Optronique en développement logiciel (temps réel)</li>
				<li><strong>2014</strong> : Développement d'un site web et de son propre CMS pour l'IUT Paris Jussieu</li>
			</ul>
			
			<!-- PROJETS -->
			<h3>Projets<a href="#" onclick="return afficherCacherCateg('Projets');" id="buttonProjets">Cacher</a></h3>
			<ul id="listProjets" style="display: block">
				<li><strong>DSM</strong> - Dynamic Website Motor - API de développement web</li>
				<li><strong>iJulie</strong> - Application Android de développement</li>
				<li><strong>Site IUT Paris Jussieu</strong> - Site web entièrement modifiable depuis une interface web</li>
				<li><strong>Voiture téléguidée par smartphone</strong> - Création d'une manette android et d'un circuit électronique contrôlant une petite voiture via la technologie Bluetooth©</li>
				<li><strong>Site BDE IP7</strong> (fermé) - Site d'association étudiante</li>
			</ul>
			
			<!-- LOISIRS / PASSIONS -->
			<h3>Loisirs et passions</h3>
			<ul>
				<li>Développement web (HTML/CSS/PHP/SQL/JS/JQUERY)</li>
				<li>Programmation Android</li>
				<li>Les plaisirs naturels (scatophilie, les poulets fermiers, l'orticulture)</li>
				<li>Sports automobile</li>
				<li>Athlétisme</li>
			</uL>
			
			<!-- Boutons d'interactions -->
			<p id="buttons">
				<a class="button" href="cv.pdf">Télécharger en PDF</a>
				<a class="button" href="#" onclick="return afficherCacher('contactForm');">Me contacter</a>
				<a class="button" href="#" onclick="return afficherCacher('ajouterExpPro');">Ajouter experience professionnelle</a>
			</p>
			
			<!-- Formulaire d'ajout d'expérience pro - caché par défaut -->
			<form id="ajouterExpPro" method="post" onsubmit="return ajouterExpPro();" style="display: none;">
				<h3>Ajouter une expérience professionnelle</h3>
				<p class="line">
					<label for="exppro_date">Date : </label>
					<input type="text" name="exppro_date" id="exppro_date"/>
				</p>
				<p class="line">
					<label for="exppro_description">Description : </label>
					<textarea name="exppro_description" id="exppro_description"></textarea>
				</p>
				<p class="line center">
					<input type="submit" class="submit" value="Ajouter"/>
				</p>
			</form>
		</article>
		
		<!-- En positon fixed avec un z-inex plus haut que le reste de la page, ce formulaire est en dehors de l'article -->
		<form id="contactForm" method="post" onsubmit="return envoyerEmail();" style="display: none;">
			<h3>Me contacter</h3>
			<p class="line">
				<label for="email_address">Votre adresse : </label>
				<input type="email" name="email_address" id="email_address"/>
			</p>
			<p class="line">
				<label for="email_contenu">Contenu : </label>
				<textarea name="email_contenu" id="email_contenu"></textarea>
			</p>
			<p class="line center">
				<input type="submit" class="submit" value="Envoyer"/>
			</p>
			<p class="line center">
				<a href="#" onclick="return afficherCacher('contactForm');" id="closeButton">Fermer</a>
			</p>
		</form>
	</body>
</html>
