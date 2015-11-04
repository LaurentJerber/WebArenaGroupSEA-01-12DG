<?php
echo $this->Form->create('Fightermove');
echo $this->Form->input('direction',array('options' => array('north'=>'north','east'=>'east','south'=>'south','west'=>'west'), 'default' => 'east'));
echo $this->Form->end('Move');
?>

<table id="game">
	<?php for ($y = 0; $y < 15; $y++) { ?>
		<tr>
			<?php for ($x = 0; $x < 15; $x++) { ?>
				<?php if (in_array($x . "|" . $y, $movable)) { ?>
					<td class="movable_coordonate"><a href="?move"></a></td>
				<?php } elseif ($x == $current_x && $y == $current_y) { ?>
					<td class="current_coordonate"></td>
				<?php } else { ?>
					<td class="empty_coordonate"></td>
				<?php } ?>
			<?php } ?>
		</tr>
	<?php } ?>
</table>
