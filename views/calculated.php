<?php
switch ( $_GET['sort'] ):
case 'grid':
	$rows = make_query(
		"SELECT a.*, AVG(ac.position) AS average, COUNT(ac.position) AS count
		FROM album a
		JOIN album_chart ac ON a.id = ac.album_id
		GROUP BY ac.album_id
		ORDER BY count DESC, average ASC, a.name ASC
	" );
	?>
	<table class="grid">
		<tr>
			<th>Rank</th>
			<th>Album</th>
			<?php foreach ( $charts as $chart ): ?>
			<th class="center"><?php echo $chart['name'] ?></th>
			<?php endforeach ?>
		</tr>
	<?php
	$cols = count( $charts ) + 2;
	$i = 1;
	$counted = array();
	foreach ( $rows as $row ):
		$prows = make_query( "SELECT chart_id, position FROM album_chart WHERE album_id = {$row['id']}" );
		$positions = array();
		foreach ( $prows as $p ) {
			$positions[$p['chart_id']] = $p['position'];
		}

		if ( ! in_array( $row['count'], $counted ) ):
			$counted[] = $row['count']; ?>
		<tr class="bumper"><?php echo str_repeat( '<td></td>', $cols ) ?></tr>
		<?php endif; ?>
		<tr>
			<td><?php echo $i ?>.</td>
			<td><?php echo $row['name'] ?></td>
			<?php foreach ( $charts as $chart ): ?>
			<td class="center<?php if ( ! isset( $positions[$chart['id']] ) ) {
				echo ' empty-cell';
			} ?>"><?php echo isset( $positions[$chart['id']] ) ? $positions[$chart['id']] : '' ?></td>
			<?php endforeach ?>
		</tr>
	<?php
	$i++;
	endforeach ?>
	</table>
	<?php
	break;

case 'compiled':
	$rows = make_query(
		"SELECT a.*, AVG(ac.position) AS average, COUNT(ac.position) AS count
		FROM album a
		JOIN album_chart ac ON a.id = ac.album_id
		GROUP BY ac.album_id
		ORDER BY count DESC, average ASC, a.name ASC
	" );
	?>
	<table>
		<tr>
			<th>Rank</th>
			<th>Album</th>
			<th class="center">Average Rank</th>
			<th class="center"># of Lists</th>
		</tr>
	<?php
	$i = 1;
	$counted = array();
	foreach ( $rows as $row ):
		if ( ! in_array( $row['count'], $counted ) ):
			$counted[] = $row['count']; ?>
		<tr class="bumper"><td></td><td></td><td></td><td></td></tr>
		<?php endif; ?>
		<tr>
			<td><?php echo $i ?>.</td>
			<td><?php echo $row['name'] ?></td>
			<td class="center"><?php echo $row['average'] ?></td>
			<td class="center"><?php echo $row['count'] ?></td>
		</tr>
	<?php
	$i++;
	endforeach ?>
	</table>
	<?php
	break;

case 'rank':
	$rows = make_query(
		"SELECT a.*, AVG(ac.position) AS average
		FROM album a
		JOIN album_chart ac ON a.id = ac.album_id
		GROUP BY ac.album_id
		ORDER BY average ASC, a.name ASC
	" );

	?>
	<table>
		<tr>
			<th>Rank</th>
			<th>Album</th>
			<th class="center">Average Rank</th>
		</tr>
	<?php
	$i = 1;
	foreach ( $rows as $row ): ?>
		<tr>
			<td><?php echo $i ?>.</td>
			<td><?php echo $row['name'] ?></td>
			<td class="center"><?php echo $row['average'] ?></td>
		</tr>
	<?php
	$i++;
	endforeach ?>
	</table>
	<?php
	break;
endswitch;