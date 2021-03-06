<?php

smartrest_do_json();

function smartrest_do_json() {
	
	$lastAccessTime = $_GET['ts'];
        $category = $_GET['ct'];
        $once = $_GET['once']; 
        $idb = $_GET['idb']; 
        $tipId = $_GET['id'];
        $accesstime = time();
	$paged = $_GET['page'] ? $_GET['page'] : 1;
	$pagemode = $lastAccessTime ? false : true;

	// WP_Query arguments
	if(isset($lastAccessTime) && is_numeric($lastAccessTime)) {
		$since = date('Y-m-d h:i:s', $lastAccessTime);
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '500', 'orderby' => 'date', 'order' => 'ASC', 'date_query' => array(array('after' => date('Y-m-d h:i:s', $lastAccessTime))));
        } else if(isset($tipId)) {
		$args = array ( 'post_status' => 'publish', 'p' => $tipId);	
        } else if(isset($category)) {
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '500', 'orderby' => 'date', 
'order' => 'ASC', 'paged' => $paged, 'cat' => $category);	
        } else if(isset($idb)) {
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '500', 'orderby' => 'date', 'order' => 'ASC', 'paged' => $paged);
        } else if(isset($once)) {
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '3000', 'orderby' => 'date', 'order' => 'ASC', 'paged' => $paged, 'date_query' => array(array('after' => 'January 1st, 2016',)));
	} else {	
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '500', 'orderby' => 'date', 'order' => 'ASC', 'paged' => $paged, 'date_query' => array(array('after' => 'January 1st, 2016',)));	
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
	   $accesstime = get_post_time();
           if(isset($idb) || isset($once)) {	
	   	$results[] = array("id"=>get_the_ID(), "title"=>get_the_title(), "post_date"=>get_post_time(), "modified"=>get_the_modified_date(), "modified_time"=>get_the_modified_time(), "category"=> $arrCatId[0],"content"=> get_the_content());
	   } else {
	   	$results[] = array("id"=>get_the_ID(), "title"=>get_the_title(), "post_date"=>get_post_time(), "modified"=>get_the_modified_date(), "modified_time"=>get_the_modified_time(), "category"=> $arrCatId,"content"=> get_the_content(), "link"=> get_permalink());
	   }		
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