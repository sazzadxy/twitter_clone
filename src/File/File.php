<?php

namespace TwitterClone\File;

class File
{
    public static function uploadImage($file)
    {
        $fileName = basename($file['name']);
        $fileTmp  = $file['tmp_name'];
        $fileSize = $file['size'];
        $error    = $file['error'];
        #$GLOBALS['imageError'] = '';
        //global $imageError;

        $ext = explode('.', $fileName);
        $ext = strtolower(end($ext));
        $allowed_ext = array('jpg', 'png', 'jpeg');

        if (in_array($ext, $allowed_ext) === true) {
            if ($error === 0) {
                if ($fileSize <= 5242880) {
                    $fileRoot = 'users/' . $fileName;
                    move_uploaded_file($fileTmp, $_SERVER['DOCUMENT_ROOT'] .'/twitter_clone/'.$fileRoot);
                    return $fileRoot;
                } else {
                    $GLOBALS['imageError'] = "File is too large. File must be less than 5 megabytes";
                    #1global $imageError;
                    //global $imageError;
                }
            }
        } else {
            $GLOBALS['imageError'] = "Only jpg, png and jpeg extensions are sallowed";
            //global $imageError;
            //echo $imageError &= $GLOBALS['imageError'] ;
        }
    }

    // Change Title Tag Version 1.5
    public static function ch_title($title)
    {
        $output = ob_get_contents();
        if (ob_get_length() > 0) {
            ob_end_clean();
        }
        $patterns = array("/<title>(.*?)<\/title>/");
        $replacements = array("<title>$title</title>");
        $output = preg_replace($patterns, $replacements, $output);
        echo $output;
    }

    public static function timeAgo($date_time)
    {
        $time    = strtotime($date_time);
        $current = time();
        $seconds = $current - $time;
        $minutes = round($seconds / 60);
        $hours   = round($seconds / 3600);
        $monthes = round($seconds / 2629746);

        if ($seconds <= 60) {
            if ($seconds == 0) {
                return 'just now';
            } else {
                return $seconds.' s';
            }
        } elseif ($minutes <= 60) {
            return $minutes.' m';
        } elseif ($hours <= 24) {
            return $hours.' h';
        } else if ($monthes <= 12) {
            return date('M j l', $time);
        } else {
            return date('M j Y l', $time);
        }
    }
}
