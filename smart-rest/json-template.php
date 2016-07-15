<?php

smartrest_do_json($category);

function smartrest_do_json($category) {
	if (!isset($category)) {
		$category = "-5";
	}

	$lastAccessTime = $_GET['ts'];
        $tipId = $_GET['id'];

	// WP_Query arguments
	if(isset($lastAccessTime)) {
		//echo json_encode(date('Y-m-d h:i:s', $lastAccessTime));
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '5000', 'orderby' => 'modified', 'date_query' => array(array('after' => date('Y-m-d h:i:s', $lastAccessTime))));
    } else if(isset($tipId)) {
		$args = array ( 'post_status' => 'publish', 'p' => $tipId);	
	} else {	
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '5000', 'orderby' => 'modified');	
	}

	// The Query
	$query = new WP_Query( $args );
	$results = array();
	while($query->have_posts()) {
	   // Loop in here
	   $query->the_post();
	   $arrCatId = array ();	
	   foreach (get_the_category() as $tipCtgry) {
			$arrCatId[] = $tipCtgry->cat_ID;
	   }
	   $results[] = array("id"=>get_the_ID(), "title"=>get_the_title(), "post_date"=>get_post_time(), "modified"=>get_the_modified_date(), "modified_time"=>get_the_modified_time(), "category"=> $arrCatId,"content"=> get_the_content(), "link"=> get_permalink());
	}
	write_header();
	$response = array('time' => time(), 'quotes' => $results); 	
	echo json_encode($response); 
}

//Send Header
function write_header() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header( 'Content-Type: application/json' );
}
			
?>