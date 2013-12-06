<?php
global $stats;
$stats = array( 'mc_get' => 0, 'mc_set' => 0, 'select' => 0 );

function _memcache() {
	static $m = null;
	if ( ! $m ) {
		$m = new Memcache();
		$m->addServer( '127.0.0.1', 11211 );
		$m->connect( '127.0.0.1', 11211 );
	}

	return $m;
}

function get_cache( $key ) {
	global $stats;
	$stats['mc_get']++;
	return _memcache()->get( $key . ':' . last_updated() );
}

function set_cache( $key, $value ) {
	global $stats;
	$stats['mc_set']++;
	return _memcache()->set( $key . ':' . last_updated(), $value );
}

function last_updated() {
	static $updated = null;

	if ( $updated ) {
		return $updated;
	}

	$updated = _memcache()->get( 'last_updated' );
	if ( ! $updated ) {
		$updated = $_SERVER['REQUEST_TIME'];
		_memcache()->set( 'last_updated', $updated );
	}

	return $updated;
}

// lib
function make_query( $query ) {
	global $stats;
	static $dbh = null;
	if ( ! $dbh ) {
		// database
		$dbh = new mysqli( '127.0.0.1', 'root', 'mypassword', 'charts' );
	}

	$args = func_get_args();

	if ( count( $args ) > 1 ) {
		$escaped = array();
		foreach ( array_slice( $args, 1 ) as $arg ) {
			$escaped[] = $dbh->real_escape_string( $arg );
		}
		$s = new ReflectionFunction( 'sprintf' );
		$query = $s->invokeArgs( array_merge( array( $query ), $escaped ) );
		//echo $query, "\n";
	}

	if ( false !== strpos( $query, 'INSERT' ) ) {
		$dbh->query( $query );
		return $dbh->insert_id;
	} elseif ( false === strpos( $query, 'SELECT' ) ) {
		$results = $dbh->query( $query );
		return $results;
	} else {
		$hash = md5( $query );
		$cached = get_cache( $hash );
		if ( $cached ) {
			return $cached;
		}

		$stats['select']++;
		$results = $dbh->query( $query );
		$rows = array();
		while ( $result = $results->fetch_assoc() ) {
			$rows[] = $result;
		}

		set_cache( $hash, $rows );

		return $rows;
	}
}

function retrieve_asset_id( $name, $table = '' ) {
	$lower = strtolower( $name );
	$select = "SELECT id FROM $table WHERE LOWER(name) = '%s'";
	$asset_id = make_query( $select, $lower );
	if ( empty( $asset_id ) ) {
		return make_query( "INSERT INTO $table (name) VALUES ('%s')", $name );
	} else {
		$row = array_shift( $asset_id );
		return $row['id'];
	}
}