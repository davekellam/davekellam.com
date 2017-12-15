<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		
		<title>Dave Kellam</title>

		<style>
			html { margin: 0; padding: 0; }
			body { width: 90%; max-width: 900px; margin: 12em auto 6em; font-family:  "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 1rem; line-height: 1.6em; text-align: left; }
			img { width: 256px; height: 256px; margin: 0; border-radius: 128px; margin-bottom: 30px; }
			a { text-decoration: none; color: firebrick; }`
			h2 { margin-top: 8em; }
			ul { margin: 0; padding: 0;}
			li { display: inline; list-style: none; }
			li:not(:last-child):after { content: " / "; color: grey; padding: .5em; }
			hr { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
		</style>
	</head>
	<body>
		<header>
			<img src="<?php echo get_template_directory_uri(); ?>/assets/photo_x.jpg">

			<h1>Dave Kellam</h1>
		</header>

		<section id="main">

			<p>I make things on the internet

			<hr>

			<ul>
				<li><a href="https://www.eightface.com">eightface.com</a></li>
				<li><a href="http://helveti.ca">helveti.ca</a></li>
				<li><a href="https://twitter.com/davekellam/">twitter</a></li>
				<li><a href="https://pinboard.in/u:davekellam">pinboard</a></li>
				<li><a href="https://www.flickr.com/photos/davekellam/">flickr</a></li>
				<li><a href="https://instagram.com/davekellam">instagram</a></li>
				<li><a href="https://last.fm/user/eightface/">last.fm</a></li>
				<li><a href="https://github.com/davekellam/">github</a></li>
				<li><a href="https://davekellam.tumblr.com">tumblr</a></li>
			</ul>
		</section>

		<?php wp_footer(); ?>

	</body>

	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-76978-15', 'davekellam.com');
		ga('send', 'pageview');

	</script>
</html>