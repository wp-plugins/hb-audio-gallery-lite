<?php



/*

Plugin Name: HB AUDIO GALLERY LITE

Plugin URI: http://plugins.hbwebsol.com

Description: An audio gallery plugin for WordPress by HBWEBSOL.

Version: 1.0.0

Author: HBWEBSOL TEAM

Author URI: http://www.hbwebsol.com/

License: GPLv2.

*/







class hb_Loader {



    var $gallery;

    var $update;



    function hb_Loader() {

        $this->load_defines();

        $this->load_files();

        $this->create_gallery_directory();



        $plugin_name = basename(dirname(__FILE__)).'/'.basename(__FILE__);



        register_activation_hook( $plugin_name, array( &$this, 'plugin_active' ) );

        register_deactivation_hook( $plugin_name, array( &$this, 'plugin_deactive' ) );

        register_uninstall_hook( $plugin_name, array(__CLASS__, 'plugin_uninstall') );

        add_action( 'init', array( &$this, 'init' ), 11 );

        add_action( 'init', array( &$this, 'init_autoupdate' ) );

        add_action( 'admin_init', array( &$this, 'admin_init' ) );

    }





    function  init() {

        wp_enqueue_script('jquery');



        wp_register_style('hb-style', AG_URLPATH .'css/hb-style.css', array(), null);

        wp_enqueue_style('hb-style');



        wp_register_style('hb-jplayer-style', AG_URLPATH .'lib/jPlayer/skin/blue.monday/jplayer.blue.monday.css', array(), null);

        wp_enqueue_style('hb-jplayer-style');



        wp_register_script('hb-jplayer', AG_URLPATH .'lib/jPlayer/js/jquery.jplayer.js', array('jquery'), null);

        wp_enqueue_script('hb-jplayer');



        wp_register_script('hb-jplayer-playlist', AG_URLPATH .'lib/jPlayer/js/jplayer.playlist.js', array('jquery'), null);

        wp_enqueue_script('hb-jplayer-playlist');



    }





    function  admin_init() {



        add_action( 'admin_head',  array( &$this, 'plupload_admin_head') );

        add_action( 'wp_ajax_plupload_action',  array( &$this, 'g_plupload_action') );



        wp_enqueue_script('plupload-all');



        wp_register_script('hbplupload', AG_URLPATH .'gallery/js/hbplupload.js', array('jquery'), null);

        wp_enqueue_script('hbplupload');



        wp_register_style('hbplupload', AG_URLPATH .'gallery/css/hbplupload.css');

        wp_enqueue_style('hbplupload');



        // scan audio ajax

        wp_enqueue_script('hb-scan-audiofile', AG_URLPATH .'gallery/js/hbscanfile.js', array('jquery'), null);

        wp_localize_script( 'hb-scan-audiofile', 'ajax_object',

            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

        add_action( 'wp_ajax_hb-scanaudio', array( &$this, 'scanaudio_callback') );



        // upload audio ajax

        wp_enqueue_script('hb-upload-audiofile', AG_URLPATH .'gallery/js/hbaddgallery.js', array('jquery'), null);

        wp_localize_script( 'hb-upload-audiofile', 'ajax_object',

            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

        add_action( 'wp_ajax_hb-uploadaudio', array( &$this, 'uploadaudio_callback') );



        // htaccess ajax

        wp_enqueue_script('hb-htaccess', AG_URLPATH .'gallery/js/hbhtaccess.js', array('jquery'), null);

        wp_localize_script( 'hb-htaccess', 'ajax_object',

            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

        add_action( 'wp_ajax_hb-htaccess', array( &$this, 'htaccess_callback') );

    }





    function load_defines() {

        define( 'WINABSPATH', str_replace("\\", "/", ABSPATH) );

        define( 'AG_FOLDER', basename( dirname( __FILE__ ) ) );

        define( 'AG_ABSPATH', trailingslashit( str_replace("\\","/", WP_PLUGIN_DIR . '/' . AG_FOLDER ) ) );

        define( 'AG_URLPATH', trailingslashit( plugins_url( AG_FOLDER ) ) );

        define( 'AG_UPLOAD_DIR', WINABSPATH . 'wp-content/hb-audio-gallery' );



        define( 'HB_AG_VERSION', '1.0.0' );

        define( 'HB_AG_UPDATE_URL', 'http://audio.hbwebsol.com/update/update.php' );

        define( 'AG_OPTIONS', 'hb_ag_options' );



        global $wpdb;

        //define( 'AUDIO_DB', $wpdb->prefix . 'hb_audios' );

    }





    function  load_files() {

        require_once (dirname (__FILE__) . '/gallery/gallery.php');

        require_once (dirname (__FILE__) . '/gallery/gallery-content.php');

        $this->gallery = new hb_gallery();



        require_once (dirname (__FILE__) . '/lib/audioDB.php');

        require_once (dirname (__FILE__) . '/lib/util-functions.php');

    }



    function create_gallery_directory() {

        require_once( ABSPATH . "wp-admin/includes/class-wp-filesystem-base.php" );

        require_once( ABSPATH . "wp-admin/includes/class-wp-filesystem-direct.php" );

        $wp_fs_d = new WP_Filesystem_Direct( new StdClass() );



        if ( !$wp_fs_d->is_dir( AG_UPLOAD_DIR ) && !$wp_fs_d->mkdir( AG_UPLOAD_DIR, 0777 ) )

            wp_die( sprintf( __( "Impossible to create %s directory." ), AG_UPLOAD_DIR ) );



        $uploads = wp_upload_dir();

        if ( !$wp_fs_d->is_dir( $uploads['path'] ) && !$wp_fs_d->mkdir( $uploads['path'], 0777 ) )

            wp_die( sprintf( __( "Impossible to create %s directory." ), $uploads['path'] ) );



//        if (!is_dir($uploads['path'])) {

//            umask(0);

//            mkdir($uploads['path'], 0777);

//        }



    }

    function plupload_admin_head() {



    // place js config array for plupload

        $plupload_init = array(

            'runtimes' => 'html5,silverlight,flash,html4',

            'browse_button' => 'plupload-browse-button', // will be adjusted per uploader

            'container' => 'plupload-upload-ui', // will be adjusted per uploader

            'drop_element' => 'drag-drop-area', // will be adjusted per uploader

            'file_data_name' => 'async-upload', // will be adjusted per uploader

            'multiple_queues' => true,

            'max_file_size' => 64000000 . 'b',

            'url' => admin_url('admin-ajax.php'),

            'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),

            'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),

            'filters' => array(array('title' => __('Audio Files'), 'extensions' => 'mp3')),

            'multipart' => true,

            'urlstream_upload' => true,

            'multi_selection' => false, // will be added per uploader

            // additional post data to send to our ajax hook

            'multipart_params' => array(

                '_ajax_nonce' => "", // will be added per uploader

                'action' => 'plupload_action', // the ajax action name

                'audioid' => 0 // will be added per uploader

            )

        );

        ?>

        <script type="text/javascript">

            var base_plupload_config=<?php echo json_encode($plupload_init); ?>;

        </script>

    <?php

    }





    function g_plupload_action() {



        // check ajax noonce

        $audioid = $_POST['audioid'];



        check_ajax_referer($audioid . 'pluploadan');



        // handle file upload

        $status = wp_handle_upload($_FILES[$audioid . 'async-upload'], array('test_form' => true, 'action' => 'plupload_action'));



        // send the uploaded file url in response

        echo $status['url'];

        exit;

    }







    function scanaudio_callback() {



        $upload_dir = $_REQUEST['upload_dirctory'] . '/';

        $gallery_id = $_REQUEST['gallery_id'];

        $audio_columns = $_REQUEST['audio_columns'];

        $hidden_columns = $_REQUEST['hidden_columns'];



        $audio_columns = explode(",", $audio_columns);

        if($hidden_columns != "no")

            $hidden_columns = explode(",", $hidden_columns);

        else

            $hidden_columns = array();



        $audioList = array();

        $audioList = hb_db_get_AudioGallery( $gallery_id );



        $audio_filename_List = array();

        $count_del = 0;

        foreach( $audioList as $audio ) {

            $filepath = hb_convert_urltopath( $audio['audioURL'] );

            if( is_file( $filepath ) == false ) {

                hb_db_delete_audio($audio['aid']);

                $count_del++;

                continue;

            }

            array_push( $audio_filename_List, $audio['filename'] );

        }



        $count = 0;

        if (is_dir($upload_dir)) {

            if ($dh = opendir($upload_dir)) {

                while (($file = readdir($dh)) !== false) {

                    if($file == "." || $file == "..")

                        continue;



                    if( !in_array( $file, $audio_filename_List ) ) {

                        $file_path = $upload_dir . $file;

                        $file_url = hb_convert_pathtourl( $file_path );

                        $filepart = pathinfo( $file_path );





                        hb_db_insert_audio( $gallery_id, $filepart['filename'], $filepart['basename'], $file_url );

                        $count++;

                    }

                }

                closedir($dh);

            }

        }



        $audioList = hb_db_get_AudioGallery( $gallery_id );

        $return_arr['content'] = hb_get_gallery_list_content( $audioList, $audio_columns, $hidden_columns );

        $return_arr['message'] = '<p style="margin:5px 0;">Scan Finished!</p>';



        if( $count != 0 ){

            $return_arr['message'] .= '<p style="margin:5px 0;">' . $count . ' files are added.</p>';

        } else {

            $return_arr['message'] .= '<p style="margin:5px 0;">No files to be added.</p>';

        }

        if( $count_del != 0 ) {

            $return_arr['message'] .= '<p style="margin:5px 0;">' . $count_del . ' files are not exist. These Files are removed in gallery.</p>';

        }



        echo json_encode( $return_arr );

        die();

    }





    function uploadaudio_callback() {

        if( $_REQUEST['upload_dirctory'] && $_REQUEST['audio_upload'] && $_REQUEST['gallery_id'] && $_REQUEST['audio_columns'] && $_REQUEST['hidden_columns'] ) {



            $upload_dir = $_REQUEST['upload_dirctory'];

            $audioS = $_REQUEST['audio_upload'];

            $audio_arr = explode( ',', $audioS );



            $gallery_id = $_REQUEST['gallery_id'];

            $audio_columns = $_REQUEST['audio_columns'];

            $hidden_columns = $_REQUEST['hidden_columns'];



            $audio_columns = explode(",", $audio_columns);

            if($hidden_columns != "no")

                $hidden_columns = explode(",", $hidden_columns);

            else

                $hidden_columns = array();



            $audioList = array();



            $count_success = 0;

            $count_fail = 0;

            $count_exist = 0;

            foreach( $audio_arr as $audio ) {

                $filepart = pathinfo( $audio );



                $newfile = $upload_dir . '/' . $filepart['basename'];

                $newfile_url = hb_convert_pathtourl( $newfile );

                $oldfile = hb_convert_urltopath( $audio );





                if( file_exists( $newfile ) ) {

                    $count_exist++;

                    unlink( $oldfile );

                    continue;

                }



                hb_copyfile( $oldfile, $newfile );

                if( file_exists( $newfile ) ) {

                    hb_db_insert_audio( $gallery_id, $filepart['filename'], $filepart['basename'], $newfile_url );

                    $count_success++;

                } else {

                    $count_fail++;

                }



                unlink( $oldfile );

            }



            $audioList = hb_db_get_AudioGallery( $gallery_id );

            $return_arr['content'] = hb_get_gallery_list_content( $audioList, $audio_columns, $hidden_columns );

            $return_arr['message'] = '<p style="margin:5px 0;">Upload Finished!</p>';

            if($count_success != 0)

                $return_arr['message'] .= '<p style="margin:5px 0;">' . $count_success . ' files are uploaded.</p>';

            if( $count_fail != 0 )

                $return_arr['message'] .= '<p style="margin:5px 0;">' . $count_fail . ' files are failed.</p>';

            if( $count_exist != 0 )

                $return_arr['message'] .= '<p style="margin:5px 0;">' . $count_exist . ' files alread exist.</p>';



            echo json_encode( $return_arr );

            die();

        }

    }





    function htaccess_callback() {

        if( $_REQUEST['ht_content']  ) {



            $ht_content = $_REQUEST['ht_content'];

            if( hb_WriteNewHtaccess( $ht_content ) ) {

                $return_arr['message'] = '<p style="margin:5px 0;">Save Successful!</p>';

            } else {

                $return_arr['message'] = '<p style="margin:5px 0;">The file could not be saved!</p>';

            }



            echo json_encode( $return_arr );

            die();

        }

    }





    function init_autoupdate() {

        require_once (dirname (__FILE__) . '/hb_autoupdate.php');

        $hb_plugin_current_version = HB_AG_VERSION;  // HB_AG_VERSION

        $hb_plugin_remote_path = HB_AG_UPDATE_URL;

        $hb_plugin_slug = plugin_basename(__FILE__);

        $this->update = new hb_auto_update($hb_plugin_current_version, $hb_plugin_remote_path, $hb_plugin_slug);

    }





    function plugin_active() {



        hb_db_create_table();



        if ( get_option( AG_OPTIONS ) === false ) {

            $new_options['hb_audio_download_enable'] = false;

            $new_options['hb_audio_facebook_sharing'] = false;

            $new_options['hb_audio_addthis_sharing'] = false;

            $new_options['addthis_publish_id'] = "";

            $new_options['version'] = HB_AG_VERSION;// HB_AG_VERSION

            add_option( AG_OPTIONS, $new_options );

        } else {

            $existing_options = get_option( AG_OPTIONS );

            if ( version_compare( $existing_options['version'], HB_AG_VERSION,  '<' ) ) {// HB_AG_VERSION

                $existing_options['version'] = HB_AG_VERSION;

                update_option( AG_OPTIONS, $existing_options );

            }

        }

    }





    function plugin_deactive() {



        /*if ( get_option( 'hb_ag_options' ) != false ) {

            delete_option( 'hb_ag_options' );

        }



        hb_db_delete_table();



        hb_db_delete_audiogallery_post();



        hb_remove_dir( AG_UPLOAD_DIR );*/ /*Husain Ali as on 04-10-13*/

    }





    function plugin_uninstall() {



        if ( get_option( 'hb_ag_options' ) != false ) {

            delete_option( 'hb_ag_options' );

        }



        hb_db_delete_table();

		

		hb_db_delete_audiogallery_post(); //Husain Ali as on 04-10-13



        hb_remove_dir( AG_UPLOAD_DIR ); //Husain Ali as on 04-10-13

    }





}









global $hb_Loader;

$hb_Loader = new hb_Loader();