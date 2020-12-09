<?php
switch ( $_GET['sort'] ):
case 'grid':
	$rows = make_query(
		"SELECT a.*, AVG(ac.position) AS average, COUNT(ac.position) AS count
		FROM %s a
		JOIN %s ac ON a.id = ac.album_id
		GROUP BY ac.album_id
		ORDER BY count DESC, average ASC, a.name ASC
	", TABLE_ALBUM_NAME, TABLE_ALBUM_CHART_NAME );
	?>
	<table class="grid">
		<tr>
			<th class="center">Rank</th>
			<th>Album</th>
			<th></th>
			<?php foreach ( $charts as $chart ): ?>
			<th class="center<?php echo 'grid' === $_GET['sort'] ? ' name' : '' ?>"><?php echo $chart['name'] ?></th>
			<?php endforeach ?>
			<th></th>
		</tr>
	<?php
	$cols = count( $charts ) + 4;
	$i = 1;
	$counted = array();
	foreach ( $rows as $row ):
		$prows = make_query( "SELECT chart_id, position FROM %s WHERE album_id = {$row['id']}", TABLE_ALBUM_CHART_NAME );
		$positions = array();
		foreach ( $prows as $p ) {
			$positions[$p['chart_id']] = $p['position'];
		}

		if ( ! in_array( $row['count'], $counted ) ):
			$counted[] = $row['count']; ?>
		<tr class="bumper"><?php echo str_repeat( '<td class="empty-cell"></td>', $cols ) ?></tr>
		<?php endif; ?>
		<tr>
			<td class="center"><?php echo $i ?>.</td>
			<td><?php echo $row['name'] ?></td>
			<td class="empty-cell"></td>
			<?php foreach ( $charts as $chart ): ?>
			<td class="center<?php if ( ! isset( $positions[$chart['id']] ) ) {
				echo ' empty-cell';
			} ?>"><?php echo isset( $positions[$chart['id']] ) ? $positions[$chart['id']] : '' ?></td>
			<?php endforeach ?>
			<td class="empty-cell"></td>
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
		FROM %s a
		JOIN %s ac ON a.id = ac.album_id
		GROUP BY ac.album_id
		ORDER BY count DESC, average ASC, a.name ASC
	", TABLE_ALBUM_NAME, TABLE_ALBUM_CHART_NAME );
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
		"SELECT a.*, AVG(ac.position) AS average, COUNT(ac.position) AS count
		FROM %s a
		JOIN %s ac ON a.id = ac.album_id
		GROUP BY ac.album_id
		ORDER BY average ASC, count DESC, a.name ASC
	", TABLE_ALBUM_NAME, TABLE_ALBUM_CHART_NAME );

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
