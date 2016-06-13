@extends('base')

@section('content')

	<div class="row grey darken-4" style="min-height:500px">
		<div class="col s12 m10 l5 offset-m1 offset-l1">
			<br/>
			<ul class="collapsible grey darken-4" data-collapsible="accordion">
				<ul class="collection">
				 	<li class="collection-item grey darken-3 grey-text">
				 		@if(is_null($fb_user))
							<p class="grey-text">&nbspPrisijunk su <a href="redirect">Facebook</a> ir išsaugok dainas!</p>
						@else
							<div class="fb-avatar" style="background-image: url('{{ $fb_user->getAvatar() }}');"></div>
							<p class="center-align">
								<a href="{{ $link }}">{{ $link_text }}</a>
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
							<a href="#!" class="secondary-content" id="{{ $song->song_id}} ">
								<i class="material-icons star-icon @if($song->saved) stared @endif">grade</i>
							</a>
						</div>
						<div class="collapsible-body grey darken-4">
							<div class="video-container" id="video-container"></div>
						</div>
					</li>
				@endforeach
				
				@if (empty($songs))
					<ul class="collection">
						<li class="collection-item grey darken-3 grey-text">
							<p class="grey-text">Nėra išsaugotų dainų</p>
						</li>
					</ul>
				@endif
				
            </ul>
		</div>
	</div>

	<div id="player"></div>
	
@endsection