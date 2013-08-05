<?php
global $wpdb;
define( 'AUDIO_DB', $wpdb->prefix . 'hb_audios' );


function hb_db_create_table() {
    global $wpdb;
    $sql =
        "CREATE TABLE IF NOT EXISTS " . AUDIO_DB . " (
            aid BIGINT(20) NOT NULL AUTO_INCREMENT ,
            gid BIGINT(20) DEFAULT '0' NOT NULL ,
            filename VARCHAR(255) NOT NULL ,
            audioURL VARCHAR(255) NOT NULL ,
            title VARCHAR(255) NOT NULL ,
            PRIMARY KEY  (aid)
            ) ;" ;

    $wpdb->query( $sql );
}


function hb_db_delete_table() {
    global $wpdb;
    $sql = "DROP TABLE IF EXISTS " . AUDIO_DB;
    $wpdb->query( $sql );
}

function hb_db_get_AudioGallery($gallery_id) {

    global $wpdb;
    $gallery = array();
    $gallery = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM wp_hb_audios WHERE gid=%d ORDER BY title", $gallery_id ), ARRAY_A );

    return $gallery;
}


function hb_db_insert_audio( $gallery_id, $title, $filename, $audioURL ) {
    global $wpdb;

    $wpdb->query( $wpdb->prepare( "INSERT INTO wp_hb_audios (gid, filename, audioURL, title) VALUES (%d, %s, %s, %s)",
        $gallery_id, $filename, $audioURL, $title ) );

    return true;
}


function hb_db_get_audio( $audio_id ) {
    global $wpdb;
    $audio = array();

    $audio = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM wp_hb_audios WHERE aid=%d ORDER BY title", $audio_id ), ARRAY_A );

    return $audio;
}


function hb_db_delete_audio( $audio_id ) {
    global $wpdb;

    $wpdb->query( $wpdb->prepare( "DELETE FROM wp_hb_audios WHERE aid=%d", $audio_id ) );
}


function hb_db_update_audio( $audio_id, $audio_title ) {
    global $wpdb;

    $wpdb->query( $wpdb->prepare( "UPDATE wp_hb_audios SET title=%s WHERE aid=%d", $audio_title, $audio_id ) );
}


function hb_db_delete_audiogallery_post() {
    $gallery_posts = get_posts( array( 'post_type' => 'hb_audio_gallery', 'numberposts' => 300));
    foreach( $gallery_posts as $gpost ) {
        wp_delete_post( $gpost->ID, true);
    }
}
