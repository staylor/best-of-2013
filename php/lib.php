<?php
date_default_timezone_set( 'America/New_York' );

global $stats;
$stats = array( 'mc_get' => 0, 'mc_set' => 0, 'select' => 0 );

function _memcache() {
	static $m = null;
	if ( ! $m ) {
		$m = new Memcached();
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

function last_updated( $reset = false ) {
	static $updated = null;

	if ( $updated && ! $reset ) {
		return $updated;
	}

	if ( $reset ) {
		$updated = false;
	} else {
		$updated = _memcache()->get( 'last_updated' );
	}

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
		$dbh = new mysqli( '127.0.0.1', 'root', 'mypassword', DB_NAME );
	}

	$args = func_get_args();

	if ( count( $args ) > 1 ) {
		$escaped = array();
		foreach ( array_slice( $args, 1 ) as $arg ) {
			$escaped[] = $dbh->real_escape_string( $arg );
		}
		$s = new ReflectionFunction( 'sprintf' );
		$query = $s->invokeArgs( array_merge( array( $query ), $escaped ) );
	}
	error_log( $query );

	if ( false !== strpos( $query, 'INSERT' ) ) {
		$dbh->query( $query );
		// last_updated( true );
		return $dbh->insert_id;
	} elseif ( false === strpos( $query, 'SELECT' ) ) {
		$results = $dbh->query( $query );
		// last_updated( true );
		return $results;
	} else {
		$hash = md5( $query );
		// $cached = get_cache( $hash );
		// if ( is_array( $cached ) ) {
		// 	return $cached;
		// }

		$stats['select']++;
		$results = $dbh->query( $query );
		$rows = array();
		while ( $result = $results->fetch_assoc() ) {
			$rows[] = $result;
		}

		// set_cache( $hash, $rows );

		return $rows;
	}
}

function retrieve_asset_id( $name, $table = '' ) {
	$trimmed = trim( $name );
	$lower = strtolower( $trimmed );
	$select = "SELECT id FROM $table WHERE TRIM(LOWER(name)) = '%s'";
	$asset_id = make_query( $select, $lower );
	if ( empty( $asset_id ) ) {
		return make_query( "INSERT INTO $table (name) VALUES ('%s')", $trimmed );
	} else {
		$row = array_shift( $asset_id );
		return $row['id'];
	}
}
