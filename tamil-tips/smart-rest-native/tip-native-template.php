<?php

smartrest_do_json_native_tip();

function smartrest_do_json_native_tip() {
	
    $tipId = $_GET['id'];
    $tipIds = $_GET['ids'];

	if(isset($tipIds)) {
		// WP_Query arguments
	    $args = array ( 'post__in' => explode(",", $tipIds), 
					   'post_type' => 'any',
					    'post_status' => array('publish', 'future'));	
	} else {
		// WP_Query arguments
	    $args = array ( 'p' => $tipId, 
					   'post_type' => 'any',
					    'post_status' => array('publish', 'future'));	
	}

	// The Query
	$query = new WP_Query( $args );
	while($query->have_posts()) {
	   // Loop in here
	   $query->the_post();
	   $arrCatId = array ();	
	   foreach (get_the_category() as $tipCtgry) {
		$arrCatId[] = $tipCtgry->cat_ID;
	   }
		
		$images = get_attached_media('image');
		if(!empty($images)) {
		   reset($images);
	       $first_key = key($images);
		   $postimage = $images[$first_key]->guid;
			//echo $postimage;
	   }

		
	   $accesstime = get_post_time();
	   $result[] = array("kurippuId"=>get_the_ID(), "title"=>get_the_title(), "postDate"=>get_post_time(), "updatedDate"=>get_the_modified_time('U'), "category"=> $arrCatId[0], "status" => get_post_status(), "image" => $postimage, "content" => get_the_content() );
	}
	write_header();
    echo json_encode($result); 
}

//Send Header
function write_header() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header( 'Content-Type: application/json' );
}
			
?>		