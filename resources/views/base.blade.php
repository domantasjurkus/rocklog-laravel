<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
	<link rel="icon" href="favicon.ico">
	<meta id="document_root" content="{{ url('/') }}">
	
	<title>RockLog</title>

	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
	<link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>

<body class="grey darken-4">

	<main>
		<div style="background: url(img/bg_darken.jpg)" class="section no-pad-bot parallax-container background responsive-img" id="index-banner">
    		<div class="container">
    			<br><br>
    			<h1 class="header center">
    				<a class="red-text text-shadow text-accent-4" href="{{ url('/') }}">
    					RockLog
    				</a>
    			</h1>
    			<div class="row center">
    			<h5 class="header col s12 light white-text text-shadow">Naujausios dainos iš <a class="red-text" href="http://www.rock.lt">Classic Rock FM</a></h5>
    			</div>
    			<br><br>
    		</div>
    	</div>
	
		@section('content')
		@show
	</main>

	<footer class="page-footer black">
		<div class="container">
			<div class="row">
				<div class="col l6 s12"></div>
				<div class="col l4 offset-l2 s12"></div>
			</div>
		</div>
		
		<div class="footer-copyright">
			<div class="container">
				<a href="https://github.com/domantasjurkus" class="white-text" id="dj-link">Domantas Jurkus</a>
			</div>
		</div>
	</footer>

	<script src="js/jquery-2.1.1.min.js"></script>
	<script src="js/materialize.js"></script>
	<script src="js/index.js"></script>
	<script src="https://www.youtube.com/iframe_api"></script>

	</body>
</html>

