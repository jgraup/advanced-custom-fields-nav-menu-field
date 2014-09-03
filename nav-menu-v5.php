<?php
/*
//	This is just a slightly modified version of Faison Zutavern's 
//	ACF V4 Field Nav Menu plugin to work with ACF V5.
//
//	ACF V5 Support added by Jesse Graupmann | http://www.justgooddesign.com
//
//	Original V4 plugin
//	http://wordpress.org/plugins/advanced-custom-fields-nav-menu-field/
//	http://faisonz.com/wordpress-plugins/advanced-custom-fields-nav-menu-field/
//
//	ACF V5 plugin Info
//	http://www.advancedcustomfields.com/resources/creating-a-new-field-type/
*/
class acf_field_nav_menu_v5 extends acf_field
{
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options
		 
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function __construct()
	{
		// vars
		$this->name = 'nav_menu';
		$this->label = __('Nav Menu', 'acf');
		$this->category = __("Relational",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			'save_format' => 'id',
			'allow_null' => -1,
			'container' => 'div'
		);
		 
		// do not delete!
		parent::__construct();

		// settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.1.2'
		); 
	} 
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function render_field_settings( $field )  
	{ 
		// Create Field Options HTML
		acf_render_field_setting( $field, array(
			'label'			=> __('Return Value','acf'), 
			'type'		=>	'radio',
			'name'		=> 'save_format', 
			'layout'	=>	'horizontal',
			'choices' 	=>	array(
				'object'	=>	__("Nav Menu Object",'acf'),
				'menu'		=>	__("Nav Menu HTML",'acf'),
				'id'		=>	__("Nav Menu ID",'acf')
			)
		));
		
		$choices = $this->get_allowed_nav_container_tags();
		$usingMenu = $field['save_format'] === 'menu' ? '*' : '.';
		acf_render_field_setting( $field, array(
			'label'			=> __('Menu Container','acf'),
			'instructions'	=> __('What to wrap the Menu\'s ul with.<br />Only used when returning HTML' . $usingMenu,'acf'),
			'type'		=>	'select',
			'name'		=> 'container', 
			'choices' 	=>	$choices
		)); 

		acf_render_field_setting( $field, array(
			'label'			=> __('Allow Null?','acf'), 
			'type'	=>	'radio', 
			'name'		=> 'allow_null', 
			'choices'	=>	array(
				1	=>	__("Yes",'acf'),
				0	=>	__("No",'acf'),
			),
			'layout'	=>	'horizontal',
		));  
	}

	
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	function render_field( $field )
	{
		// ob_start();
		// print_r($field);
		// error_log(ob_get_clean());

		// create Field HTML
		echo sprintf( '<select id="%d" class="%s" name="%s">', $field['id'], $field['class'], $field['name']  );

		// null
		if( $field['allow_null'] )
		{
			echo '<option value=""> - Select - </option>';
		}

		// Nav Menus
		$nav_menus = $this->get_nav_menus();

		foreach( $nav_menus as $nav_menu_id => $nav_menu_name ) {
			$selected = selected( $field['value'], $nav_menu_id );
			echo sprintf( '<option value="%1$d" %3$s>%2$s</option>', $nav_menu_id, $nav_menu_name, $selected );
		}

		echo '</select>';
	}

	function get_nav_menus() {
		$navs = get_terms('nav_menu', array( 'hide_empty' => false ) );
		
		$nav_menus = array();
		foreach( $navs as $nav ) {
			$nav_menus[ $nav->term_id ] = $nav->name;
		}

		return $nav_menus;
	}

	function get_allowed_nav_container_tags() {
		$tags = apply_filters( 'wp_nav_menu_container_allowedtags', array( 'div', 'nav' ) );
		$formatted_tags = array(
			array( '0' => 'None' )
		);
		foreach( $tags as $tag ) {
    		$formatted_tags[0][$tag] = ucfirst( $tag );
		}
		return $formatted_tags;
	}
	
	function format_value( $value, $post_id, $field )
	{
		// defaults
		$field = array_merge($this->defaults, $field);
		
		if( !$value ) {
			return false;
		}

		// check format
		if( $field['save_format'] == 'object' ) {
			$wp_menu_object = wp_get_nav_menu_object( $value );

			if( !$wp_menu_object ) {
				return false;
			}

			$menu_object = new stdClass;

			$menu_object->ID = $wp_menu_object->term_id;
			$menu_object->name = $wp_menu_object->name;
			$menu_object->slug = $wp_menu_object->slug;
			$menu_object->count = $wp_menu_object->count;

			return $menu_object;

		} elseif( $field['save_format'] == 'menu' ) {
			
			ob_start();

			wp_nav_menu( array(
				'menu' => $value,
				'container' => $field['container']
			) );
			
			return ob_get_clean();

		}
		return $value;
	}
}

// create field
new acf_field_nav_menu_v5();