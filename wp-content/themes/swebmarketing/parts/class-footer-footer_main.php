<?php

class TC_footer_main {

    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;

    function __construct () {

        self::$instance =& $this;
        //html > footer actions
        add_action ( '__after_main_wrapper'		, 'get_footer');

        //footer actions
        add_action ( '__footer'					, array( $this , 'tc_widgets_footer' ), 10 );
        add_action ( '__footer'					, array( $this , 'tc_colophon_display' ), 20 );
        add_action ( '__colophon'				, array( $this , 'tc_colophon_left_block' ), 10 );
        add_action ( '__colophon'				, array( $this , 'tc_colophon_center_block' ), 20 );
        add_action ( '__colophon'				, array( $this , 'tc_colophon_right_block' ), 30 );
    }


    /**
	 * Displays the footer widgets areas
	 *
	 *
	 * @package Customizr
	 * @since Customizr 3.0.10
	 */
    function tc_widgets_footer() {
    	
    	//checks if there's at least one active widget area in footer.php.php
    	$status 					= false;
    	$footer_widgets 			= apply_filters( 'tc_footer_widgets', TC_init::$instance -> footer_widgets );
    	foreach ( $footer_widgets as $key => $area ) {
    		$status = is_active_sidebar( $key ) ? true : $status;
    	}
		if ( !$status )
			return;
		
		//hack to render white color icons if skin is grey
		$skin_class 	= ( 'grey.css' == tc__f('__get_option' , 'tc_skin') ) ? 'white-icons' : '';

		ob_start();
		?>
			<div class="container footer-widgets <?php echo $skin_class ?>">
				<div class="row widget-area" role="complementary">
					
					<?php foreach ( $footer_widgets as $key => $area )  : ?>

						<?php if ( is_active_sidebar( $key ) ) : ?>
							
							<div id="<?php echo $key; ?>" class="<?php echo apply_filters( $key . '_widget_class', 'span4' ) ?>">
								<?php dynamic_sidebar( $key ); ?>
							</div>

						<?php endif; ?>

					<?php endforeach; ?>
				</div><!-- .row.widget-area -->
			</div><!--.footer-widgets -->
		<?php
		$html = ob_get_contents();
        if ($html) ob_end_clean();
        echo apply_filters( 'tc_widgets_footer', $html );
	}//end of function






    /**
	 * Displays the colophon (block below the widgets areas).
	 *
	 *
	 * @package Customizr
	 * @since Customizr 3.0.10
	 */
    function tc_colophon_display() {
    	
    	?>
    	<?php ob_start() ?>
		 <div class="colophon">
		 	<div class="container">
		 		<div class="<?php echo apply_filters( 'tc_colophon_class', 'row-fluid' ) ?>">
				    <?php 
					    //colophon blocks actions priorities
					    //renders blocks
					    do_action( '__colophon' ); 
				    ?>
      			</div><!-- .row-fluid -->
      		</div><!-- .container -->
      	</div><!-- .colophon -->
    	<?php
    	$html = ob_get_contents();
        if ($html) ob_end_clean();
        echo apply_filters( 'tc_colophon_display', $html );
    }




    /**
	 * Displays the social networks block in the footer
	 *
	 *
	 * @package Customizr
	 * @since Customizr 3.0.10
	 */
    function tc_colophon_left_block() {

      	echo apply_filters( 
      		'tc_colophon_left_block', 
      		sprintf('<div class="%1$s">%2$s</div>',
      			apply_filters( 'tc_colophon_left_block_class', 'span4 social-block pull-left' ),
      			0 != tc__f( '__get_option', 'tc_social_in_footer') ? tc__f( '__get_socials' ) : ''
      		)
      	);
    }





    /**
	 * Displays the back to top block
	 *
	 *
	 * @package Customizr
	 * @since Customizr 3.0.10
	 */
	function tc_colophon_right_block() {
    	echo apply_filters(
    		'tc_colophon_right_block',
    		sprintf('<div class="%1$s"><p class="pull-right"><a class="back-to-top" href="#">%2$s</a></p></div>',
    			apply_filters( 'tc_colophon_right_block_class', 'span4 backtop' ),
    			__( 'Back to top' , 'customizr' )
    		)
    	);
	}

 }//end of class

