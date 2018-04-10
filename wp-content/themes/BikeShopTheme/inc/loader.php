<?php 
	/* Redux framework init */
	if (file_exists( dirname( __FILE__ ) . '/metaboxes.php' ) ) {
	    require_once( dirname( __FILE__ ) . '/metaboxes.php' );
	}

	if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/ReduxFramework/ReduxCore/framework.php' ) ) {
    	require_once( dirname( __FILE__ ) . '/ReduxFramework/ReduxCore/framework.php' );
	}
	if (file_exists( dirname( __FILE__ ) . '/redux-config.php' ) ) {
	    require_once( dirname( __FILE__ ) . '/redux-config.php' );
	}

?>