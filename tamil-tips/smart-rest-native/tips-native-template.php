<?php

smartrest_do_json_native();

function smartrest_do_json_native() {
	
     $category = $_GET['ct'];
	 $type = $_GET['ty'];
 //    $idb = $_GET['idb']; 
 //    $tipId = $_GET['id'];
 //    $accesstime = time();
	// $paged = $_GET['page'] ? $_GET['page'] : 1;
	// $pagemode = $lastAccessTime ? false : true;

	// WP_Query arguments
    if(isset($category)) {
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '500', 'orderby' => 'modified', 
'order' => 'desc', 'cat' => $category);	
    } else if(isset($type) && $type == 'new') {
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '200', 'orderby' => 'modified', 
'order' => 'desc');	
    } else if(isset($type) && $type == 'draft') {
		$args = array ( 'post_status' => 'future', 'posts_per_page' => '200', 'orderby' => 'modified', 'order' => 'asc');	
	}
// 	if(isset($lastAccessTime) && is_numeric($lastAccessTime)) {
// 		$since = date('Y-m-d h:i:s', $lastAccessTime);
// 		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '500', 'orderby' => 'date', 'order' => 'ASC', 'date_query' => array(array('after' => date('Y-m-d h:i:s', $lastAccessTime))));
//         } else if(isset($tipId)) {
// 		$args = array ( 'post_status' => 'publish', 'p' => $tipId);	
//         } else  else if(isset($idb)) {
// 		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '500', 'orderby' => 'date', 'order' => 'ASC', 'paged' => $paged);
//         } else if(isset($once)) {
// 		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '3000', 'orderby' => 'date', 'order' => 'ASC', 'paged' => $paged, 'date_query' => array(array('after' => 'January 1st, 2016',)));
// 	} else {	
// 		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '500', 'orderby' => 'date', 'order' => 'ASC', 'paged' => $paged, 'date_query' => array(array('after' => 'January 1st, 2016',)));	
// 	}

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
		
		$images = get_attached_media('image');
		if(!empty($images)) {
		   reset($images);
	       $first_key = key($images);
		   //echo 'First Media Key ' . $first_key;
		   $postimage = $images[$first_key]->guid;
	    } else {
			$postimage = null;
		}

		
	   $accesstime = get_post_time();
	   $results[] = array("kurippuId"=>get_the_ID(), "title"=>get_the_title(), "postDate"=>get_post_time(), "updatedDate"=>get_the_modified_time('U'), "category"=> $arrCatId[0],"status" => get_post_status(), "image" => $postimage);
	}
	write_header();
 	//$response = array('time' => $accesstime, 'since' => $since, 'tips' => $results); 	
	echo json_encode($results); 
}

//Send Header
function write_header() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header( 'Content-Type: application/json' );
}
			
?>		