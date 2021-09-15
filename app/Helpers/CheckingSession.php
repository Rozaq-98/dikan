<?php
    namespace App\Helpers;

    use Session;

    class CheckingSession
    {
        public static function checking_session($id){
            // output
            $output = true;

            // checking session
            if (is_null(Session::get('ss_username'))) {
                $output = false;
                Session::flush();
            } else {
                // checking role access
                if (!in_array($id,Session::get('ss_arrdetail'),true)) {
                    $output = false;
                    Session::flush();
                }
            }

            return $output;
        }
    }
?>
