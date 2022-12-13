<?php
$data = array_map( 'trim', $_POST );
$chart_id = retrieve_asset_id( $data['chart'], TABLE_CHART_NAME );
$lines = array_map( 'trim', explode( "\n", $data['data'] ) );
$data['entries'] = array();

// pull out the entries
foreach ( $lines as $line ) {
	// expects 1. Daft Punk - Whatever
	list( $position, $name ) = explode( '.', $line, 2 );
	$album_id = retrieve_asset_id( $name, TABLE_ALBUM_NAME );
	make_query( "INSERT IGNORE INTO %s (album_id, chart_id, position) VALUES ({$album_id}, {$chart_id}, {$position})", TABLE_ALBUM_CHART_NAME );
}

// _memcache()->set( 'last_updated', $_SERVER['REQUEST_TIME'] );

header( "Location: " . $_SERVER['REQUEST_URI'] );
exit();
