<?php
function hb_single_audio( $atts ) {

    extract( shortcode_atts( array(

        'aid' => ''

    ), $atts ) );



    global $wpdb;

    $audio = hb_db_get_audio( $aid );



    $output = '';

    if( !empty( $audio ) ) {

        $output .= '<script>

                        //<![CDATA[

                        jQuery(document).ready(function(){

                            jQuery("#jquery_jplayer_s' . $aid . '").jPlayer({

                                ready: function () {

                                    jQuery(this).jPlayer("setMedia", {

                                        mp3:"' . $audio['audioURL'] . '"

                                    });

                                },

                                swfPath: "http://www.jplayer.org/latest/js/Jplayer.swf",

                                supplied: "mp3",

                                wmode: "window",

                                smoothPlayBar: true,

                                keyEnabled: true,

                                cssSelectorAncestor: "#jp_container_s' . $aid . '"

                            });

                        });

                        //]]>

                    </script>';



        $output .= '<div id="jquery_jplayer_s' . $aid . '" class="jp-jplayer"></div>';

        $output .= '<div id="jp_container_s' . $aid . '" class="jp-audio hb-single">';

        $output .=      '<div class="jp-type-single">';

        $output .=          '<div class="jp-gui jp-interface">';

        $output .=              '<ul class="jp-controls">';

       $output .=                  '<li class="jp-play"><a href="javascript:;" tabindex="1"><img src=' . AG_URLPATH . 'images/play.png></a></li>';
        $output .=                  '<li class="jp-pause"><a href="javascript:;" tabindex="1"><img src=' . AG_URLPATH . 'images/pause.png></a></li>';
        $output .=                  '<li class="jp-stop"><a href="javascript:;" tabindex="1"><img src=' . AG_URLPATH . 'images/stop.png></a></li>';
        $output .=                  '<li class="jp-mute"><a href="javascript:;" tabindex="1"><img src=' . AG_URLPATH . 'images/unmute.png></a></li>';
        $output .=                  '<li class="jp-unmute"><a href="javascript:;" tabindex="1"><img src=' . AG_URLPATH . 'images/mute.png></a></li>';
		
		 $output .=                  '<li class="hb-volume_bar"><div class="jp-volume-bar"><div class="jp-volume-bar-value"></div></div></li>';
		
        $output .=                  '<li class="jp-volume-max"><a href="javascript:;" tabindex="1"><img src=' . AG_URLPATH . 'images/maxvol.png></a></li>';
        $output .=              '</ul>';

        $output .=              '<div class="jp-progress">';

        $output .=                  '<div class="jp-seek-bar">';

        $output .=                      '<div class="jp-play-bar"></div>';

        $output .=                  '</div>';

        $output .=              '</div>';

       
        $output .=              '<div class="jp-time-holder">';

        $output .=                  '<div class="jp-current-time"></div>';

        $output .=                  '<div class="jp-duration"></div>';

        $output .=                  '<ul class="jp-toggles">';

        $output .=                      '<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>';

        $output .=                      '<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>';

        $output .=                  '</ul>';

        $output .=              '</div>';

        $output .=          '</div>';

        $output .=          '<div class="jp-title">';

        $output .=              '<ul>';

        $output .=                  '<li>' . $audio['title'] . '</li>';

        $output .=              '</ul>';

        $output .=          '</div>';

        $output .=          '<div class="jp-no-solution">';

        $output .=              '<span>Update Required</span>To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.';

        $output .=          '</div>';

        $output .=      '</div>';

        $output .=  '</div>';

    }



    /*$output =   '<script>

                    audiojs.events.ready(function() {

                        var as = audiojs.createAll();

                    });

                </script>';

    $output = '<div class="hb-sigle-audio">';

    $output .= '<h3>Title : ' . $audio['title'] . '</h3>';

    $output .= '<audio src="' . $audio['audioURL'] . '" preload="auto" />';

    $output .= '<audio controls>';

    $output .= '<source src="' . $audio['audioURL'] . '" type="audio/mpeg">';

    $output .= '<embed height="50" width="100" src="' . $audio['audioURL'] . '">';

    $output .= '</audio>';

    $output .= '</div>';*/



    return $output;

}