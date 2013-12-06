<?php
define( 'LIB', dirname( __FILE__ ) . '/php/' );
define( 'VIEWS', dirname( __FILE__ ) . '/views/' );

require( LIB . 'lib.php' );

// handle POST
if ( 'POST' === $_SERVER['REQUEST_METHOD'] ):
	require( LIB . 'handler.php' );
endif;
?>
<!DOCTYPE html>
<html>
<head>
<title>Best of 2013</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body {font-family: sans-serif;}
td, th {font-size: 85%}
a:visited {color: blue;}
a.selected {color: red}
.center {text-align: center; padding: 0 7px;}
.grid {border-collapse: collapse;}
.grid td { border: 1px solid #f1f1f1; padding: 3px}
.bumper td {padding: 10px;}
.empty-cell {background: #eee;}
</style>
</head>
<body>
<h1>Best of 2013</h1>
<?php
require( VIEWS . 'nav.php' );

if ( ! empty( $_GET['sort'] ) ):

	require( VIEWS . 'calculated.php' );

elseif ( ! empty( $_GET['chart'] ) ):

	require( VIEWS . 'list.php' );

endif;

if ( isset( $_GET['view'] ) && 'admin' === $_GET['view'] ) {
	require( VIEWS . 'form.php' );
}
?>
<pre>
<?php print_r( $stats ); ?>
</pre>
</body>
</html>