<table id='events'>
	<tr>
		<th class="event_date">Date</th>
		<th class="event_message">Evénement</th>
		<th class="event_coor">Coordonées</th>
	</tr>
	<?php foreach($events as $e) {
		$event = $e['events'];
		echo '<tr>';
			echo '<td class="event_date"><strong>' . $event['date'] . '</strong></td>';
			echo '<td class="event_message">' . $event['name'] . '</td>';
			echo '<td class="event_coor">x : ' . $event ['coordinate_x'] . ' | y : ' . $event['coordinate_y'] . '</td>';
		echo '</tr>';?>		
	<?php } ?>
</table>

