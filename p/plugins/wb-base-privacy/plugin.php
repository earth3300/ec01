<?php

/**
Plugin Name: WP Bundle Base Privacy
Plugin URI: http://wp.cbos.ca
Description: Sets new published content to private by default (posts, pages, etc.).
Version: 2018.06.26
Author: wp.cbos.ca
Author URI: http://wp.cbos.ca
License: GPLv2+
*/

defined( 'ABSPATH' ) || exit;

class WP_Bundle_Privacy {
    
    /**
	 * Stores meta in cache for future reads.
	 * A group must be set to to enable caching.
	 *
	 * @since 3.0.0
	 * @var string
	 */
	protected $cache_group = '';
    
    /**
	 * Default constructor.
	 *
	 */
	public function __construct( ) {
        add_action( 'post_submitbox_misc_actions' , '&$this->default_post_visibility' );
        add_filter( 'status_edit_pre', '&$this->set_post_type_status_private', 10, 2 );
        add_filter( 'status_save_pre', '&$this->save_post_type_status_private', 10, 1 );
	}
    
    function set_post_type_status_private( $status, $post_id ) {
        $status = 'private';
        return $status;
    }
    
    function save_post_type_status_private( $status ) {
        $status = 'private';
        return $status;
    }
    
    function default_post_visibility(){
        global $post;
        
        if ( 'publish' == $post->post_status ) {
            $visibility = 'public';
            $visibility_trans = __('Public');
        } elseif ( !empty( $post->post_password ) ) {
            $visibility = 'password';
            $visibility_trans = __('Password protected');
        } elseif ( $post_type == 'post' && is_sticky( $post->ID ) ) {
            $visibility = 'public';
            $visibility_trans = __('Public, Sticky');
        } else {
            $post->post_password = '';
            $visibility = 'private';
            $visibility_trans = __('Private');
        } ?>
        
        <script type="text/javascript">
            (function($){
                try {
                    $('#post-visibility-display').text('<?php echo $visibility_trans; ?>');
                    $('#hidden-post-visibility').val('<?php echo $visibility; ?>');
                    $('#visibility-radio-<?php echo $visibility; ?>').attr('checked', true);
                } catch(err){}
            }) (jQuery);
        </script>
        <?php
    }
} //end class
