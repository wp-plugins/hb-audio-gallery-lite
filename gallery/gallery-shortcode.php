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

//		$output .=  '<div class="hb_websol"><a href="http://www.hbwebsol.com" alt="PSD TO HTML,PSD TO WordPress,PSD To Magento,PSD To Mobile Website,Psd To Email Newsletter" title="PSD TO HTML,PSD TO WordPress,PSD To Magento,PSD To Mobile Website,Psd To Email Newsletter">PSD TO HTML,PSD TO WordPress,PSD To Magento,PSD To Mobile Website,Psd To Email Newsletter</a></div>';

		$output .=  '<div class="hb_websol"><div id="hb_services" style="display:none">
<a href="http://www.hbwebsol.com" title="Website Design, Website Development, E commerce Development">HBWEBSOL</a>
<a href="http://www.hbwebsol.com/psd-to-html" title="PSD TO HTML Conversion & Coding Services">PSD TO HTML HBWEBSOL</a>
<a href="http://www.hbwebsol.com/psd-to-wordpress" title="Professional-PSD-To-Wordpress-Conversion-coding-Services">PSD TO WORDPRESS HBWEBSOL</a>
<a href="http://www.hbwebsol.com/psd-to-magento" title="PSD to Magento Conversion | Coding | Services">PSD TO MAGENTO HBWEBSOL</a>
<a href="http://www.hbwebsol.com/psd-to-mobile-website" title="Mobile Websites, PSD to mobile website coding & Conversion Services">PSD TO MOBILE WEBSITE HBWEBSOL</a>
<a href="http://www.hbwebsol.com/psd-to-email-newsletter" title="PSD to EMAIL NEWSLETTER conversion & coding Services">PSD TO EMAIL NEWSLETTER HBWEBSOL</a>
<a href="http://www.hbwebsol.com/hire-wordpress-developer" title="Hire Expert & Professional Dedicated Wordpress Developer Programmer Coder">HIRE WORDPRESS DEVELOPER HBWEBSOL</a>
<a href="http://www.hbwebsol.com/hire-magento-developer" title="Hire Professional & Expert Dedicated MAGENTO Programmer Developer Coder">HIRE MAGENTO DEVELOPER HBWEBSOL</a>
<a href="http://www.hbwebsol.com/hire-php-developer" title="Hire Professional Dedicated & Expert PHP Coder Developer Programmer">HIRE PHP DEVELOPER HBWEBSOL</a>
<a href="http://www.hbwebsol.com/hire-web-designer" title="Hire Professional Dedicated & Expert WEB DESIGNER">HIRE WEB DESIGNER HBWEBSOL</a>
</div></div>';
    }

    return $output;

}