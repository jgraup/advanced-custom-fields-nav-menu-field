=== Advanced Custom Fields: Nav Menu Field ===
Contributors: Faison
Tags: Advanced Custom Fields, acf, acf4, acf5, custom fields, admin, menu, nav menu, navigation
Author: Faison Zutavern
Author URI: http://faisonz.com
Requires at least: 3.4
Tested up to: 4.0
Stable tag: 2.0.0
License: GPL2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add-On plugin for Advanced Custom Fields (ACF) that adds a 'Nav Menu' Field type.

== Description ==

Add [Navigation Menus](http://codex.wordpress.org/Navigation_Menus) to [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) (ACF) with the Nav Menu Field plugin! This plugin adds the Nav Menu Field type to ACF (version 5 & 4), allowing you to select from the menus you create in the WordPress Admin backend to use on your website's frontend.

Using ACF, you can set the Nav Menu Field to return the selected menu's:

*	ID for lightweight coding,
*	Object for more involved programming, or
*	HTML (generated from [wp_nav_menu](http://codex.wordpress.org/Function_Reference/wp_nav_menu)) for quickly displaying a menu.

I created this plugin because I needed to display a secondary menu that changed depending on what page you're on. Most of those pages were children of the same page, but then I had to throw a couple of Custom Post Types in there too. Because of the Custom Post Types, I couldn't just grab the top most parent for the current page and use [wp_list_pages](http://codex.wordpress.org/Function_Reference/wp_list_pages). So I did some research and decided to extend the functionality of my favourite plugin, [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/). Now when I create a new Page or Custom Post, I just select the menu from a drop down menu!

Feel free to try this add-on on your dev site, ask questions on the support link above, and please review this add-on. By leaving a rating and review, you help this plugin become even better!

= Advanced Custom Fields Compatibility =

This add-on will work with:

* version 5
* version 4

== Installation ==

Follow the following instructions: https://codex.wordpress.org/Managing_Plugins#Installing_Plugins

== Frequently Asked Questions ==

= Can you show a quick example of how to use this? =

Sure can!

1.	Create a new field group
2.	Add a Nav Menu and set the Field Label to `Side Menu` (this will cause the Field Name to be `side_menu`)
3.	Set the Nav Menu's Return Value to `Nav Menu HTML`
4.	Set the Location Rules to Show if "Post Type" "is equal to" "Page"
5.	Save the Field Group
6.	Now in your themes sidebar.php, put the following code before or after any of the div's with class="widget-area"
`
<?php if( get_field( 'side_menu' ) ) : ?>
	<div class="widget-area">
		<?php the_field( 'side_menu' ); ?>
	</div>
<?php endif; ?>
`
Finally, create or edit a page, select a menu in the Side Menu field, and view the page to see that menu in the sidebar!

= Will you make this plugin compatible with Advanced Custom Fields v3? =

No.

= Why does the Nav Menu returned by your plugin look like an unstyled list of links? =

So that you can style it yourself. I don't want to step on your toes :)

= I added the Nav Menu Field to Pages, selected my menu when creating a new page, but the menu doesn't show. What gives? =

First, check that you added the necessary ACF code to your templates. If you don't know what I'm talking about, read up on how to use [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/). If you're already familiar with ACF and you still can't figure out why the menu isn't showing up, start a new support thread, include details and a little code, and I'll do my best to help you.

== Screenshots ==

1. Adding the Nav Menu Field to an Advanced Custom Fields Field Group

2. Creating your menu in the WordPress Admin backend

3. Selecting the previously created menu in the meta box created by Advanced Custom Fields

4. Viewing the (not styled) menu displayed by calling 'the_field()' in the sidebar

== Changelog ==

= 2.0.0 =
* Added ACF v5 class.
* Updated code to follow coding standards
* Updated the ACF v4 class to use the updated code found in the ACF v5 Class

= 1.1.2 =
* Fixed a silly mistake related to allowing Null for a Nav Menu Field. Basically, it was storing the string "null" when you don't select a menu, that's taken care of now.

= 1.1.1 =
* I forgot to add a default value for the Menu Container field, so I added 'div' as the default value. If you upgraded from 1.0.0 to 1.1.0 and had WP_DEBUG enabled, you would receive a warning about an unknown index. Since I like debug mode to run without warnings, I fixed this.

= 1.1.0 =
* Added a field which allows users to choose the containing element for the Menu's ul. See [wp_nav_menu's container parameter](http://codex.wordpress.org/Function_Reference/wp_nav_menu#Parameters)

= 1.0.0 =
* Initial Release.

== Upgrade Notice ==

= 2.0.0 =
You now have support for ACF v5, and when the code is read, it makes fewer people cry!

= 1.1.1 =
I forgot to add a default value for the Menu Container field. So to eliminate WP_DEBUG warnings, I added 'div' as the default value. Please upgrade to avoid the warnings.

= 1.1.0 =
Added a new minor feature for selecting the Menu's containing element.

= 1.0.0 =
If you have a version less than 0.1.0, something went really, really wrong. Upgrade now, because I have no idea what will happen if you don't!