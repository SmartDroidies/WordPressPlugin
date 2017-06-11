<?php

smartyrest_do_json();

function smartyrest_do_json() {

    $code = $_GET['cd'];
    $category = $_GET['ct'];   
	$id = $_GET['id'];   
    $test = $_GET['td']; 
    $lastAccessTime = $_GET['ts'];  
 
	$args = array ( 'post_status' => 'publish', 'posts_per_page' => '1000', 'orderby' => 'date', 'order' => 'DESC');

	if(isset($id)) {
	  $args['p'] = $id; 
	}

	if(isset($category)) {
	  $args['category_name'] = $category; 
	}

	if(isset($test)) {
	  $args['post_status'] = array( 'publish', 'draft'); 
	}

	if(isset($lastAccessTime) && is_numeric($lastAccessTime)) {
		$since = date('Y-m-d h:i:s', $lastAccessTime);
		$args['date_query'] = array(array('after' => date('Y-m-d h:i:s', $lastAccessTime))); 
	}

	$accesstime = time();

    //print_r($args);

    // The Query
	$query = new WP_Query($args);
	$results = array();
	while($query->have_posts()) {
	   // Loop in here
	   $query->the_post();
	
	   $arrCatId = array();	
	   foreach (get_the_category() as $tipCtgry) {
		$arrCatId[] = $tipCtgry->cat_ID;
	   }
	   //$accesstime = get_post_time();

	   //Collect Question type and choices
	   /*
	   $custom_fields = get_post_custom();
	   foreach ( $custom_fields as $key => $value ) {
		$key = ''; $icon = ''; $type = ''; $priority = '';
		if($key == 'code') {
		   $code =  $value[0];	
		} else if($key == 'priority') {
		   $priority =  $value[0];	
		} else if($key == 'type') {
		   $type =  $value[0];
		} else if($key == 'icon') {
		   $icon =  $value[0];
		}	
	    	// echo $key . " => " . $value[0] . "<br />"; 
	   }
	   */

	   	$results[] = array("id"=>get_the_ID(), "title"=>get_the_title(), "post_date"=>get_post_time(), "modified"=>get_the_modified_date(), "modified_time"=>get_the_modified_time(), "status"=>get_post_status(), "category"=> $arrCatId[0], "content"=> get_the_content());
	}
	write_header();
 	$response = array('time' => $accesstime, 'since' => $since, 'tips' => $results); 	
	echo json_encode($response); 
}

//Send Header
function write_header() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header( 'Content-Type: application/json' );
}
			
?>