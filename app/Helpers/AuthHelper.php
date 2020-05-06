<?php

namespace App\Helpers;

class AuthHelper
{
    /**
     * Check if session active
     * 
     * @return bool
     */
    public static function valid()
    {
        $id_not_empty = SessionHelper::get('id');
        $ip_match = SessionHelper::get('ip') === $_SERVER['REMOTE_ADDR'];
        $session_valid = SessionHelper::get('expires') > time();

        return $id_not_empty && $ip_match && $session_valid;
    }
}
