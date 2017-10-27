<?php
/*
Plugin Name: Notice Box LogHQ
Plugin URI: http://wp.login.plus/
Description: Simple Light Weight "Notice Box by LogHQ" is less than 5 Kilo Bytes and it does not requires any configuration. It can be used to display 4 types of notice boxes: 1. Notice Box, 2. Warning Box, 3. Success Box, 4. Error Box. The Notice Box can be embbed within your content with easy to use shortcode. Use following shortcode:  [noticebox type="error" msg="Error not functioning"]   [noticebox type="success" txt=" Yeah hurray nice"] [noticebox type="warning" text=" Becareful !! "]  [noticebox type="notice" message=" Notice"]
Author: wp.Login.plus
Author URI: http://wp.login.plus/
Version: 1.0.1
Text Domain: noticebox_loghq
*/

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/* Enable shortcodes in widget area */
add_filter('widget_text', 'do_shortcode');


// add css in footer
add_action( 'wp_footer','noticebox_css');

function noticebox_css(){
	
		$boxcss = "
<style type='text/css'>
.noticebox {
	color:#555;
	border-radius:10px;
	font-family:Tahoma,Geneva,Arial,sans-serif;font-size:11px;
	padding:10px 36px;
	margin:10px;
}
.noticebox span {
	font-weight:bold;
	text-transform:uppercase;
	display:block;
}
.error {
	background:#ffecec url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACj0lEQVR4XqVTW08TURCe0253AySilEu1BjHYcNEYoxYLbdpCKBC5NJRY1PCAISak8S8Y/wYvPBkjkcTICzE+mJh4A3xUtksbCWp3aWEp3YW2hKXj2dYWQd/4kpMz+81+38zO2UMQEU4C5jgRJYTjOJgAhrlntFrrKFV5kEjwQMiMQTPOWhVF/ltwpIOfFYzfUFszVdY/ZDFdvgKE5QoJTYN9gYfMm9cyJBX/uVj8Q1FjKAY/qsqdTGvLC9Lbb1GVXcilUpCTNyG3JUNOVUBVdgC93WZjk+2taKv3HzGInT9lZixn5w4am1jG5YW6x09g49MCaDIVJ7dg4+NnMIceAevoAK2+gSUWy1TUZiu0h4jwy1o7GR/owWifDzOCgDoykQgKvT6MUE79slTgaO67fwATY0GM3bgaolTBINZ88V1ieADjgUEU3C7MrKygjmw0gupSURzGcJcH4+P3MfFgDEW34/2hQUujFLvWiqKnHcUeD/LtbZiNrGARmTCPvLMdJVpkfagPpW43il2uZV1bGCLHJcFgANzdBaTDQ1UFbXsbitCL4N4eHawCuXQakL4LLHvm8BQ4VqYm+g6prAYXnj6Dipt2oJXpCkNZcws0UC6VzuhCIBynH7FQcpc8t0KitwMF+3VMf/uKOtI8j8sOO/KONj0ucSseJ0ojQygGhydLn7BTXjVNHUUTYwR1cTFfdW1iHE7XVkOlpQbWHk4AHSLNLYBJ74BhNnP7MFvqQF/rg71+yX87u+p10SouFGkVKTiM66OB/B7p9OCqr1N/zsaDAWdRd+QyiaMjTiOBVzSshv9DBMBQ3czLueN3oYRYIGA2meAOGshdQGiiVBIJyDR+vqNkpi/Nz+/9c5lOgt8n8IvUM0QilAAAAABJRU5ErkJggg==') no-repeat 10px 10px;
	border:1px solid #f5aca6;
}
.success {
	background:#e9ffd9 url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAmJJREFUeNqkk0toE0Ech3+T3aRJX7RpPNgSgzQljYXiC1FbUcFrL9WTqAe96NGce+hF8KA5eVHsSaQni1CR4kHEFwoVxNrW0iJtA9lqk1TJbnZ2d3bGnbWPDT124Fvm9f32v+wMEUJgL02VD/IkASjEQw5IJwiGvd6AR3JzX8HjAwQmIEQRrjdyBcTV0v+AQBuKqpFcpiuTTiWS8eaG5qisz7D0I8vrK4MLxcWLlmPlvanJugq25NaGltFzfWezKpQYsxl0W99aa0x3dDcm25Mdb+fejVZNf94PCW1u6GwIRXJnegeyds2K6boOSmkdz3oeg5lO7GT6RDZCwjnp7AQwMdyzvztNdRozDAOmadZxt3vE3zZ1eNwLYbFUPJmWTjDgdKIpEa9Wq7Asy0dWsfZ7DTejV9BWbkKhUMC1l7cwOzcLTnlcOsGAAwqUqOu6+Hx+ClpZw8qvFaRIF061H4eqqhhbfooXpVdwQg6oTaPSCQaAuQw3Dl7GzMwMpg6N42iiHw/77/ny69J7PCiOATH4MJX5zk6AI1ZLxjod+XYHiqIgHA7jUe99hNUwFms/cXt5BLyZe/8CPjaxqHSCFXxcW9cqSlzB4I8h/61bXFq8DrRhW5bQaq0inWDAxJ/V8lIIxCRdBMe+X/DlvulBYF+9zLlrWpq5JJ2dAC6KrsHy5U/avGDcJCmCvq+enML2d0u4w0x9ujLPa25eOvUnkYtJpln4+1zLRbJN6UimMa6oalQuuRuM2gu1ij1vLHFH5NGqeKeQ7DrKfggvsS/0zcawx+7LpJAJtCjFoEL2ep3/CTAAj+gy+4Yc2yMAAAAASUVORK5CYII=') no-repeat 10px 10px;
	border:1px solid #a6ca8a;
}
.warning {
	background:#fff8c4 url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABqklEQVR4XqWTvWsUURTFf+/tx7DmA5sUmyB+EGQDCkFRxCFosYWCFgELm2ApCBYW/gOCFpYSrUMsBIv4BwTSCSqaWgsTEDRV2EVBZWffvXIYwhZOEdgLhzmcc+7h3WKCuzPOhI+P80rDzE7WwmAHIHnzVIxxl4qJVaKbkYrBxvyVZQRxaYcq0EmehvePzp5YnD67hCAuzd0PUWB2JNQazzo377D7+auAuDR51QWjZWxYvD2e34DsJw+fbwviSJOnTHWBO5aGt6fa84szF67CzguCIYgjTZ4yuP9fYGqO2avO8j348hSKff4OkiAuDXnKKDsqGD1989jSLWJvA/58g+YUv34Xgrg0eSij7MEpsXx66k62O932wjT030NjAuotXj/YE8SlyUMZZbWj3ejmEFubp69fg711yCYha0GWcXftjCAuTZ4yKKsd7dbNfHXuUk6jeAPNCSBCAJpGb78PiGel7gCmLHMXc76/21oNn57kfm5lFg0W0KBPDag7GoYBEuCUE0uy/fIH4cOjy27J0SlI56DEiSVFFi4dEUUIMRBrQZTzjDFj/87/ACmm3+QFX8sKAAAAAElFTkSuQmCC') no-repeat 10px 10px;
	border:1px solid #f2c779;
}
.notice {
	background:#e3f7fc url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAwFBMVEX///8AVq0CYcADbNEDaMoDa88Das0EcdkFfe0CZMPv9fu/1esFe+kBXbkEdd8Uf+QEb9VRkdAGf/AFeecRacAEc9wBW7QBX7zs6dwFeudRjswGgvUFd+MCZscDbtTl4tIBWbFEo/rh3s7A2/QAVasCZcYEcNcBYL3n5NUFfOsWhe5UqPVCkNvj4NDd2swyhNMGgPIBXLbq59nB4Pw1m/lToOgCY8RBi9Lv9/8FduHm49QxfMfo5dcQYrNTnuVBhMhJU/nRAAAAAXRSTlMAQObYZgAAAMpJREFUeF4lzdVyRTEIQFGI57i7XXet+///VZN2vy0YBrA9HPZeF34v4L/XWXtVdRdIenT+/NheE2W8puxiJ7O2TZSq7jLiTMfm3u53YVXhIHriu3BIEuXVdYBI2Yr4Dex3npd22893KnpdFl9Qp2n3FgTbEZkm/oSQGuVSjiOagwJ9CPNcrodhPCEpb9MygycZDZTz0xyNsYhhkXMhGJuf0XhJXIBj1K/02YTGDQA4F042G7SRDwfs5EVo828qn3+sbW6cEZI1rsUvrDkTPAFMyQwAAAAASUVORK5CYII=') no-repeat 10px 10px;
	border:1px solid #8ed9f6;
}

</style>
		";
		
		echo $boxcss;
}


// Create Shortcode noticebox
//  Use the shortcode:
//  [noticebox type="error" msg="Error not functioning"]
//  [noticebox type="success" txt=" Yeah hurray nice"]
//  [noticebox type="warning" text=" Becareful !! "]
//  [noticebox type="notice" message=" Notice"]

function create_noticebox_shortcode($atts) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'msg' 		=> '',
			'txt' 		=> '',
			'text' 		=> '',
			'message' 	=> '',
			'type' 		=> '',
			'class'		=> '',
		),
		$atts,
		'noticebox'
	);
	// message 
	$msg = $atts['msg'];
	$txt = $atts['txt'];
	$text = $atts['text'];
	$message = $atts['message'];
	
	// type
	$type = $atts['type'];
	// class name
	$class = $atts['class'];

	// OUTPUT
	$htmlbox = '<div class="noticebox '. $type .' '. $class. ' "><span>'. $type .': </span>'. $msg . $txt . $text . $message. '</div>';
	
	return $htmlbox;
}
add_shortcode( 'noticebox', 'create_noticebox_shortcode' );





