<?php

/**
 * ARCHIVO DE CONFIGURACIONES
 * @author "Raul Ramirez" <raul.chuky@gmail.com>
 * @version 1 2018-02-13
 */
// Always provide a TRAILING SLASH (/) AFTER A PATH
//header('Content-type: text/plain; charset=utf-8');
$host = getHost();
switch ($host) {
    case 'localhost':
        define('URL', 'http://localhost/multi-idiomas/');
        define('DB_USER', 'root');
        define('DB_PASS', 'cThoNTJ0cy9tVU5lQ3JnTDgrbXZxdz09');
        define('DB_NAME', 'multi-idiomas');
        define('DB_HOST', 'localhost');
        break;
}
define('LIBS', 'libs/');

define('DB_TYPE', 'mysql');


// This is for database passwords only
define('HASH_PASSWORD_KEY', '!@123456789ABCDEFGHIJKLMNOPRSTWYZ[¿]{?}<->');

//Constantes varias
define('SITE_TITLE', 'Multi Idiomas');
define('CANT_REG', 12);

function getHost() {
    $host = $_SERVER['HTTP_HOST'];
    return $host;
}
