<?php
/**
 * Listening data (last.fm scrobbles via listening.davekellam.com).
 *
 * @package Quarter
 */

/**
 * Fetch the last {window_days} days of scrobbles, cached in a transient.
 */
function quarter_get_listening_data(): ?array {
	$data = get_transient( 'quarter_listening_data' );

	if ( is_array( $data ) && ! empty( $data['tracks'] ) ) {
		return $data;
	}

	$response = wp_remote_get(
		'https://listening.davekellam.com/recent',
		[
			'timeout'    => 20,
			'headers'    => [
				'Accept' => 'application/json',
			],
			'user-agent' => 'davekellam.com (Quarter theme)',
		]
	);

	if ( is_wp_error( $response ) ) {
		return null;
	}

	if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
		return null;
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( ! is_array( $data ) || empty( $data['tracks'] ) || ! is_array( $data['tracks'] ) ) {
		return null;
	}

	set_transient( 'quarter_listening_data', $data, 15 * MINUTE_IN_SECONDS );

	return $data;
}

/**
 * Aggregate tracks into a ranked list of plays.
 *
 * @param array  $tracks    Tracks from quarter_get_listening_data().
 * @param string $by        One of: artist, album, track.
 * @param int    $limit     Number of results to return.
 * @param bool   $by_artist Whether to group albums by artist + title rather than title alone.
 * @return array
 */
function quarter_listening_top( array $tracks, string $by, int $limit = 10, bool $by_artist = true ): array {
	$items = [];

	foreach ( $tracks as $track ) {
		$artist = trim( (string) ( $track['artist'] ?? '' ) );
		$name   = trim( (string) ( $track[ $by ] ?? '' ) );

		if ( '' === $name ) {
			continue;
		}

		$key = ( 'artist' === $by || $by_artist ) ? $artist . '|' . $name : $name;

		if ( ! isset( $items[ $key ] ) ) {
			$items[ $key ] = [
				'name'      => $name,
				'artist'    => 'artist' === $by ? '' : $artist,
				'thumbnail' => (string) ( $track['album_thumbnail'] ?? '' ),
				'count'     => 0,
				'_artists'  => [],
			];
		}

		++$items[ $key ]['count'];

		if ( '' !== $artist ) {
			$items[ $key ]['_artists'][ $artist ] = ( $items[ $key ]['_artists'][ $artist ] ?? 0 ) + 1;
		}
	}

	foreach ( $items as &$item ) {
		if ( $item['_artists'] ) {
			arsort( $item['_artists'] );
			$item['artist'] = (string) key( $item['_artists'] );
		}

		unset( $item['_artists'] );
	}
	unset( $item );

	usort(
		$items,
		static function ( $a, $b ) {
			if ( (int) $a['count'] === (int) $b['count'] ) {
				return strcasecmp( $a['name'], $b['name'] );
			}

			return (int) $b['count'] <=> (int) $a['count'];
		}
	);

	return array_slice( array_values( $items ), 0, $limit );
}

/**
 * Format an ISO 8601 timestamp in the site's local time and date format.
 *
 * @param string $iso    ISO 8601 timestamp.
 * @param string $format Optional PHP date format, defaults to the site's date format.
 * @return string
 */
function quarter_listening_date( string $iso, string $format = '' ): string {
	if ( ! strtotime( $iso ) ) {
		return '';
	}

	if ( '' === $format ) {
		$format = get_option( 'date_format' ) . ' \a\t g:ia';
	}

	return wp_date( $format, strtotime( $iso ) );
}
