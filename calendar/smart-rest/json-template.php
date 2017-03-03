<?php

smartrest_do_json();

function smartrest_do_json() {

        $code = $_GET['cd'];
        $category = $_GET['ct'];   
        $test = $_GET['td'];   
 
	$args = array ( 'post_status' => 'publish', 'posts_per_page' => '100', 'orderby' => 'date', 'order' => 'ASC');

    	//print_r($args);

	if(isset($category)) {
	  $args['category_name'] = $category; 
	}

	if(isset($test)) {
	  $args['post_status'] = array( 'publish', 'draft'); 
	}

	//print_r($args);
        /*
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
	} else {	
		$args = array ( 'post_status' => 'publish', 'posts_per_page' => '500', 'orderby' => 'date', 'order' => 'ASC', 'paged' => $paged, 'date_query' => array(array('after' => 'August 1st, 2015',)));	
	}
        */

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
	   $accesstime = get_post_time();

	   //Collect Question type and choices
	   $custom_fields = get_post_custom();
	   foreach ( $custom_fields as $key => $value ) {
		if($key == 'code') {
		   $code =  $value[0];	
		} else if($key == 'priority') {
		   $priority =  $value[0];	
		} else if($key == 'type') {
		   $type =  $value[0];
		}	
		
	    	/* echo $key . " => " . $value[0] . "<br />"; */
	   }

	   $results[] = array("id"=>get_the_ID(), "title"=>get_the_title(), "post_date"=>get_post_time(), "code"=>$code, "priority"=>$priority, "type"=>$type, "category"=> $arrCatId[0],"content"=> get_the_content());
	}
	//print_r($results);
	write_header();
 	//$response = array($results); 	
	echo json_encode($results); 
         

	/*
	$lastAccessTime = $_GET['ts'];
        $type = $_GET['type'];
        $date = $_GET['date'];

	if($type == 'raasi') {
		//echo("Collect Raasi Details for : " . $date);	
	      	$args = array ( 'post_status' => 'publish', 'name' => $date);	
                //$args = array ( 'post_status' => 'publish');	
		// The Query
		$query = new WP_Query( $args );
                $query->the_post();
		write_header();
		echo json_encode(get_the_content()); 
        }
        */
}

//Send Header
function write_header() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header( 'Content-Type: application/json' );
}
			
?>