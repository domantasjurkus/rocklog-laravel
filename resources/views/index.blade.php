<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
	<link rel="icon" href="favicon.ico">
	<meta id="document_root" content="{{ url('/') }}">
	<title>RockLog</title>

	<!-- CSS  -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
	<link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>

<body class="black">

	<div style="background: url(img/bg_darken.jpg)" class="section no-pad-bot parallax-container background" id="index-banner">
		<div class="container">
			<br><br>
			<h1 class="header center red-text text-shadow text-accent-4">RockLog</h1>
			<div class="row center">
			<h5 class="header col s12 light white-text text-shadow">Naujausios dainos iš <a class="red-text" href="http://www.rock.lt">Classic Rock FM</a></h5>
			</div>
			<br><br>
		</div>
	</div>

	<div class="row grey darken-4">
		<div class="col s12 m10 l5 offset-m1 offset-l1">
			
			<br/>
			<ul class="collapsible grey darken-4" data-collapsible="accordion">
			
				<ul class="collection">
				
				 	<li class="collection-item grey darken-3 grey-text">
				 		@if(is_null($fb_user))
							<p class="grey-text">&nbspIšsaugok dainas - Prisijunk su <a href="redirect">Facebook!</a></p>
						@else
							<div class="fb-avatar" 
							style="background-image: url('{{ $fb_user->getAvatar() }}');">
							</div>
						
							<p class="center-align">{{ $fb_user->getName() }}
								<a href="logout" class="secondary-content">
									<i class="material-icons grey-text logout-icon">input</i>
								</a>
							</p>
							
						@endif
				 	</li>
				</ul>
				<br/>
			
				@foreach($songs as $song)
					<li>
						<div class="song collapsible-header red darken-4 white-text">
							<b class="artist">{{ $song->artist  }}</b> - <span class="song">{{ $song->song }}</span>
							<!--<a href="#!" class="secondary-content"><i class="material-icons grey-text">grade</i></a>-->
						</div>
						<div class="collapsible-body grey darken-4">
							<div class="video-container" id="video-container"></div>
						</div>
					</li>
				@endforeach
				
            </ul>
		</div>
	</div>

	<div id="player"></div>

	<footer class="page-footer black">
		<div class="footer-copyright">
			<div class="container">
				<a href="https://github.com/domantasjurkus" class="white-text" id="dj-link">Domantas Jurkus</a>
			</div>
		</div>
	</footer>

	<!--  Scripts-->
	<script src="js/jquery-2.1.1.min.js"></script>
	<script src="js/materialize.js"></script>
	<script src="js/index.js"></script>
	<script src="https://www.youtube.com/iframe_api"></script>

	</body>
</html>

