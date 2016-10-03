<?php
/**
 * Plugin Name: PDF Templator
 * Plugin URI: http://webcore.ro
 * Description: Tool to create templates to turn into pdfs
 * Version: 1.0
 * Author: Mihai Panait
 * Author URI: http://webcore.ro
 * License: All rights reserved??
 */



if(!class_exists('oap')) {require_once('class.oap.php'); }
require_once('class.base62.php');
require_once('class.wbc.metabox.php');
require_once('class.wbc.metabox.field.php');
require_once('class.eval.math.php');

if ( ! defined( 'TEMPLATOR__BASE_FILE' ) )
    define( 'TEMPLATOR__BASE_FILE', __FILE__ );
if ( ! defined( 'TEMPLATOR__BASE_DIR' ) )
    define( 'TEMPLATOR__BASE_DIR', dirname( TEMPLATOR__BASE_FILE ) );
if ( ! defined( 'TEMPLATOR__PLUGIN_URL' ) )
    define( 'TEMPLATOR__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
 
 
 // Register Custom Post Type
function templator_custom_post_type() {

	$labels = array(
		'name'                => _x( 'PDF Templates', 'Post Type General Name', 'webcore' ),
		'singular_name'       => _x( 'PDF Template', 'Post Type Singular Name', 'webcore' ),
		'menu_name'           => __( 'PDF Template', 'webcore' ),
		'parent_item_colon'   => __( 'Parent PDF Template:', 'webcore' ),
		'all_items'           => __( 'All Templates', 'webcore' ),
		'view_item'           => __( 'View Template', 'webcore' ),
		'add_new_item'        => __( 'Add New Template', 'webcore' ),
		'add_new'             => __( 'New Template', 'webcore' ),
		'edit_item'           => __( 'Edit Template', 'webcore' ),
		'update_item'         => __( 'Update Template', 'webcore' ),
		'search_items'        => __( 'Search Templates', 'webcore' ),
		'not_found'           => __( 'No Templates found', 'webcore' ),
		'not_found_in_trash'  => __( 'No Templates found in Trash', 'webcore' ),
	);
	$args = array(
		'label'               => __( 'Template', 'webcore' ),
		'description'         => __( 'Templates information pages', 'webcore' ),
		'labels'              => $labels,
		'supports'            => array( ),
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-media-document',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'pdftemplate', $args );
	
	$metabox = new metabox(
 	'rtm_steps',
	array(
	"title" => __( 'Extra styling'),
	"context"=>"normal",
	"priority"=>"high"
	),
	array(
		new TextArea(
			array(
			  'id'				=> 'extrastyling',
			  'name' 			=> 'Write here custom css for this pdf template<br />Ex: body {background:#ccc;}',
			  'desc' 			=> '',
			  'class' 			=> 'extrastyling'				
			)
		),
		new TextField(
			array(
			  'id'				=> 'wattermark',
			  'name' 			=> 'Write here the wattermark text',
			  'desc' 			=> '',
			  'class' 			=> 'wattermarktxt'				
			)
		),	
		new TextField(
			array(
			  'id'				=> 'dont',
			  'name' 			=> 'Don\'t apply wattermark on page(s)',
			  'desc' 			=> 'Comma separated ex 1,3,5',			  
			  'class' 			=> 'wattermarktxt'				
			)
		)
		
			
	),
	array('pdftemplate')
	);

}

// Hook into the 'init' action
add_action( 'init', 'templator_custom_post_type', 0 );

//load right template
add_filter( 'template_include', 'templator_template_chooser');

add_shortcode('math', 'string2math');

function string2math($atts) {
	$ma = str_replace("£","",$atts["do"]);
	$eval =  new EvalMath();
	$result = $eval->evaluate($ma);
	//mail("mihai@webcore.ro","math test","math: ".$ma."returl: ".$result);
	return number_format($result,2); 

}

function templator_template_chooser($template) {
	// Post ID
    $post_id = get_the_ID();
 
    // For all other CPT
    if ( get_post_type( $post_id ) != 'pdftemplate' ) {
        return $template;
    }
 
    // Else use custom template
    if ( is_single() ) {
        return templator_get_template_hierarchy( 'single' );
    }
}


function templator_get_template_hierarchy( $template ) {
 
    // Get the template slug
    $template_slug = rtrim( $template, '.php' );
    $template = $template_slug . '.php';
 
    // Check if a custom template exists in the theme folder, if not, load the plugin template file
    if ( $theme_file = locate_template( array( 'plugin_template/' . $template ) ) ) {
        $file = $theme_file;
    }
    else {
        $file = TEMPLATOR__BASE_DIR . '/templates/' . $template;
    }
 
    return apply_filters( 'rc_repl_template_' . $template, $file );
}
/*REMOVE SEO*/
function remove_yoast_metabox_reservations(){
    remove_meta_box('wpseo_meta', 'pdftemplate', 'normal');
}
add_action( 'add_meta_boxes', 'remove_yoast_metabox_reservations',11 );

function wp_editor_fontsize_filter( $options ) {
	array_shift( $options );
	array_unshift( $options, 'fontsizeselect');
	array_unshift( $options, 'formatselect');
	array_unshift( $options, 'fontselect');
	
	return $options;
}
add_filter('mce_buttons_3', 'wp_editor_fontsize_filter');

function templator_add_editor_styles($mce_css ) {  
	$mce_css .= ', ' . plugins_url( 'css/style.css', __FILE__ );
    return $mce_css;
}
add_filter( 'mce_css', 'templator_add_editor_styles' );



function templator_to_pdf($content) {
	error_reporting (0);
	global $post; 
	require_once('MPDF57/mpdf.php');  
	//fill in the blanks 
	$content = fill_in_the_blanks($content);
	$content =do_shortcode($content);
	//create pdf from template
	$mpdf=new mPDF();
	//$mpdf->debugfonts=true;
	$mpdf->debug=false;
	$extraCSS = get_post_meta( $post->ID, "rtm_steps", true );
	
	if($extraCSS['dont']!='') {
		$dont=explode(",",$extraCSS['dont']);
		
	}
	
	
	
	$ctnt = explode("===ENDPAGE===",$content);
	if(count($ctnt)==0) {
		$ctnt[0] = $content;
	}
	
	$i=0;
	foreach($ctnt as $index => $page) {
		
	$html = '<!DOCTYPE >
	<html><head xmlns="http://www.w3.org/1999/xhtml">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"></meta>
	<link  type="text/css" href="/wp-includes/css/editor.min.css"  rel="stylesheet" />
    <link href="/wp-content/plugins/pdf-templator/css/style.css"  rel="stylesheet" type="text/css" />
	<style> body { width:210mm; font-family:sans;  font-size:13px;} 
	'.$extraCSS['extrastyling'].'
	</style>
	
	</head><body >';
	if($extraCSS['wattermark']!="") {
		
		 $mpdf ->SetWatermarkText($extraCSS['wattermark']);
		if(isset($dont) && in_array($i,$dont)) {	
				
          $mpdf ->showWatermarkText = false;
		  $html.= wpautop($page);
	$html.="</body></html>";
	
	$mpdf->addPage();
	$mpdf->WriteHTML($html);		 
		} else {			
			
			 $mpdf ->showWatermarkText = true;
			 $html.= wpautop($page);;
	$html.="</body></html>";
	
	$mpdf->addPage();
	$mpdf->WriteHTML($html);
		}
		 
	} else {
		 $mpdf ->showWatermarkText = true;
			 $html.= wpautop($page);;
	$html.="</body></html>";
	
	$mpdf->addPage();
	$mpdf->WriteHTML($html);
	}
	
	$i++;
	}
	
	if($_REQUEST['uid']!='') {
		
		global $post;
		$uid = $_REQUEST['uid'];
		$field = ($_REQUEST['switch_field']==1)?"Postage Address URL":"Sales Pack URL";
		
		//generate unique filename based on templatename and oap ID
		$filename = $post->post_name.'-'. md5(Base62::convert($uid)).'.pdf';
		$uploads = wp_upload_dir();	
		
		//save pdf
		$mpdf->Output($uploads['basedir'].'/'.$filename,'F');
		
		//update oap
		$oap = new oap();
		echo $oap->update($uid,array('Mobile Recycling'=>array($field=>$uploads['baseurl'].'/'.$filename)));		
	
	} else {
		if ( current_user_can('moderate_comments') ) {
			$mpdf->Output();
		} else {
			header("HTTP/1.0 404 Not Found");		
		}
	}
	exit;
} 

function fill_in_the_blanks($content) {
	$data = $_REQUEST;
	
	foreach($data as $id=> $value) {
		$content = str_replace('{'.$id.'}',$value,$content);		
	}
	return $content;
}
 ?>