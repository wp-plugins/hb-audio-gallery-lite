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

function hb_db_getCorrectSql($sql) {
    $find_str = "'" . AUDIO_DB . "'";
    $retsql = str_replace($find_str, AUDIO_DB, $sql);
    return $retsql;


}
function hb_db_get_AudioGallery($gallery_id) {

    global $wpdb;

    $gallery = array();
    $sql = $wpdb->prepare( "SELECT * FROM %s WHERE gid=%d ORDER BY title", AUDIO_DB, $gallery_id );
    $gallery = $wpdb->get_results( hb_db_getCorrectSql($sql), ARRAY_A );

    return $gallery;
}


function hb_db_insert_audio( $gallery_id, $title, $filename, $audioURL ) {
    global $wpdb;
    $sql = $wpdb->prepare( "INSERT INTO %s (gid, filename, audioURL, title) VALUES (%d, %s, %s, %s)",
        AUDIO_DB, $gallery_id, $filename, $audioURL, $title );

    $wpdb->query( hb_db_getCorrectSql($sql) );

    return true;
}


function hb_db_get_audio( $audio_id ) {
    global $wpdb;
    $audio = array();
    $sql = $wpdb->prepare( "SELECT * FROM %s WHERE aid=%d ORDER BY title", AUDIO_DB, $audio_id );

    $audio = $wpdb->get_row( hb_db_getCorrectSql($sql), ARRAY_A );

    return $audio;
}


function hb_db_delete_audio( $audio_id ) {
    global $wpdb;
    $sql = $wpdb->prepare( "DELETE FROM %s WHERE aid=%d", AUDIO_DB, $audio_id );
    $wpdb->query( hb_db_getCorrectSql($sql) );
}


function hb_db_update_audio( $audio_id, $audio_title ) {
    global $wpdb;
    $sql = $wpdb->prepare( "UPDATE %s SET title=%s WHERE aid=%d", AUDIO_DB, $audio_title, $audio_id );
    $wpdb->query( hb_db_getCorrectSql($sql) );
}


function hb_db_delete_audiogallery_post() {
    $gallery_posts = get_posts( array( 'post_type' => 'hb_audio_gallery', 'numberposts' => 300));
    foreach( $gallery_posts as $gpost ) {
        wp_delete_post( $gpost->ID, true);
    }
}
