<?php
   /*
   Plugin Name: OST 3D Image tag cloud : Beta 1.0
   Plugin URI: http://www.opensourcetechnologies.com/
   Description: 3D Image tag cloud displays your tags in 3D by placing them on a rotating images and links on tags. It is upgraded version of 3D tag cloud Wordpress Plugin ver 2.0.
   Version: 1.0
   Author: OpenSource Technologies
   Author URI: http://www.opensourcetechnologies.com/
   License: GPL2
   */
//includes the jquery file
add_action('admin_init', 'tagcloud_enq_scripts');
add_action("admin_menu", "cardoza_3d_tag_cloud_options");
add_action('wp_enqueue_scripts', 'tagcloud_enq_scripts');
add_action("plugins_loaded", "cardoza_3d_tagcloud_init");

function tagcloud_enq_scripts(){
	wp_enqueue_script('jquery');
	wp_enqueue_script('tag_handle', plugins_url('/jquery.tagcanvas.min.js', __FILE__), array('jquery'));
	//includes the css styles file
	wp_enqueue_style('my-style', plugin_dir_url(__FILE__). '3dcloud_style.css');
}

//The following function will retrieve all the avaialable 
//options from the wordpress database
function retrieve_options(){
	$opt_val = array(
			'title' => stripslashes(get_option('c3tdc_title')),
			'no_of_tags' => stripslashes(get_option('c3tdc_noof_tags')),
			'width' => stripslashes(get_option('c3tdc_width')),
			'height' => stripslashes(get_option('c3tdc_height')),
			'bg_color' => stripslashes(get_option('c3dtc_bg_color')),
			'txt_color' => stripslashes(get_option('c3dtc_txt_color')),
			'hlt_txt_color' => stripslashes(get_option('c3dtc_hlt_txt_color')),
			'font_name' => stripslashes(get_option('c3dtc_font_name')),
			'max_font_size' => stripslashes(get_option('c3dtc_max_font_size')),
			'min_font_size' => stripslashes(get_option('c3dtc_min_font_size'))
	); 
	return $opt_val;
}

add_action('wp_head','tagcloud_js_init');

function tagcloud_js_init(){
	$option_value = retrieve_options(); 
	if(!empty($option_value['txt_color'])) $canvas_txtcolor = $option_value['txt_color'];
	else $canvas_txtcolor = "333333";
	if(!empty($option_value['bg_color'])) $canvas_outlinecolor = $option_value['bg_color'];
	else $canvas_outlinecolor = "FFFFFF";
	?>
	<script type="text/javascript">
	$j = jQuery.noConflict();
	$j(document).ready(function() {
	    if(!$j('#myCanvas').tagcanvas({
	    	textColour: '#<?php echo $canvas_txtcolor;?>',
	        outlineColour: '#<?php echo $canvas_outlinecolor;?>',
	        reverse: true,
	        depth: 0.8,
	        textFont: null,
	        weight: true,
	        maxSpeed: 0.05
	    },'tags')) {
	        $j('#myCanvasContainer').hide();
	    }
	}); 
</script>
	<?php
}

function cardoza_3d_tag_cloud_options(){
	add_menu_page(
		__('3D Tag Cloud'), 
		'OST 3D Cloud', 
		'manage_options', 
		'slug_for_c3dtc', 
		'cardoza_3D_tc_options_page',
		plugin_dir_url(__FILE__).'images/tag-icon.png');
}

function cardoza_3D_tc_options_page(){
	$c_3d_tag_options = array(
			'c3d_title' => 'c3tdc_title',
			'c3d_noof_tags' => 'c3tdc_noof_tags',
			'c3d_width' => 'c3tdc_width',
			'c3d_height' => 'c3tdc_height',
			'c3d_bg_color' => 'c3dtc_bg_color',
			'c3d_txt_color' => 'c3dtc_txt_color',
			'c3d_font_name' => 'c3dtc_font_name',
			'c3d_max_font_size' => 'c3dtc_max_font_size',
			'c3d_min_font_size' => 'c3dtc_min_font_size'
	);	
	if(isset($_POST['frm_submit'])){
		if(!empty($_POST['frm_title'])) update_option($c_3d_tag_options['c3d_title'], $_POST['frm_title']);
		if(!empty($_POST['frm_noof_tags'])) update_option($c_3d_tag_options['c3d_noof_tags'], $_POST['frm_noof_tags']);
		if(!empty($_POST['frm_width'])) update_option($c_3d_tag_options['c3d_width'], $_POST['frm_width']);
		if(!empty($_POST['frm_height'])) update_option($c_3d_tag_options['c3d_height'], $_POST['frm_height']);
		if(!empty($_POST['frm_rot_speed'])) update_option($c_3d_tag_options['c3d_rot_speed'], $_POST['frm_rot_speed']);
		if(!empty($_POST['frm_bg_color'])) update_option($c_3d_tag_options['c3d_bg_color'], $_POST['frm_bg_color']);
		if(!empty($_POST['frm_txt_color'])) update_option($c_3d_tag_options['c3d_txt_color'], $_POST['frm_txt_color']);
		if(!empty($_POST['frm_font_name'])) update_option($c_3d_tag_options['c3d_font_name'], $_POST['frm_font_name']);
		if(!empty($_POST['frm_max_font_size'])) update_option($c_3d_tag_options['c3d_max_font_size'], $_POST['frm_max_font_size']);
		if(!empty($_POST['frm_min_font_size'])) update_option($c_3d_tag_options['c3d_min_font_size'], $_POST['frm_min_font_size']);
?>
<div id="message" class="updated fade"><p><strong><?php _e('Options saved.', 'cardozatagcloud' ); ?></strong></p></div>
<?php	
	}
	$option_value = retrieve_options();
?>
	<div class="wrap">
		<h2><?php _e("OST 3D Image Cloud Settings", "cardozatagcloud");?></h2><br />
		<!-- Administration panel form -->
		<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<h3><?php _e('General Settings','cardozatagcloud');?></h3>
		<table>
        <tr><td width="200"><b><?php _e('Widget Title','cardozatagcloud');?>:</b></td>
        <td><input type="text" name="frm_title" size="50" value="<?php echo $option_value['title'];?>"/></td></tr>
		<tr><td width="150"><b><?php _e('Number of tags','cardozatagcloud');?>:</b></td>
        <td><input type="text" name="frm_noof_tags" value="<?php echo $option_value['no_of_tags'];?>"/></td></tr>
		<tr><td width="150"><b><?php _e('Widget area width','cardozatagcloud');?>:</b></td>
        <td><input type="text" name="frm_width" value="<?php echo $option_value['width'];?>"/>px</td></tr>
		<tr><td width="150"><b><?php _e('Widget area height','cardozatagcloud');?>:</b></td>
        <td><input type="text" name="frm_height" value="<?php echo $option_value['height'];?>"/>px</td></tr>
        </table><br />
   		<table>
		
		<tr height="50"><td></td><td><input type="submit" name="frm_submit" value="Update Options"/></td></tr>
		</table>
		</form>
	</div>
<?php
}

function widget_cardoza_3d_tagcloud($args){
	$option_value = retrieve_options();
	extract($args);
	echo $before_widget;
	echo $before_title;
	echo $option_value['title'];
	echo $after_title;
	global $wpdb;
	$tags_list = get_terms('post_tag', array(
				'orderby' 		=> 'count',
				'hide_empty' 	=> 0
			));

	

	if(sizeof($tags_list)!=0){
		$max_count = 0;
        if(!empty($option_value['height'])) $canvas_height = $option_value['height'];
        else $canvas_height = "250";
        if(!empty($option_value['width'])) $canvas_width = $option_value['width'];
		else $canvas_width = "250";
		if(!empty($option_value['bg_color'])) $canvas_bgcolor = $option_value['bg_color'];
		else $canvas_bgcolor = "FFFFFF";
		
		
		foreach($tags_list as $tag) if($tag->count > $max_count) $max_count = $tag->count;  ?>

		<div id="myCanvasContainer" style="background-color:#<?php echo $canvas_bgcolor;?>;">
			<canvas width="<?php echo $canvas_width;?>" height="<?php echo $canvas_height;?>" id="myCanvas" >
				<p>Anything in here will be replaced on browsers that support the canvas element</p>
			</canvas>
        </div>
        <div id="tags" >
                
		<ul style="
		font-family: <?php if(!empty($option_value['font_name'])) echo $option_value['font_name'];
			else echo "Calibri";?>;
		height:
		<?php 
			if(!empty($option_value['height'])) echo $option_value['height'];
			else echo "250";
		?>px;
		width:
		<?php 
			if(!empty($option_value['width'])) echo $option_value['width'];
			else echo "250";
		?>px;
		">
		<?php 
		if(empty($option_value['no_of_tags'])) $option_value['no_of_tags'] = 15;
		if(empty($option_value['max_font_size'])) $option_value['max_font_size'] = 40;
		if(empty($option_value['min_font_size'])) $option_value['max_font_size'] = 3;
		$i=1;

		
		
		foreach($tags_list as $tag){

			$pid = $wpdb->get_var( "SELECT object_id FROM $wpdb->term_relationships where term_taxonomy_id='".$tag->term_id."'" );
			$ost_thumbnail = get_the_post_thumbnail($pid, array(50,50));


			if($i <= $option_value['no_of_tags']){
			$font_size = $option_value['max_font_size'] - (($max_count - $tag->count)*2);

			if($font_size < $option_value['min_font_size']) $font_size = $option_value['min_font_size'];
			//echo "<li><a href=/"'.$_SERVER['PHP_SELF'].'?tag='.$tag->slug.'/"><img src='http://localhost/wp3.6/wp-content/uploads/2013/10/Dashbaord.png' height='50px'/></a></li>" ;
			//echo '<li><a href="'.$_SERVER['PHP_SELF'].'?tag='.$tag->slug.'" style="font-size:'.$font_size.'px;color:">'.'<img src='.$tag->description.' height="50px"/>'.'</a></li>';
			if(!empty($ost_thumbnail))
				echo '<li><a href="'.$_SERVER['PHP_SELF'].'?tag='.$tag->slug.'" style="font-size:'.$font_size.'px;color:">'.$ost_thumbnail.'</a></li>';
			else
				echo '<li><a href="'.$_SERVER['PHP_SELF'].'?tag='.$tag->slug.'" style="font-size:'.$font_size.'px;color:">'.$tag->slug.'</a></li>';
            //echo $tag->description;
            $i++;
			}
		}
	echo '</ul></div>';
	}
	else echo "No tags found";
	echo $after_widget;
}


function cardoza_3d_tagcloud_init(){
	load_plugin_textdomain('cardozatagcloud', false, dirname( plugin_basename(__FILE__)).'/languages');
	wp_register_sidebar_widget('3d_tag_cloud', __('OST 3D Image Cloud'), 'widget_cardoza_3d_tagcloud');
}
?>
