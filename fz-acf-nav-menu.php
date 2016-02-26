<?php
/*
 * Plugin Name: Advanced Custom Fields: Nav Menu Field
 * Plugin URI: http://faisonz.com/wordpress-plugins/advanced-custom-fields-nav-menu-field/
 * Description: Add-On plugin for Advanced Custom Fields (ACF) that adds a 'Nav Menu' Field type.
 * Version: 2.0.0
 * Author: Faison Zutavern
 * Author URI: http://faisonz.com
 * License: GPL2 or later
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACF_Nav_Menu_Field_Plugin {

	/**
	 * Adds register hooks for the Nav Menu Field.
	 */
	public function __construct() {
		// version 4
		add_action( 'acf/register_fields', array( $this, 'register_field_v4' ) );	

		// version 5
		add_action( 'acf/include_field_types', array( $this, 'register_field_v5' ) );
	}

	/**
	 * Loads up the Nav Menu Field for ACF v4
	 */
	public function register_field_v4() {
		include_once 'nav-menu-v4.php';
	}

	/**
	 * Loads up the Nav Menu Field for ACF v5
	 */
	public function register_field_v5() {
		include_once 'nav-menu-v5.php';
	}
	
}

new ACF_Nav_Menu_Field_Plugin();
