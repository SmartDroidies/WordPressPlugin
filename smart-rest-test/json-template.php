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

	   /*	
	   $arrCatId = array ();	
	   foreach (get_the_category() as $tipCtgry) {
		echo "Category -> " . $tipCtgry->name; 
		$arrCatId[] = $tipCtgry->cat_ID;
	   }
	   */

	   //Collect Category 
           $categories = get_the_category();
	   $category =  $categories[0]->name;	
	
	   //Collect Question type and choices
	   $custom_fields = get_post_custom();
	   foreach ( $custom_fields as $key => $value ) {
		if($key == 'type') {
		   $quest_type =  $value[0];	
		   /* echo "Type : " . $key . " => " . $value[0] . "<br>/"; */	
		} else if($key == 'choices') {
		   $quest_choices =  $value[0];	
		} else if($key == 'choice') {
		   $quest_choice =  $value[0];
		}	
	    	/* echo $key . " => " . $value[0] . "<br />"; */
	   }

	   $results[] = array("id"=>get_the_ID(), "title"=>get_the_title(), "category"=> $category,"content"=> get_the_content(), "type"=> $quest_type, "choices" => $quest_choices, "choice" => $quest_choice);
	}
	write_header();
	$response = array('questions' => $results); 	
	echo json_encode($response); 
}

//Send Header
function write_header() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header( 'Content-Type: application/json' );
}
			
?>