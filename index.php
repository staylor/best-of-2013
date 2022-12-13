<?php
define( 'DB_NAME', 'charts' );
define( 'TABLE_ALBUM_NAME', 'album' );
define( 'TABLE_CHART_NAME', 'chart' );
define( 'TABLE_ALBUM_CHART_NAME', 'album_chart' );
define( 'VIEWS', dirname( __FILE__ ) . '/views/' );

require( dirname( __FILE__ ) . '/php/lib.php' );

// handle POST
if ( 'POST' === $_SERVER['REQUEST_METHOD'] ):
	require( dirname( __FILE__ ) . '/php/handler.php' );
endif;
?>
<!DOCTYPE html>
<html>
<head>
<title>Best of 2022</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body {font-family: sans-serif;}
td, th {font-size: 85%}
th {word-wrap: break-word;}
a:visited {color: blue;}
a.selected {color: red}
.center {text-align: center; padding: 0 7px;}
.name {max-width: 90px;}
.grid {border-collapse: collapse; width: 100%;}
.grid td { border: 1px solid #f1f1f1; padding: 3px}
.bumper td {padding: 10px;}
.empty-cell {background: #eee;}
</style>
</head>
<body>
<h1>Best of 2022</h1>
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
<!-- <pre>
<?php print_r( $stats ); ?>
</pre> -->
</body>
</html>
