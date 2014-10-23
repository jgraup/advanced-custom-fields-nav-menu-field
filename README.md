# Advanced Custom Fields: Nav Menu Field #

This is an [Add-On plugin](http://wordpress.org/extend/plugins/advanced-custom-fields/)  [*](http://support.advancedcustomfields.com/forums/forum/add-ons/user-submitted/) [*](https://github.com/reyhoun/awesome-acf) for Advanced Custom Fields (ACF) [4](http://www.advancedcustomfields.com/) & [Pro 5](http://www.advancedcustomfields.com/pro) that adds a 'Nav Menu' Field type, allowing you to select from the [menus](http://codex.wordpress.org/Navigation_Menus) you create in the WordPress Admin backend to use on your website's frontend. 

![](http://faisonz.com/wp-content/uploads/2014/01/acf-nav-menu-field-banner-770x250.png)


Using ACF, you can set the Nav Menu Field to return the selected menu’s:

| Return Value   | When to use  |
| ------ | ------------ |
| ID     | for lightweight coding |
| Object | for more involved programming  |
| HTML   | (generated from [wp_nav_menu](http://codex.wordpress.org/Function_Reference/wp_nav_menu)) for quickly displaying a menu  |


## ACF 4 & Pro 5

This version adds support for [ACF Pro 5](http://www.advancedcustomfields.com/pro) to the exisiting [Nav Menu Field plugin](http://wordpress.org/plugins/advanced-custom-fields-nav-menu-field/) by [Faison Zutavern](http://faisonz.com/wordpress-plugins/advanced-custom-fields-nav-menu-field/)  [@Faison](https://github.com/Faison) [@FaisonZ](https://twitter.com/FaisonZ/).

## UPDATED (10/15/2014)

The original vesion has been updated to include support for ACF 5 Pro. Now you can update directly from WP at [Nav Menu Field plugin page](http://wordpress.org/plugins/advanced-custom-fields-nav-menu-field/) or follow the discussion on the WP Support Forum: [Advanced Custom Fields: Nav Menu Field
[resolved] ACF 5 compatibility](https://wordpress.org/support/topic/acf-5-compatibility). Since this repo was created to add new functionality, I would recommend using the original version since it will most likely have better support moving forward. If you don't really care or just want to see some examples then please continue to read on.

## Example

1. Set up your ACF in the backend and select "Nav Menu ID" as the Return Value
2. On a page or post, select your menu from the dropdown and update the entry
3. Then get the menu you always wanted with:

````php
// Your post id
$post_id = 259; 

// Your ACF field id
$acf_field_id = 'sidebar_menu'; 

// Get the menu id
$field = get_field($acf_field_id, $post_id);  // ID

$titles = array();

if(!empty($field)){

  // Assuming we selected "Nav Menu ID" as our return type
  // Get the menu object
  $items = wp_get_nav_menu_items($field);
  
  if($items){
    $titles = wp_list_pluck($items, 'title');
  }
}

// Now do something with the titles
echo implode (" / ", $titles);
print_r($titles);
error_log(json_encode($items));
````

## Example

This example shows how you can dynamically check the Return Value before acting on the data.

````php
class NavFieldTypes
{
	const UNKNOWN = -1;
	const OBJECT = 0;
	const HTML = 1;
	const ID = 2;
}

// Your post id
$post_id = 2; 

// Your ACF field id
$acf_field_id = 'sidebar_menu'; 

// Get the menu id
$field = get_field($acf_field_id, $post_id);  // Object | HTML | ID

// We don't know the type yet
$field_type = NavFieldTypes::UNKNOWN;

// Let's find out...
if(is_object($field)) 
	$field_type = NavFieldTypes::OBJECT; 
else if (is_scalar($field)){	
	$field_type = !is_numeric($field) ? 
		NavFieldTypes::HTML : NavFieldTypes::ID;	 
} 

// OK, based on what we know we should...
switch ($field_type)
{	
  case NavFieldTypes::OBJECT:
	
	// $field -> {ID,name,slug,count}
	
	echo "OBJECT: {$field->name} | "; 
	$items = wp_get_nav_menu_items($field->ID);
	$wrapItem = function ($ID, $title)
	{
		return "<a href='#{$ID}'>{$title}</a>";
	};
	echo implode (" / ", array_map($wrapItem, 
		wp_list_pluck($items, 'ID'), 
		wp_list_pluck($items, 'title')));
    break;
	
  case NavFieldTypes::HTML:
	
	echo "HTML: " . $field;
    break;
	
  case NavFieldTypes::ID:
	
	echo "ID: {$field} | "; 
	$items = wp_get_nav_menu_items($field);
	echo render_nav_menu_items($items);
    break;
	
  default:
	// Nothing to see here
}

function render_nav_menu_items($items){
	ob_start();
	if($items){
		$titles = array();
		foreach($items as $key => $value){
			$titles[] = $value->title;
		}
		echo implode (" / ", $titles);
	}
	return ob_get_clean();
}
````

## Links

* [Original ACF 4 Plugin](http://wordpress.org/plugins/advanced-custom-fields-nav-menu-field/)
* [ACF 4 on WordPress.org](http://wordpress.org/plugins/advanced-custom-fields/)
* [ACF 4](http://www.advancedcustomfields.com/)
* [ACF Pro 5](http://www.advancedcustomfields.com/pro)
* [ACF Support Forum: User Submitted Thread : Nav Menu Field] (http://support.advancedcustomfields.com/forums/topic/nav-menu-field/)
* [Creating an ACF Plugin](http://wordpress.org/extend/plugins/advanced-custom-fields/)
* [Get Field in ACF](http://www.advancedcustomfields.com/resources/get_field/)
* [Nav Menus](http://codex.wordpress.org/Navigation_Menus)
* [Awesome Advanced Custom Field : Links](https://github.com/reyhoun/awesome-acf)
