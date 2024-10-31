<?php
/*
Plugin Name: Red Blue jQuery Floater Widget
Plugin URI: http://redbluewebsites.com
Description: Red Blue Websites Floater Widget
Version: 1.0
Author: Red Blue Websites
Author URI: http://redbluewesbites.com
*/

/**
* The Red Blue Websites Widget Base Class
*/
class RedBlue_Widget_Floater extends WP_Widget {
	protected $options = array();
	protected $description = "";
	function RedBlue_Widget_Floater($shortname, $name, $description) {
		$widget_ops = array('classname' => 'widget_text', 'description' => $description );
		$this->WP_Widget($shortname, $name, $widget_ops);
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		return $new_instance;
	}
	function form($instance) {
		$options = $this->options;
?>
		<div style="float: right;"><img style="float: right;" src="<?php echo WP_PLUGIN_URL."/".plugin_basename(dirname(__FILE__))."/images/logo.png";?>"/>
		<?php echo $this->description;?></div>
		<div style="clear: both; height: 15px;"></div>
			<?php foreach($options as $option){ ?>
			<?php switch($option["type"]){ 
					case "text":?>
					<p><label for="<?php echo $this->get_field_id($option["id"]); ?>"><?php echo $option["name"];?>
					<input class="widefat" id="<?php echo $this->get_field_id($option["id"]); ?>" 
						name="<?php echo $this->get_field_name($option["id"]); ?>" type="text" 
						value="<?php echo attribute_escape($instance[$option["id"]]); ?>" />
					</label></p>
					<?php break; ?>
				<?php case "textarea":?>
					<p><label for="<?php echo $this->get_field_id($option["id"]); ?>"><?php echo $option["name"];?><textarea rows="6" class="widefat" id="<?php echo $this->get_field_id($option["id"]); ?>" name="<?php echo $this->get_field_name($option["id"]); ?>"><?php echo attribute_escape($instance[$option["id"]]); ?></textarea></label></p>
					<?php break; ?>
				<?php case "checkbox":?>
					<p><label for="<?php echo $this->get_field_id($option["id"]); ?>"><?php echo $option["name"];?><input type="checkbox" class="widefat" id="<?php echo $this->get_field_id($option["id"]); ?>" name="<?php echo $this->get_field_name($option["id"]); ?>" <?php if($instance[$option["id"]]) echo "checked='checked';"; ?>/></label></p>
					<?php break; ?>
				<?php case "select":?>
					<p><label for="<?php echo $this->get_field_id($option["id"]); ?>"><?php echo $option["name"];?>
					<select class="widefat" id="<?php echo $this->get_field_id($option["id"]); ?>" name="<?php echo $this->get_field_name($option["id"]); ?>" ><?php foreach($option["options"] as $opt):?>
							<option <?php if(attribute_escape($instance[$option["id"]]) == $opt){echo "selected='selected'";}; ?> value="<?php echo $opt;?>"><?php echo $opt;?></option>
						<?php endforeach;?>
					</select></label></p>
					<?php break; ?>
				<?php } ?>
			<?php }; ?>
<?php
	}
}

/**
* The Red Blue Websites Floater Widget
*/
class RedBlue_Floater_Widget extends RedBlue_Widget_Floater {
	function RedBlue_Floater_Widget() {
		$widget_ops = array('classname' => 'widget_text', 'description' => 'Displays arbitrary text/html and makes the widget stick to the top upon scrolling.' );
		$this->WP_Widget('redbluefloaterwidget', 'Red Blue Floater Widget', $widget_ops);
		
		$this->options = array(
			array("name" => "Title:",
						"id" => "title",
						"type" => "text"
			),
			array("name" => "Text:",
						"id" => "content",
						"type" => "textarea"
			)
		);
		$this->description = "Displays any html or text content and makes it float upon scrolling.";
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = $instance['title'];
		$widget_id = $this->id;
		echo '<div style="clear: both;"></div>';
		echo "<div class='".$this->id."-placeholder'>";
		echo "<div class='redbluefloater-anchor ".$this->id."-anchor'></div>";
		echo "<div class='redbluefloater ".$this->id."' style='z-index: 1000;'>";
		echo $before_widget; 
		echo $before_title . $title . $after_title; 
		echo $instance["content"];
				echo <<<END
<script type="text/javascript">
	jQuery(window).scroll(function() {
		var b = jQuery(window).scrollTop();
		var d = jQuery(".$widget_id-anchor").offset().top;
		var p = jQuery(".$widget_id-placeholder");
		var c = jQuery(".$widget_id");
		var w = c.width();
		var h = c.height();
		
		p.css({width: c.width()+"px", height: (c.height()+10)+"px"});
		
		var widgets = jQuery(".redbluefloater");
		var prev = null;
		var prev_num = 0;
		var total_height = 0;
		for(var i = 0; i < widgets.length; i++){
			if(widgets[i] == c[0] && i > 0){
				prev = jQuery(widgets[i-1]);
				prev_num = i;
				break;
			}
			total_height += jQuery(widgets[i]).height();
		}
		
		if(prev && prev.offset().top + prev.height() > d){
			c.css({position:"fixed", top: total_height, width: w});
		} else if (b>d) {
			c.css({position:"fixed",top: 0, width: w});
		} else if (b<=d) {
			c.css({position:"relative",top:"auto", width: "100%"});
		}
	});
</script>
END;
		echo $after_widget;
		echo "</div></div>";
	}
}

function redbluefloaterwidget_winit(){
	register_widget('RedBlue_Floater_Widget');
}

add_action( 'widgets_init', 'redbluefloaterwidget_winit' );

function redbluefloaterwidget_init(){
	//wp_enqueue_script( 'jquery' );
	//wp_enqueue_script('redbluefloaterwidget',
	//	plugins_url('/script.js', __FILE__),
	//	array('jquery'),
	//	'1.0' );
}

add_action("init", "redbluefloaterwidget_init");

?>