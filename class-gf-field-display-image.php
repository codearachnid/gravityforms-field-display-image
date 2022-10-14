<?php

class Simple_GF_Field extends GF_Field {
	public $type = 'display_image';
	
	public function get_form_editor_field_title() {
		return esc_attr__( 'Display Image', 'gf_field_display_image' );
	}
	
	/**
	 * Assign the field button to the Advanced Fields group.
	 *
	 * @return array
	 */
	public function get_form_editor_button() {
		return array(
			'group' => 'advanced_fields',
			'text'  => $this->get_form_editor_field_title(),
		);
	}
	
	/**
	 * The settings which should be available on the field in the form editor.
	 *
	 * @return array
	 */
	function get_form_editor_field_settings() {
		return array(
			'display_image_id',
			'display_image_size',
			'css_class_setting',
			'admin_label_setting',
			'visibility_setting',
			'conditional_logic_field_setting',
		);
	}
	
	/**
	 * Enable this field for use with conditional logic.
	 *
	 * @return bool
	 */
	public function is_conditional_logic_supported() {
		return true;
	}

	
	/**
	 * Define the fields inner markup.
	 *
	 * @param array $form The Form Object currently being processed.
	 * @param string|array $value The field value. From default/dynamic population, $_POST, or a resumed incomplete submission.
	 * @param null|array $entry Null or the Entry Object currently being edited.
	 *
	 * @return string
	 */
	public function get_field_input( $form, $value = '', $entry = null ) {
		$id              = absint( $this->id );
		$form_id         = absint( $form['id'] );
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();
	
		// Prepare the value of the input ID attribute.
		//$field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_{$id}" : "input_{$form_id}_{$id}";
		$field_id = "input_{$id}";
		
		$image_size = !empty($this->display_image_size) ? $this->display_image_size : 'full';
		$image_to_display = wp_get_attachment_image_src( $this->display_image_id, $image_size );
	
		$input = sprintf('<img %s id="%s" class="%s" data-imgsize="%s" data-imgid="%s" />',
			!empty( $image_to_display ) ? 'src="' . $image_to_display[0] . '"' : '',
			$field_id,
			!empty( $this->display_image_id) ? '' : ' hidden ',
			$this->display_image_size,
			$this->display_image_id,
		);
		
		$input .= sprintf('<input type="button" class="button gf-display-image-upload %s" value="%s" data-fieldid="%s" />', 
			!empty( $this->display_image_id) ? ' hidden ' : '',
			_( 'Upload image' ),
			$id
		);

	
		return sprintf( "<div class='ginput_container ginput_container_%s'>%s</div>", $this->type, $input );
	}
	
	public function get_field_content( $value, $force_frontend_label, $form ) {
		$form_id         = $form['id'];
		$admin_buttons   = $this->get_admin_buttons();
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();
		$is_admin        = $is_entry_detail || $is_form_editor;
		$field_label     = '';//$this->get_field_label( $force_frontend_label, $value );
		$field_id        = $is_admin || $form_id == 0 ? "input_{$this->id}" : 'input_' . $form_id . "_{$this->id}";
		$field_content   = ! $is_admin ? '{FIELD}' : $field_content = sprintf( "%s{FIELD}", $admin_buttons );
	 
		return $field_content;
	}
}

GF_Fields::register( new Simple_GF_Field() );
