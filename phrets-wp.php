add_shortcode( 'phrets_get_content', function () {
	$request = wp_remote_get( 'http://ronniearchitectmarketing.com/fl/phrets/get.php' );
	$out="";
	if( is_wp_error( $request ) ) {
		$out .= "Error";
		return false; // Bail early
	}

	$body = wp_remote_retrieve_body( $request );
	
	$data = json_decode( $body );
	
	if( ! empty( $data ) ) {
		$post_arr = array(
			'post_title'   => $data->address,
			'post_content' => $data->public_remarks,
			'post_status'  => 'publish',
			'post_author'  => get_current_user_id(),
			'post_type'		=> 'property',
			'tax_input'    => array(
				'property-type'     => $data->property_type,
			),
			'meta_input'   => array(
				'REAL_HOMES_property_address' 			=> $data->address . ", " . $data->city . ", ". $data->postal,
				'REAL_HOMES_property_id' 				=> $data->ListingKeyNumeric,
				'REAL_HOMES_property_bedrooms' 			=> $data->bedroom,
				'REAL_HOMES_property_bathrooms' 		=> $data->bathroom,
				'inspiry_basement' 						=> $data->basement,
				'inspiry_heating' 						=> $data->heating,
				'inspiry_cooling' 						=> $data->cooling,
				'inspiry_fireplace' 					=> $data->fireplace,
				'inspiry_stories' 						=> $data->stories,
				'inspiry_zoning' 						=> $data->zoning,
				'REAL_HOMES_property_price' 			=> $data->price,
				'REAL_HOMES_tour_video_url' 			=> $data->virtual_tour,
				'inspiry_transaction_type' 				=> $data->transaction_type,
				'inspiry_property_sub_type' 			=> $data->property_sub_type,
				'inspiry_lease'							=> $data->lease,
				'inspiry_age'							=> $data->age,
				'inspiry_parking_type'					=> $data->parking_type,
				'REAL_HOMES_property_garage'			=> $data->parking_space,
				'inspiry_pool_feature'					=> $data->pool_feature
			),
		);
		// Insert the post into the database.
		wp_insert_post( $post_arr );
		return $data->ListingKeyNumeric;
		
	}

	//$out . '<p>write your HTML shortcode content here</p>';

	//return $out;
} );
