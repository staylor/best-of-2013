<hr/>

<p><strong>View Calculated Results</strong>: <?php

$sorts = array(
	'compiled' => 'Compiled',
	'rank' => 'By Rank Only',
	'grid' => 'All Results, Ranked'
);

foreach ( $sorts as $slug => $label ): ?>
<a href="?sort=<?php echo $slug ?>" <?php
	if ( isset( $_GET['sort'] ) && $slug == $_GET['sort'] ) {
		echo 'class="selected"';
	}
	?>><?php echo $label ?></a>&nbsp;
<?php endforeach ?></p>

<hr/>

<p><strong>View an Individual List</strong>: <?php

$charts = make_query( sprintf( "SELECT * FROM %s", TABLE_CHART_NAME ) );

foreach ( $charts as $chart ): ?>
<a href="?chart=<?php echo $chart['id'] ?>" <?php
	if ( isset( $_GET['chart'] ) && $chart['id'] == $_GET['chart'] ) {
		echo 'class="selected"';
	}
?>><?php echo $chart['name'] ?></a>&nbsp;
<?php endforeach; ?></p>

<hr/>
