<?php
echo $this->Form->create('Fightermove');
echo $this->Form->input('direction',array('options' => array('north'=>'north','east'=>'east','south'=>'south','west'=>'west'), 'default' => 'east'));
echo $this->Form->end('Move');
?>

<table id="game">
	<?php for ($y = 0; $y < 15; $y++) { ?>
		<tr>
			<?php for ($x = 0; $x < 15; $x++) { ?>
				<td class="empty_coordinate"></td>
			<?php } ?>
		</tr>
	<?php } ?>
</table>