<?php
/**
 * Template Name: Listening
 *
 * Recent last.fm scrobbles, fetched from listening.davekellam.com.
 *
 * @package Quarter
 */

get_header();
the_post();
?>

<main class="site-main" id="main">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>

		<div class="entry-content">
			<?php the_content(); ?>

			<?php $listening = quarter_get_listening_data(); ?>

			<?php if ( null === $listening ) : ?>

				<p><?php esc_html_e( 'Could not load listening data right now. Check back in a bit.', 'quarter' ); ?></p>

			<?php else : ?>

				<p class="listening-summary">
					<?php
					printf(
						/* translators: 1: number of scrobbles, 2: number of days, 3: time data was last updated */
						esc_html__( '%1$d scrobbles over the last %2$d days', 'quarter' ),
						(int) $listening['total_tracks'],
						(int) $listening['window_days']
					);
					?>
					(<a href="https://last.fm/user/eightface">eightface</a>)
				</p>

				<section class="listening-section" aria-labelledby="listening-recent-title">
					<h2 class="listening-title" id="listening-recent-title"><?php esc_html_e( 'Recently played tracks', 'quarter' ); ?></h2>

					<ol class="listening-tracks">
						<?php foreach ( array_slice( $listening['tracks'], 0, 20 ) as $quarter_track ) : ?>

							<li class="listening-track">
								<?php if ( ! empty( $quarter_track['album_thumbnail'] ) ) : ?>
									<img class="listening-cover" src="<?php echo esc_url( $quarter_track['album_thumbnail'] ); ?>" alt="" loading="lazy" width="64" height="64">
								<?php endif; ?>

								<div class="listening-track-copy">
									<?php if ( ! empty( $quarter_track['url'] ) ) : ?>
										<a class="listening-track-name" href="<?php echo esc_url( $quarter_track['url'] ); ?>"><?php echo esc_html( $quarter_track['track'] ); ?></a>
									<?php else : ?>
										<span class="listening-track-name"><?php echo esc_html( $quarter_track['track'] ); ?></span>
									<?php endif; ?>

									<span class="listening-track-artist">
										<?php
										echo esc_html( $quarter_track['artist'] );
										if ( ! empty( $quarter_track['album'] ) ) {
											echo ' &mdash; ' . esc_html( $quarter_track['album'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										}
										?>
									</span>
								</div>

								<time class="listening-track-time" datetime="<?php echo esc_attr( $quarter_track['played_at'] ); ?>">
									<span class="listening-track-date"><?php echo esc_html( quarter_listening_date( $quarter_track['played_at'], 'M j' ) ); ?></span>
									<span class="listening-track-clock"><?php echo esc_html( quarter_listening_date( $quarter_track['played_at'], 'g:ia' ) ); ?></span>
								</time>
							</li>

						<?php endforeach; ?>
					</ol>
				</section>

				<section class="listening-section" aria-labelledby="listening-artists-title">
					<h2 class="listening-title" id="listening-artists-title"><?php esc_html_e( 'Top artists', 'quarter' ); ?></h2>

					<ol class="listening-ranked">
						<?php foreach ( quarter_listening_top( $listening['tracks'], 'artist', 10 ) as $quarter_item ) : ?>

							<li>
								<span class="listening-ranked-name"><?php echo esc_html( $quarter_item['name'] ); ?></span>
								<span class="listening-ranked-count"><?php echo esc_html( $quarter_item['count'] ); ?></span>
							</li>

						<?php endforeach; ?>
					</ol>
				</section>

				<section class="listening-section" aria-labelledby="listening-albums-title">
					<h2 class="listening-title" id="listening-albums-title"><?php esc_html_e( 'Top albums', 'quarter' ); ?></h2>

					<ul class="listening-albums">
						<?php foreach ( quarter_listening_top( $listening['tracks'], 'album', 12, false ) as $quarter_item ) : ?>

							<li class="listening-album">
								<?php if ( ! empty( $quarter_item['thumbnail'] ) ) : ?>
									<img class="listening-cover" src="<?php echo esc_url( $quarter_item['thumbnail'] ); ?>" alt="" loading="lazy" width="64" height="64">
								<?php endif; ?>

								<span class="listening-album-copy">
									<span class="listening-album-name"><?php echo esc_html( $quarter_item['name'] ); ?></span>
									<span class="listening-album-artist"><?php echo esc_html( $quarter_item['artist'] ); ?></span>
								</span>

								<span class="listening-ranked-count"><?php echo esc_html( $quarter_item['count'] ); ?></span>
							</li>

						<?php endforeach; ?>
					</ul>
				</section>

				<section class="listening-section" aria-labelledby="listening-tracks-title">
					<h2 class="listening-title" id="listening-tracks-title"><?php esc_html_e( 'Top tracks', 'quarter' ); ?></h2>

					<ol class="listening-ranked">
						<?php foreach ( quarter_listening_top( $listening['tracks'], 'track', 10 ) as $quarter_item ) : ?>

							<li>
								<span class="listening-ranked-name"><?php echo esc_html( $quarter_item['name'] ); ?></span>
								<span class="listening-ranked-artist"><?php echo esc_html( $quarter_item['artist'] ); ?></span>
								<span class="listening-ranked-count"><?php echo esc_html( $quarter_item['count'] ); ?></span>
							</li>

						<?php endforeach; ?>
					</ol>
				</section>

			<?php endif; ?>
		</div>
	</article>
</main>

<?php
get_footer();