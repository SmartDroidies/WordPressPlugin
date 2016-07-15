<?php

smartrest_do_json();

function smartrest_do_json() {
       $type = $_GET['t'];
       if(isset($type)) {
         echo "Get data for - " . $type;  		 
       } else {
          collect_questions();
       }	
}

function collect_questions() {

	//Params for Query
	$args = array ( 'post_status' => 'publish', 'posts_per_page' => '5', 'orderby' => 'rand');	

	// The Query
	$query = new WP_Query( $args );
	$results = array();
	while($query->have_posts()) {
	   // Loop in here
	   $query->the_post();

	   //Collect Category 
           $categories = get_the_category();
	   $category =  $categories[0]->name;	
	
	   //Collect Question type and choices
	   $custom_fields = get_post_custom();
	   foreach ( $custom_fields as $key => $value ) {
		if($key == 'Priority') {
		   $priority =  $value[0];	
		   /* echo "Type : " . $key . " => " . $value[0] . "<br>/"; */	
		} else if($key == 'Answer') {
		   $answer =  $value[0];	
		} else if($key == 'Clue') {
		   $clue =  $value[0];
		}	
	    	//echo $key . " => " . $value[0] . "<br />"; 
	   }

	   $results[] = array("id"=>get_the_ID(), "category"=> $category,"content"=> get_the_content(), "priority"=> $priority, "answer" => $answer, "clue" => $clue);
	}
	write_header();
	$response = array('vidugadhaigal' => $results); 	
	echo json_encode($response); 
}

//Send Header
function write_header() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header( 'Content-Type: application/json' );
}
			
?>