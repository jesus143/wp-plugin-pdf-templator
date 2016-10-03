<?php
class metabox {
	
	private $_forposttype;
	private $_name;
	private $_settings;
	private $_fields;
	
	public  function __construct($name,$settings,$fields,$posttype) {
		$this->_forposttype = $posttype;
		$this->_name = $name;
		$this->_settings = $settings;
		$this->_fields = $fields;
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	
	}
	
	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {           
            if ( in_array( $post_type, $this->_forposttype )) {
				add_meta_box(
					$this->_name,
					$this->_settings['title'],
					array( $this, 'render_meta_box_content' ),
					$post_type,
					$this->_settings['context'],
					$this->_settings['proirity']
				);
            }
	}
	
	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( $this->_name.'_inner_custom_box', $this->_name.'_inner_custom_box_nonce' );
		
		// get current post meta data
		$meta = get_post_meta($post->ID, $this->_name, true);		
		
		foreach ($this->_fields as $field) {
			$field->render($meta[$field->getID()]);
		}
	}
	
	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST[ $this->_name.'_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST[ $this->_name.'_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, $this->_name.'_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		/*if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}*/

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
		$mydata =array();
		foreach ($this->_fields as $field) {
			$mydata[$field->getID()] = $field->getData();
		}		

		// Update the meta field.
		update_post_meta( $post_id, $this->_name, $mydata );
	}
	
	

}
?>