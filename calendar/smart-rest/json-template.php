<?php

smartrest_do_json();

function smartrest_do_json() {

        $code = $_GET['cd'];
        $category = $_GET['ct'];   
	$id = $_GET['id'];   
        $test = $_GET['td'];   
 
	$args = array ( 'post_status' => 'publish', 'posts_per_page' => '100', 'orderby' => 'date', 'order' => 'ASC');

	if(isset($id)) {
	  $args['p'] = $id; 
	}

	if(isset($category)) {
	  $args['category_name'] = $category; 
	}

	if(isset($test)) {
	  $args['post_status'] = array( 'publish', 'draft'); 
	}

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
	   $accesstime = get_post_time();

	   //Collect Question type and choices
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
	    	/* echo $key . " => " . $value[0] . "<br />"; */
	   }

           if($id) {
		   $results[] = array("id"=>get_the_ID(), "title"=>get_the_title(), "post_date"=>get_post_time(), "status"=>get_post_status(), "code"=>$code, "priority"=>$priority, "type"=>$type, "category"=> $arrCatId[0],"content"=> get_the_content(), "thumb"=>  get_the_post_thumbnail(get_the_ID(), 'thumbnail' ), "icon" => $icon);
           } else {
		   $results[] = array("id"=>get_the_ID(), "title"=>get_the_title(), "post_date"=>get_post_time(), "status"=>get_post_status(), "code"=>$code, "priority"=>$priority, "type"=>$type, "category"=> $arrCatId[0], "thumb"=>  get_the_post_thumbnail(get_the_ID(), 'thumbnail' ), "icon" => $icon);
           }
	}
	//print_r($results);
	write_header();
 	//$response = array($results); 	
	echo json_encode($results); 

}

//Send Header
function write_header() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header( 'Content-Type: application/json' );
}
			
?>