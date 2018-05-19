<?php
/**
 * Function to fetch data from the record collection repo
 * 
 * Will store data in a transient to avoid repeated requests.
 * Probably needs better error handling
 */
function dk_get_record_collection( $filename = 'albums' ) {

    $data   = '';
    $cache  = get_transient( 'dk_record_collection_' . $filename );

	if ( $cache ) {
		$data = $cache;
	} else {

		$request = wp_remote_get( 'https://raw.githubusercontent.com/davekellam/record-collection/master/' . $filename . '.md' );

		if ( is_wp_error ( $request ) ) {
            return $data->get_error_message();
        } else {
            $data = $request['body'];
            $transient_name = 'dk_record_collection_' . $filename;
			set_transient( $transient_name, $request['body'], 12 * HOUR_IN_SECONDS ); // maybe cache for 12 hours
		}
    }

    return $data;

}