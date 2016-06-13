// Globals required for YouTube API
var player;
var done = false;

// Global function for YouTube API
/*function onYouTubeIframeAPIReady() {
    makeNewPlayer('vjqtHxuvwVg');
}*/

function makeNewPlayer(id) {
    player = new YT.Player('player', {
        height: '390',
        width: '640',
        videoId: id,
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        }
    });
}

function onPlayerReady(event) {
    event.target.playVideo();
}


function onPlayerStateChange(event) {
    /*if (event.data == YT.PlayerState.PLAYING && !done) {
     setTimeout(stopVideo, 6000);
     done = true;
     }*/
}

function stopVideo() {
    player.stopVideo();
}

(function($){

    // On document ready
    $(function(){
        
        var documentRoot = $("#document_root").attr("content");
        var row = $(".collapsible-header");

        // Attach onClick event for every row
        row.click(function(e) {
            var song = $(this).find(".song").html();
            var artist = $(this).find(".artist").html();
            var line = $(this).next();
            
            // If the bar is being closed
            if ($(this).hasClass("active")) {
                console.log("closing");
                try { player.pauseVideo(); }
                catch(e) { }

            // If the bar is being opened
            } else {

                // Make a request to this app for the video id
                $.ajax({
                    url: documentRoot+"/videoid",
                    data: {
                        "song": song,
                        "artist": artist
                    },
                    success: function(id) {
                        line.find(".video-container").append($("#player"));

                        // Destroy the previous player
                        try { player.destroy(); }
                        catch(e) { }

                        makeNewPlayer(id);
                        // $("#player").css("visibility", "visible");
                        // player.loadVideoById(id);
                    }
                });

            }

        });

        // On star click
        $(".star-icon").click(function(e) {
            e.stopPropagation();
            
            var icon = $(this);
            var song_id = $(this).parent().attr("id");

            // Save song
            $.ajax({
                url: documentRoot+"/save/"+song_id,
                success: function(data) {
                                                        
                }, error: function(data) {
                    icon.removeClass('stared');
                    // Gets triggered when tapping 2 stars fast
                    // Materialize.toast('Prisijunk su Facebook ir išsaugok dainas!', 2000);
                }
            });
            
            // Mark star yellow
            icon.hasClass('stared') ? icon.removeClass('stared') : icon.addClass('stared');
            
        });

        // For the collections
        $('.collapsible').collapsible({
            accordion: false
        });
        $('.parallax').parallax();

    });

})(jQuery);

function getID(root, artist, song) {

    $.ajax({
        url: root+"/videoid",
        data: {
            "song": song,
            "artist": artist
        },
        success: function(id) {
            return id;
        }
    });

}

function markStars() {
    
}