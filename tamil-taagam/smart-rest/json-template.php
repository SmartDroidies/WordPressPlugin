<?php

smartrest_do_json();

function smartrest_do_json() {

	$lastAccessTime = $_GET['ts'];
    $tipId = $_GET['id'];
	$test = $_GET['test'];
        
	// WP_Query arguments
	if(isset($test)) {
		$args = array ( 'post_status' => 'draft', 'posts_per_page' => '50', 'orderby' => 'modified');
	} else if(isset($lastAccessTime)) {
		//echo json_encode(date('Y-m-d h:i:s', $lastAccessTime));
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '500', 'orderby' => 'modified', 'date_query' => array(array('after' => date('Y-m-d h:i:s', $lastAccessTime))));
    } else if(isset($tipId)) {
		$args = array ( 'p' => $tipId, 'posts_per_page' => '5', 'orderby' => 'modified');	
	} else {	
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '500', 'orderby' => 'modified');	
	}

	// The Query
	$query = new WP_Query( $args );
	$results = array();
	while($query->have_posts()) {
	   // Loop in here
	   $query->the_post();
	   $tipCtgry = get_the_category();
	   $catId = $tipCtgry[0]->cat_ID;
    	   $catSlug = $tipCtgry[0]->slug;
	   $results[get_the_ID()] = array("id"=>get_the_ID(), "title"=>get_the_title(), "post_date"=>get_post_time(), "modified"=>get_the_modified_date(),"modified_time"=>get_the_modified_time(), "cat_ID"=> $catId,"cat_slug"=> $catSlug,"content"=> get_the_content(), "link"=> get_permalink());
	}
	write_header();
	$response = array('time' => time(), 'thuligal' => $results); 	
	echo json_encode($response); 
}

//Send Header
function write_header() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header( 'Content-Type: application/json' );
}
			
?>