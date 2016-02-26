<?php
/**
* Nav Menu Field v4
*
* @package ACF Nav Menu Field
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ACF_Field_Nav_Menu_V4 Class
 *
 * This class contains all the custom workings for the Nav Menu Field for ACF v4
 */
class ACF_Field_Nav_Menu_V4 extends acf_field {

	/**
	 * Sets up some default values and delegats work to the parent constructor.
	 */
	public function __construct() {
		$this->name     = 'nav_menu';
		$this->label    = __( 'Nav Menu' );
		$this->category = __( 'Relational' ); // Basic, Content, Choice, etc
		$this->defaults = array(
			'save_format' => 'id',
			'allow_null'  => 0,
			'container'   => 'div',
		);

		parent::__construct();
	}

	/**
	 * Renders the Nav Menu Field options seen when editing a Nav Menu Field.
	 *
	 * @param array $field The array representation of the current Nav Menu Field.
	 */
	public function create_options( $field ) {
		$field = array_merge( $this->defaults, $field );
		$key   = $field['name'];

		// Create Field Options HTML
		?>
		<tr class="field_option field_option_<?php echo esc_attr( $this->name ); ?>">
			<td class="label">
				<label><?php _e( 'Return Value' ); ?></label>
			</td>
			<td>
			<?php
				do_action('acf/create_field', array(
					'type'    => 'radio',
					'name'    => 'fields['.$key.'][save_format]',
					'value'   => $field['save_format'],
					'layout'  => 'horizontal',
					'choices' => array(
						'object' => __( 'Nav Menu Object' ),
						'menu'   => __( 'Nav Menu HTML' ),
						'id'     => __( 'Nav Menu ID' ),
					),
				) );
			?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo esc_attr( $this->name ); ?>">
			<td class="label">
				<label><?php _e( 'Menu Container' ); ?></label>
				<p class="description"><?php _e( "What to wrap the Menu's ul with (when returning HTML only)" ) ?></p>
			</td>
			<td>
			<?php
				do_action('acf/create_field', array(
					'type'         => 'radio',
					'layout'       => 'horizontal',
					'name'    => 'fields['.$key.'][container]',
					'value'   => $field['container'],
					'choices' => $this->get_allowed_nav_container_tags(),
				) );
			?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo esc_attr( $this->name ); ?>">
			<td class="label">
				<label><?php _e( 'Allow Null?' ); ?></label>
			</td>
			<td>
			<?php
				do_action('acf/create_field', array(
					'type'    => 'radio',
					'name'    => 'fields['.$key.'][allow_null]',
					'value'   => $field['allow_null'],
					'layout'  => 'horizontal',
					'choices' => array(
						1 => __( 'Yes' ),
						0 => __( 'No' ),
					),
				) );
			?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Renders the Nav Menu Field.
	 *
	 * @param array $field The array representation of the current Nav Menu Field.
	 */
	public function create_field( $field ) {
		$allow_null = $field['allow_null'];
		$nav_menus  = $this->get_nav_menus( $allow_null );

		if ( empty( $nav_menus ) ) {
			return;
		}
		?>
		<select id="<?php esc_attr( $field['id'] ); ?>" class="<?php echo esc_attr( $field['class'] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>">
		<?php foreach( $nav_menus as $nav_menu_id => $nav_menu_name ) : ?>
			<option value="<?php echo esc_attr( $nav_menu_id ); ?>" <?php selected( $field['value'], $nav_menu_id ); ?>>
				<?php echo esc_html( $nav_menu_name ); ?>
			</option>
		<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Gets a list of Nav Menus indexed by their Nav Menu IDs.
	 *
	 * @param bool $allow_null If true, prepends the null option.
	 *
	 * @return array An array of Nav Menus indexed by their Nav Menu IDs.
	 */
	private function get_nav_menus( $allow_null = false ) {
		$navs = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		$nav_menus = array();

		if ( $allow_null ) {
			$nav_menus[''] = ' - Select - ';
		}

		foreach ( $navs as $nav ) {
			$nav_menus[ $nav->term_id ] = $nav->name;
		}

		return $nav_menus;
	}

	/**
	 * Get the allowed wrapper tags for use with wp_nav_menu().
	 *
	 * @return array An array of allowed wrapper tags.
	 */
	private function get_allowed_nav_container_tags() {
		$tags           = apply_filters( 'wp_nav_menu_container_allowedtags', array( 'div', 'nav' ) );
		$formatted_tags = array(
			'0' => 'None',
		);

		foreach ( $tags as $tag ) {
			$formatted_tags[$tag] = ucfirst( $tag );
		}

		return $formatted_tags;
	}

	/**
	 * Renders the Nav Menu Field.
	 *
	 * @param int   $value   The Nav Menu ID selected for this Nav Menu Field.
	 * @param int   $post_id The Post ID this $value is associated with.
	 * @param array $field   The array representation of the current Nav Menu Field.
	 *
	 * @return mixed The Nav Menu ID, or the Nav Menu HTML, or the Nav Menu Object, or false.
	 */
	public function format_value_for_api( $value, $post_id, $field ) {
		$field = array_merge($this->defaults, $field);
		
		if( empty( $value ) ) {
			return false;
		}

		// check format
		if( 'object' == $field['save_format'] ) {
			$wp_menu_object = wp_get_nav_menu_object( $value );

			if( empty( $wp_menu_object ) ) {
				return false;
			}

			$menu_object = new stdClass;

			$menu_object->ID    = $wp_menu_object->term_id;
			$menu_object->name  = $wp_menu_object->name;
			$menu_object->slug  = $wp_menu_object->slug;
			$menu_object->count = $wp_menu_object->count;

			return $menu_object;

		} elseif( 'menu' == $field['save_format'] ) {
			ob_start();

			wp_nav_menu( array(
				'menu' => $value,
				'container' => $field['container']
			) );

			return ob_get_clean();
		}

		// Just return the Nav Menu ID
		return $value;
	}
}

new ACF_Field_Nav_Menu_V4();
