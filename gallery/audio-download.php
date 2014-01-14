<?php


define('ALLOWED_REFERRER', '');

if( $_REQUEST['file_size'] && $_REQUEST['file_path'] ) {
    $file_size =  $_REQUEST['file_size'];
    $file =  $_REQUEST['file_path'];
    $filename = basename($file);


    //if (!eregi($_SERVER['HTTP_HOST'], $_SERVER['HTTP_REFERER'])) Error("Not download in outside.");

    if(strstr($_SERVER["HTTP_USER_AGENT"] , "MSIE 6.") or strstr($_SERVER["HTTP_USER_AGENT"] , "MSIE 5.5"))
    {
        Header("Content-type: application/x-msdownload");
        header("Content-type: application/octet-stream");
        header("Cache-Control: private, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
    }
    else
    {
        header("Cache-control: private");
        header("Content-type: file/unknown");
        header('Content-Length: '.$file_size);
        Header("Content-type: file/unknown");
        Header("Content-Disposition: attachment; filename='" . $filename . "'");
        Header("Content-Description: PHP5 Generated Data");
        header("Pragma: no-cache");
        header("Expires: 0");
    }



//    if(eregi("(MSIE 5.0|MSIE 5.1|MSIE 5.5|MSIE 6.0)", $_SERVER["HTTP_USER_AGENT"]) && !eregi("(Opera|Netscape)", $_SERVER["HTTP_USER_AGENT"])) {
//        Header("Content-type: application/octet-stream");
//        Header("Content-Length: ".hb_get_filesize($audio['audioURL']));
//        Header("Content-Disposition: attachment; filename=".$filename);
//        Header("Content-Transfer-Encoding: binary");
//        Header("Pragma: no-cache");
//        Header("Expires: 0");
//    } else {
//        Header("Content-type: file/unknown");
//        Header("Content-Length: ".hb_get_filesize($audio['audioURL']));
//        Header("Content-Disposition: attachment; filename=".$filename);
//        Header("Content-Description: PHP3 Generated Data");
//        Header("Pragma: no-cache");
//        Header("Expires: 0");
//    }



    if(is_file("$file"))
    {
        $fp = fopen("$file", "r");

        if(!fpassthru($fp)) {
            fclose($fp);
        }
    }
    else
    {
    ?>
        <script>alert("This file is not exist!");history.back()</script><?php
    }

}




?>