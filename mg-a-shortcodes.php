<?php

/*
*		Here, There are 3 short codes generated
*		
*		1] [mg-testimonial]			: 	for creating [SINGLE] testimonials. E.g. [mg-testimonial name="User Name" role="Administrator"] This is an awesome site. [/mg-testimonial]
*		2] [mg-post-testimonial]	: 	for creating [MULTIPLE] testimonials by using CUSTOM POST "MG TESTIMONIAL". 
										Note: Please create testimonials to use this shortcode. Use "MG Tetimonial->Add New" create multiple testimonials.
*		3] [mg-carousel]			:	If you want to create [CAROUSEL] of testimonials then use it. This is useful for both shortcodes [mg-testimonial] & [mg-post-testimonial].
										Note: Use it if you have [MULTIPLE] Testimonials.
*/
										
										
										
/*
*
*		Short Code-1 :	For custom post type [MG TESTIMONIAL]
*						This shortcode is useful for [MULTIPLE] Testimonials
*----------------------------------------------------------------------*/

/**
 * Add Shortcode
 * Generate 'mg-post-testimonial' shortcode
 *
 * @since MG Advanced Testimonial 1.0
 */
add_shortcode('mg-post-testimonial', function() {
	
	//	Get testimonials of CUSTOM POST type ['mg_testimonial']
	$loop = new WP_Query(
		array(
			'post_type' => 'mg_testimonial',
			'order_by' => 'title'
		)
	);
	
	//	Check testimonials are available or not
	if($loop->have_posts()){
	
		//	set empty value for default
		$data = "";
		
		//	Execute loop
		while( $loop-> have_posts())
		{
			$loop->the_post();
			
			//	Get Meta Data
			$meta = get_post_meta(get_the_id());
			
			/*	Check meta values of the testimonials
			*
			*	Check meta value of [mg-class]
			*------------------------------------------------*/
			if( (isset($meta['mg-class'][0])) && (!empty($meta['mg-class'][0])) ) {
				$class = $meta['mg-class'][0];
			}
			else {
				$class = "simple";
			}//	default value 'simple'

			
			//	Check meta value of [mg-position]
			if( (isset($meta['mg-position'][0])) && ($meta['mg-position'][0] != "") ) {
					$position = $meta['mg-position'][0];
			}
			else {
					$position = "Administrator";
			}//	default 'Administrator'
			
			
			//	Check meta value of [mg-quote]
			if(isset($meta['mg-quote'][0]) && $meta['mg-quote'][0] != "") {
				if($meta['mg-quote'][0] == "1") {
					$quote = true;
				}
				else {
					$quote = false;
				}
			}
			else {
				$quote = false;
			}//		default 'false'
			
			
			//	Check meta value of [mg-iconfont]
			if(isset($meta['mg-show'][0]) && $meta['mg-show'][0] != "") {
				if($meta['mg-show'][0] == "iconfont") {
					$show = "iconfont";
				}
				else if($meta['mg-show'][0] == "image") {
					$show = "image";
				}
				else if($meta['mg-show'][0] == "none") {
					$show = "none";
				}
			}
			else {
					$show = "none";
			}//	Set [SHOW] default 'none'

			
			//	Show testimonials
			$data .= '	<div class="testimonial item ' .$class. '">';
			$data .= '			<blockquote class="">';
										if($quote){ 
			$data .= '							<span class="mg-quote-icon fa fa-quote-left fa-2x"></span>';
			$data .= '							<p class="mg-quote-p">'. get_the_content().'</p>';
			$data .= '							<span class="mg-quote-icon fa fa-quote-right fa-2x pull-right"></span><br>';
										}
										else {
			$data .= '							<p>'. get_the_content() .'</p>';								
										}//	Default show WITHOUT Quotes
									if($show=='image') {	
			$data .= '							<p style="clear: both;">';
			$data .= '								<img class="pull-left mg-img" src="" />';
			$data .= '								<span class="mg-img-name">' .get_the_title(). '</span><br>';
			$data .= '								<span class="mg-img-role">' .$position. '</span>';
			$data .= '							</p>	';
									}
									else if($show=='iconfont') {
			$data .= '							<p style="clear: both;">';
			$data .= '								<span class="mg-icon fa fa-user pull-left"></span>';
			$data .= '								<span class="pull-left"> ';
			$data .= '									<span class="mg-name">' .get_the_title(). '</span><br>';
			$data .= '									<span class="mg-role">' .$position. '</span>';
			$data .= '								</span>';
			$data .= '							</p>	';
									}//	If isset Icon Font true then SHOW Icon Font with User Name & Position
									else if($show == 'none') {
			$data .= ' 						<small>'. get_the_title() .' <cite title="Source Title">'.$position.'</cite></small>';
									}//	Default: User name + Designation
			$data .= '		</blockquote>';
			$data .= '	</div>';
		}
	}
		return $data;
});




/*
*		Short Code-2 : 	For simple shortcode
*						This shortcode is useful for [SINGLE] Testimony
*----------------------------------------------------------------------*/

/**
 * Add Shortcode
 * Generate 'mg-testimonial' shortcode
 *
 * @since MG Advanced Testimonial 1.0
 */
 
add_shortcode('mg-testimonial', function($atts, $content) {
	
	
	//	Set default attributes for shortcode
	$atts = shortcode_atts(
			array(
					'dir' => 'l',
					'content' => !empty($content) ? $content : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.',
					'name' => 'Mahesh Waghmare',
					'role' => 'Administrator',
					'class' => 'simple',
					'show' => 'normal',		//	Show User name with Icon Font, Image or Normal [Default: normal]
					'quote' => 'false'
				), $atts
			);
		extract($atts);
		
		//	Set direction of testimonial default [right]
		if($dir=="r"){$dir="pull-right";}
		else{$dir="pull-left";}

	//	Show single testimony
	$mgs  = '	<div class="testimonial item '. $class .'">';
	$mgs .= '			<blockquote class="'. $dir .'">';
								if($quote=='true'){ 
	$mgs .= '							<span class="mg-quote-icon fa fa-quote-left fa-2x"></span>';
	$mgs .= '							<p class="mg-quote-p">'. $content .'</p>';
	$mgs .= '							<span class="mg-quote-icon fa fa-quote-right fa-2x pull-right"></span><br>';
								}
								else {
	$mgs .= '							<p>'. $content .'</p>';								
								}//	Default show WITHOUT Quotes
							if($show=='image') {
	$mgs .= '							<p style="clear: both;">';
	$mgs .= '								<img class="pull-left mg-img" src="" />';
	$mgs .= '								<span class="mg-img-name">'. $name .'</span><br>';
	$mgs .= '								<span class="mg-img-role">'. $role .'</span>';
	$mgs .= '							</p>	';
							}
							else if($show=='icon') {
	$mgs .= '							<p style="clear: both;">';
	$mgs .= '								<span class="mg-icon fa fa-user pull-left"></span>';
	$mgs .= '								<span class="pull-left"> ';
	$mgs .= '									<span class="mg-name">'. $name .'</span><br>';
	$mgs .= '									<span class="mg-role">'. $role .'</span>';
	$mgs .= '								</span>';
	$mgs .= '							</p>	';
							}//	If isset Icon Font true then SHOW Icon Font with User Name & Position
							else if($show == 'normal') {
									$mgs .= ' <small>'. $name .' <cite title="Source Title"> '. $role .'</cite></small>';
							}//	Default: User name + Designation
	$mgs .= '		</blockquote>';
	$mgs .= '	</div>';
				
	return $mgs;
});
	
	


	
	
/*
*		Short Code-3 : 	For Carsousel
*						This is used for creating [CARSOUSEL] of testimonials. To use that [CLASS] field is mendatory.
*----------------------------------------------------------------------*/
	
/**
 * Add Shortcode
 * Generate 'mg-carousel' shortcode
 *
 * @since MG Advanced Testimonial 1.0
 */

 add_shortcode("mg-carousel", function($atts, $content) {

	$atts = shortcode_atts(
			array(
					'content' => !empty($content) ? $content : '<div class=" item">It Works</div><div class="testimonial item">It Works</div><div class="testimonial item">It Works</div>',
					'id' => !empty($id) ? $id : '',
					'class' => !empty($class) ? $class : "mg_demo",
					'items' => !empty($items) ? $items : "2"
				), $atts
			);		
		extract($atts);

	//	Generate Script Code
	$data   = "	<script>";
	$data  .= "	jQuery(document).ready(function() {	";
	$data  .= "			jQuery('.".$class. "').owlCarousel({	";
	$data  .= "				autoPlay: 3000, ";
	$data  .= "				items : " .$items. ",	";
	$data  .= "				itemsDesktop : [1199,2],	";
	$data  .= "				itemsDesktopSmall : [979,2],	";
	$data  .= "				pagination: false	";
	$data  .= "			});	";
	$data  .= "		});	";
	$data  .= "	</script>	";


    $data  .= "<div id='". $id ."'>";
	$data .= "<div class='". $class ." owl-carousel'>";
	
	//	Execute shortcode using [do_shortcode]
	$data  .= do_shortcode($content);
	$data  .= "</div></div>";
	
	return $data;
});

?>