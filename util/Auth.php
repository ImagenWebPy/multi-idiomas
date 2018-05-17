<?php

/**
 * 
 */
class Auth {

    public static function handleLogin($url = NULL) {
        @session_start();
        $logged = (!empty($_SESSION['loggedIn']) ? $_SESSION['loggedIn'] : '');
        if (empty($logged)) {
            //session_destroy();
            if (!isset($_SESSION['urlAnterior'])) {
                Session::set('urlAnterior', array(
                    'url' => NULL
                ));
            }
            if (!empty($url)) {
                $_SESSION['urlAnterior']['url'] = $url;
            }
            header('location: ' . URL);
            exit();
        }
    }
    
    public static function handleLoginAdmin($url = NULL) {
        @session_start();
        $logged = (!empty($_SESSION['loggedInAdmin']) ? $_SESSION['loggedInAdmin'] : '');
        if (empty($logged)) {
            //session_destroy();
            if (!isset($_SESSION['urlAnterior'])) {
                Session::set('urlAnterior', array(
                    'url' => NULL
                ));
            }
            if (!empty($url)) {
                $_SESSION['urlAnterior']['url'] = $url;
            }
            header('location: ' . URL . 'login/');
            exit();
        }
    }   

}
