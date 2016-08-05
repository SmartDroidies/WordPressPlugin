<?php

smartrest_do_json();

function smartrest_do_json() {

	$lastAccessTime = $_GET['ts'];
        $tipId = $_GET['id'];
        $category = $_GET['cat'] ? $_GET['cat'] : "-5"; 
        $accesstime = time();
	$paged = $_GET['page'] ? $_GET['page'] : 1;
	$pagemode = $lastAccessTime ? false : true;

        
	// WP_Query arguments
	if(isset($lastAccessTime) && is_numeric($lastAccessTime)) {
		$since = date('Y-m-d h:i:s', $lastAccessTime);
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '1000', 'orderby' => 'date', 'order' => 'ASC', 'date_query' => array(array('after' => date('Y-m-d h:i:s', $lastAccessTime))));
        } else if(isset($tipId)) {
		$args = array ( 'post_status' => 'publish', 'p' => $tipId);	
	} else {	
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '1000', 'orderby' => 'date', 'order' => 'ASC', 'paged' => $paged);	
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
	   $results[] = array("id"=>get_the_ID(), "title"=>get_the_title(), "post_date"=>get_post_time(), "modified"=>get_the_modified_date(),"modified_time"=>get_the_modified_time(), "cat_ID"=> $catId,"cat_slug"=> $catSlug,"content"=> get_the_content(), "link"=> get_permalink());
	}
	write_header();
	$response = array('time' => $accesstime, 'since' => $since, 'page' => $paged, 'pagemode' => $pagemode, 'tips' => $results); 	
	echo json_encode($response); 
}

//Send Header
function write_header() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header( 'Content-Type: application/json' );
}
			
?>