<?php



function hb_gallery_review_list() {

    $query_params = array( 'post_type' => 'hb_audio_gallery',

        'post_status' => 'publish',

        'posts_per_page' => 10 );



    $page_num = ( get_query_var( 'paged' ) ?

        get_query_var( 'paged' ) : 1 );



    if ( $page_num != 1 )

        $query_params['paged'] = $page_num;





    $gallery_review_query = new WP_Query;

    $gallery_review_query->query( $query_params );



    $output = '<h3>Galley List</h3>';

    if ( $gallery_review_query->have_posts() ) {



        $output .= '<div class="hb-gallery-list"><table>';

        $output .= '<tr><th style="width: 350px"><strong>';

        $output .= 'Title</strong></th>';

        $output .= '<th><strong>Author</strong></th></tr>';



        while ( $gallery_review_query->have_posts() ) {

            $gallery_review_query->the_post();

            $output .= '<tr><td><a href="' . post_permalink();

            $output .= '">';

            $output .= get_the_title( get_the_ID() ) . '</a></td>';

            $output .= '<td>';

            $output .= esc_html( get_post_meta( get_the_ID(), 'gallery_author', true ) );

            $output .= '</td></tr>';

        }

        $output .= '</table></div>';

        // Display page navigation links

        if ( $gallery_review_query->max_num_pages > 1 ) {

            $output .= '<nav id="nav-below">';

            $output .= '<div class="nav-previous">';$output .= get_next_posts_link

            ( '<span class="meta-nav">&larr;</span>

                Older reviews',

                $gallery_review_query->max_num_pages );

            $output .= '</div>';

            $output .= '<div class="nav-next">';

            $output .= get_previous_posts_link

            ( 'Newer reviews <span class="meta-nav">

                &rarr;</span>',

                $gallery_review_query->max_num_pages );

            $output .= '</div>';

            $output .= '</nav>';

        }

        // Reset post data query

        wp_reset_postdata();

    }

    return $output;

}







function hb_audio_list_in_gallery( $atts ) {

    extract( shortcode_atts( array(

        'gid' => '',

        'autoplay' => 'no'

    ), $atts ) );



    global $wpdb;

    $gallery = hb_db_get_AudioGallery( $gid );



    foreach( $gallery as $audio ) {

        $filepath = hb_convert_urltopath( $audio['audioURL'] );

        if( is_file( $filepath ) == false ) {

            hb_db_delete_audio($audio['aid']);

            continue;

        }

    }



    $gallery = hb_db_get_AudioGallery( $gid );



    $hb_plugin_current_version = HB_AG_VERSION;  // HB_AG_VERSION

    $hb_plugin_remote_path = HB_AG_UPDATE_URL;

    $hb_plugin_slug = 'hb-audio-gallery/hb-audio-gallery.php';

    $update = new hb_auto_update($hb_plugin_current_version, $hb_plugin_remote_path, $hb_plugin_slug);

    $remote_pay_version = $update->getRemote_pay_version();



    $gallery_name = get_the_title($gid);

    $aid = $audio['aid'];

    $base_dir =  str_replace("\\", "/", ABSPATH);

    $base_dir = $base_dir . 'wp-content/hb-audio-gallery' . '/' . $gallery_name;

    $audio = hb_db_get_audio( $aid );

    $filename = basename($audio['filename']);

    $file = $base_dir . '/' . $filename;

    $file_size = hb_get_filesize( $audio['audioURL'] );



    $output = '<h3>' . get_the_title($gid) . '</h3>';



    if( !empty( $gallery ) ) {

        $output .= '<script>

                    //<![CDATA[

                    $(document).ready(function(){



                        new jPlayerPlaylist({

                            jPlayer: "#jquery_jplayer_' . $gid . '",

                            cssSelectorAncestor: "#jp_container_' . $gid . '"

                        }, [';



        foreach( $gallery as $audio ) {

            $filepart = pathinfo($audio['filename']);

            $download_action_url = AG_URLPATH . 'gallery/audio-download.php?file_size=' . $file_size . '&file_path=' . $file;

            $options = get_option( AG_OPTIONS );

            $addthis_pubID = $options['addthis_publish_id'];



            $output_button = '<div style=\"float:right;width:70px;\">';



            if( $options['hb_audio_download_enable'] == true ) {

                $output_button .= '<a href=\"' . $download_action_url . '\" style=\"margin-right:7px;\" target=\"audio-download\">' . '<img src=\"' . AG_URLPATH . '/images/audio-down-16.png\" alt=\"audio download\" style=\"border:none;\"/></a>';

            }

            if( $options['hb_audio_facebook_sharing'] == true ) {

                $output_button .= '<a style=\"cursor:pointer; margin-right:7px;\"' .

                        ' onclick=\"window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=' . $audio['title'] . ' | ' . get_the_title() . '&amp;p[url]=' . get_permalink() . '\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');\"' .

                        ' href=\"javascript: void(0)\">' .

                        '<img src=\"' . AG_URLPATH . '/images/audio-facebook-16.png\" alt=\"audio facebook share\" style=\"border:none;\"/></a>';

            }

            if( $options['hb_audio_addthis_sharing'] == true ) {

                if($addthis_pubID != "") {

                    $output_button .= '<a class=\"addthis_button hb_audio_share\" href=\"http://www.addthis.com/bookmark.php?v=300&amp;pubid=' . $addthis_pubID . '\" style=\"margin-right:7px; text-indent:-9999px;\" addthis:url=\"' . get_permalink() . '\" addthis:title=\"' . $audio['title'] . ' | ' . get_the_title() . '\"><img src=\"' . AG_URLPATH . '/images/audio-share-16.png\" alt=\"Bookmark and Share\" style=\"border:none;\"/><span style=\"display:none;\">' . $audio['title'] . ' | ' . get_the_title() . '</span></a>';

                    $output_button .= '<script_ type=\"text/javascript\" src=\"//s7.addthis.com/js/300/addthis_widget.js#pubid=' . $addthis_pubID . '\"></script_>';

                }

            }



            if( $remote_pay_version != 'pay' ) {

                $output_button = '<div style=\"float:right;width:70px;\">';

            }

            $output_button .= '<iframe name=\"audio-download\" style=\"display: none\"></iframe>';

            $output_button .= '</div>';



        $output .=    '{

                        title:"' . $audio['title'] . '",

                        mp3:"' . $audio['audioURL'] . '",

                        button:"' . $output_button . '"

                    },';

        }



        $output .=      '], {';

        if($autoplay == "yes") {

            $output .=          'playlistOptions: { autoPlay: true },';

        }



        $output .=             'swfPath: "http://www.jplayer.org/latest/js/Jplayer.swf",

                                supplied: "mp3",

                                wmode: "window",

                                smoothPlayBar: true,

                                keyEnabled: true

                            });

                        });

                    //]]>

                </script>';



        $output .= '<div id="jquery_jplayer_' . $gid . '" class="jp-jplayer"></div>';



        $output .= '<div id="jp_container_' . $gid . '" class="jp-audio hb-playlist">';

        $output .=      '<div class="jp-type-playlist">';

        $output .=          '<div class="jp-gui jp-interface">';

        $output .=              '<ul class="jp-controls">';

        $output .=                  '<li><a href="javascript:;" class="jp-previous" tabindex="1">previous</a></li>';

        $output .=                  '<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>';

        $output .=                  '<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>';

        $output .=                  '<li><a href="javascript:;" class="jp-next" tabindex="1">next</a></li>';

        $output .=                  '<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>';

        $output .=                  '<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>';

        $output .=                  '<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>';

        $output .=                  '<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>';

        $output .=              '</ul>';

        $output .=              '<div class="jp-progress">';

        $output .=                  '<div class="jp-seek-bar">';

        $output .=                      '<div class="jp-play-bar"></div>';

        $output .=                  '</div>';

        $output .=              '</div>';

        $output .=              '<div class="jp-volume-bar">';

        $output .=                  '<div class="jp-volume-bar-value"></div>';

        $output .=              '</div>';

        $output .=              '<div class="jp-time-holder">';

        $output .=                  '<div class="jp-current-time"></div>';

        $output .=                  '<div class="jp-duration"></div>';

        $output .=              '</div>';

        $output .=              '<ul class="jp-toggles">';

        $output .=                  '<li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle">shuffle</a></li>';

        $output .=                  '<li><a href="javascript:;" class="jp-shuffle-off" tabindex="1" title="shuffle off">shuffle off</a></li>';

        $output .=                  '<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>';

        $output .=                  '<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>';

        $output .=              '</ul>';

        $output .=          '</div>';

        $output .=          '<div class="jp-playlist">';

        $output .=              '<ul>';

        $output .=                  '<li></li>';

        $output .=              '</ul>';

        $output .=          '</div>';

        $output .=          '<div class="jp-no-solution">';

        $output .=              '<span>Update Required</span>

        To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.';

        $output .=          '</div>';

        $output .=      '</div>';

        $output .=  '</div>';

    }

    return $output;

}





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

                        $(document).ready(function(){



                            $("#jquery_jplayer_s' . $aid . '").jPlayer({

                                ready: function () {

                                    $(this).jPlayer("setMedia", {

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

       $output .=                  '<li class="jp-play"><a href="javascript:;" tabindex="1"><img src=' . AG_URLPATH . '/images/play.png></a></li>';
        $output .=                  '<li class="jp-pause"><a href="javascript:;" tabindex="1"><img src=' . AG_URLPATH . '/images/pause.png></a></li>';
        $output .=                  '<li class="jp-stop"><a href="javascript:;" tabindex="1"><img src=' . AG_URLPATH . '/images/stop.png></a></li>';
        $output .=                  '<li class="jp-mute"><a href="javascript:;" tabindex="1"><img src=' . AG_URLPATH . '/images/mute.png></a></li>';
        $output .=                  '<li class="jp-unmute"><a href="javascript:;" tabindex="1"><img src=' . AG_URLPATH . '/images/unmute.png></a></li>';
		
		 $output .=                  '<li class="hb-volume_bar"><div class="jp-volume-bar"><div class="jp-volume-bar-value"></div></div></li>';
		
        $output .=                  '<li class="jp-volume-max"><a href="javascript:;" tabindex="1"><img src=' . AG_URLPATH . '/images/maxvol.png></a></li>';
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



//    $output =   '<script>

//                    audiojs.events.ready(function() {

//                        var as = audiojs.createAll();

//                    });

//                </script>';

//    $output = '<div class="hb-sigle-audio">';

//    $output .= '<h3>Title : ' . $audio['title'] . '</h3>';

////    $output .= '<audio src="' . $audio['audioURL'] . '" preload="auto" />';

//    $output .= '<audio controls>';

//    $output .= '<source src="' . $audio['audioURL'] . '" type="audio/mpeg">';

//    $output .= '<embed height="50" width="100" src="' . $audio['audioURL'] . '">';

//    $output .= '</audio>';

//    $output .= '</div>';



    return $output;

}