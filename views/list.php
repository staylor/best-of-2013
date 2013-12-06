<?php
$charts = make_query( "SELECT * FROM chart WHERE id = {$_GET['chart']}" );
foreach ( $charts as $chart ): ?>
<div id="chart-<?php echo $chart['id'] ?>">
	<h3><?php echo $chart['name'] ?></h3>
	<table>
	<?php
		$rows = make_query(
			"SELECT a.name AS album, ac.position
			FROM album_chart ac
			INNER JOIN album a ON a.id = ac.album_id
			WHERE ac.chart_id = {$chart['id']}
			ORDER BY ac.position ASC
		" );

		foreach ( $rows as $row ): ?>
		<tr>
			<td><?php echo $row['position'] ?>.</td>
			<td><?php echo $row['album'] ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<?php endforeach;