<?php
$charts = make_query( "SELECT * FROM %s WHERE id = {$_GET['chart']}", TABLE_CHART_NAME );
foreach ( $charts as $chart ): ?>
<div id="chart-<?php echo $chart['id'] ?>">
	<h3><?php echo $chart['name'] ?></h3>
	<table>
	<?php
		$rows = make_query(
			"SELECT a.name AS album, ac.position
			FROM %s ac
			INNER JOIN %s a ON a.id = ac.album_id
			WHERE ac.chart_id = {$chart['id']}
			ORDER BY ac.position ASC
		", TABLE_ALBUM_CHART_NAME, TABLE_ALBUM_NAME );

		foreach ( $rows as $row ): ?>
		<tr>
			<td><?php echo $row['position'] ?>.</td>
			<td><?php echo $row['album'] ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<?php endforeach;
