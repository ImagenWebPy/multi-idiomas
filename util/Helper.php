<?php

#conexion a la BD
require 'libs/Database.php';

class Helper {

    function __construct() {
        $this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }

    /**
     * Funcion para limpiar un string
     * @param strig $String a quitar caracteres especiales y espacios
     * @return string formateado
     */
    public function cleanUrl($String) {
        $String = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä'), "a", $String);
        $String = str_replace(array('Á', 'À', 'Â', 'Ã', 'Ä'), "A", $String);
        $String = str_replace(array('Í', 'Ì', 'Î', 'Ï'), "I", $String);
        $String = str_replace(array('í', 'ì', 'î', 'ï'), "i", $String);
        $String = str_replace(array('é', 'è', 'ê', 'ë'), "e", $String);
        $String = str_replace(array('É', 'È', 'Ê', 'Ë'), "E", $String);
        $String = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º'), "o", $String);
        $String = str_replace(array('Ó', 'Ò', 'Ô', 'Õ', 'Ö'), "O", $String);
        $String = str_replace(array('ú', 'ù', 'û', 'ü'), "u", $String);
        $String = str_replace(array('Ú', 'Ù', 'Û', 'Ü'), "U", $String);
        $String = str_replace(array('?', '[', '^', '´', '`', '¨', '~', ']', '¿', '!', '¡'), "", $String);
        $String = str_replace("ç", "c", $String);
        $String = str_replace("Ç", "C", $String);
        $String = str_replace("ñ", "n", $String);
        $String = str_replace("Ñ", "N", $String);
        $String = str_replace("Ý", "Y", $String);
        $String = str_replace("ý", "y", $String);

        $String = str_replace("'", "", $String);
        //$String = str_replace(".", "_", $String);
        $String = str_replace("#", "_", $String);
        $String = str_replace(" ", "_", $String);
        $String = str_replace("/", "_", $String);

        $String = str_replace("&aacute;", "a", $String);
        $String = str_replace("&Aacute;", "A", $String);
        $String = str_replace("&eacute;", "e", $String);
        $String = str_replace("&Eacute;", "E", $String);
        $String = str_replace("&iacute;", "i", $String);
        $String = str_replace("&Iacute;", "I", $String);
        $String = str_replace("&oacute;", "o", $String);
        $String = str_replace("&Oacute;", "O", $String);
        $String = str_replace("&uacute;", "u", $String);
        $String = str_replace("&Uacute;", "U", $String);
        return $String;
    }

    /**
     * Funcion para limpiar un input antes de enviarlo por post
     * @param type $data
     * @return type
     */
    public function cleanInput($data) {
        $data = trim($data);
        $data = str_replace("'", "\'", $data);
        $data = htmlspecialchars($data);
        $data = stripslashes($data);

        return $data;
    }

    /**
     * Funcion para mostrar mensajes de alerta
     * @param string $type (success - info - warning - error)
     * @param string $message (mensaje a mostrar)
     * @return string
     */
    public function messageAlert($type, $message) {
        $alert = "";
        switch ($type) {
            case 'success':
                $alert .= '<div class="alert alert-success" role="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            ' . $message . '
                        </div>';
                break;
            case 'info':
                $alert .= '<div class="alert alert-info" role="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            ' . $message . '
                        </div>';
                break;
            case 'warning':
                $alert .= '<div class="alert alert-warning" role="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            ' . $message . '
                        </div>';
                break;
            case 'error':
                $alert .= '<div class="alert alert-danger" role="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            ' . $message . '
                        </div>';
                break;
        }
        return $alert;
    }

    /**
     * 
     * @return string url anterior del sitio
     */
    public function getUrlAnterior() {
        $url = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
        return $url;
    }

    /**
     * Funcion que retorna la url actual en forma de array
     * @return array url
     */
    public function getUrl() {
        $url = '';
        if (!empty($_GET['url'])) {
            $url = $_GET['url'];
            $url = explode('/', $url);
        }
        return $url;
    }

    /**
     * Funcion que lista las opciones del campo enum de una tabla
     * @param string $table
     * @param string $field
     * @return array con las opciones del campo enum
     */
    public function getEnumOptions($table, $field) {
        $finalResult = array();
        if (strlen(trim($table)) < 1)
            return false;
        $query = $this->db->select("show columns from $table");
        foreach ($query as $row) {
            if ($field != $row["Field"])
                continue;
//check if enum type 
            if (preg_match('/enum.(.*)./', $row['Type'], $match)) {
                $opts = explode(',', $match[1]);
                foreach ($opts as $item)
                    $finalResult[] = substr($item, 1, strlen($item) - 2);
            } else
                return false;
        }
        return $finalResult;
    }

    /**
     * Funcion para obtener las paginas donde nos encontramos
     * @return array
     */
    public function getPage() {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $pagina = (explode('/', $url));
        return $pagina;
    }

    /**
     * Devuelve la ip del visitante
     * @return string IP
     */
    public function getReal_ip() {
        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
            return $_SERVER["HTTP_FORWARDED"];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * 
     * @param int $per_page
     * @param int $page
     * @param string $table (tabla a obtener el maximo de registros)
     * @param string $section (ruta del mvc a paginar)
     * @return string
     */
    public function mostrarPaginador($per_page, $page, $table, $section, $condicion = NULL) {
        if (!empty($condicion)) {
            $query = $this->db->select("SELECT COUNT(*) as totalCount $condicion");
        } else {
            $query = $this->db->select("SELECT COUNT(*) as totalCount FROM $table where estado = 1");
        }
        $total = $query[0]['totalCount'];
        $adjacents = "2";

        $page = ($page == 0 ? 1 : $page);
        $start = ($page - 1) * $per_page;

        $prev = $page - 1;
        $next = $page + 1;
        $setLastpage = ceil($total / $per_page);
        $lpm1 = $setLastpage - 1;

        $paging = "";
        if ($setLastpage > 1) {
            $paging .= "<ul class='pagination'>";
            $paging .= "<li class='active'>Página $page de $setLastpage</li>";
            if ($setLastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $setLastpage; $counter++) {
                    if ($counter == $page)
                        $paging .= "<li class='active'><a href='#'>$counter</a></li>";
                    else
                        $paging .= '<li><a href="' . URL . $section . '/' . $counter . '" data-size="small" data-color="secondary" data-border>' . $counter . '</a></li>';
                }
            }
            elseif ($setLastpage > 5 + ($adjacents * 2)) {
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $paging .= '<li class="active"><a href="#">' . $counter . '</a></li>';
                        else
                            $paging .= '<li><a  href="' . URL . $section . '/' . $counter . '" data-size="small" data-color="secondary" data-border>' . $counter . '</a></li>';
                    }
                    $paging .= "<li class='dot'>...</li>";
                    $paging .= '<li><a  href="' . URL . $section . '/' . $lpm1 . '" data-size="small" data-color="secondary" data-border>' . $lpm1 . '</a></li>';
                    $paging .= '<li><a  href ="' . URL . $section . '/' . $setLastpage . '" data-size="small" data-color="secondary" data-border>' . $setLastpage . '</a></li>';
                }
                elseif ($setLastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $paging .= '<li><a  href ="' . URL . $section . '/1' . '" data-size="small" data-color="secondary" data-border>1</a></li>';
                    $paging .= '<li><a  href ="' . URL . $section . '/2' . '" data-size="small" data-color="secondary" data-border>2</a></li>';
                    $paging .= "<li class='dot'>...</li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $paging .= "<li class='active'><a href='#'>$counter</a></li>"
                            ;
                        else
                            $paging .= '<li><a  href ="' . URL . $section . '/' . $counter . '" data-size="small" data-color="secondary" data-border>' . $counter . '</a></li>';
                    }
                    $paging .= "<li class='dot'>..</li>";
                    $paging .= '<li><a  href="' . URL . $section . '/' . $lpm1 . '" data-size="small" data-color="secondary" data-border>' . $lpm1 . '</a></li>';
                    $paging .= '<li><a  href="' . URL . $section . '/' . $setLastpage . '" data-size="small" data-color="secondary" data-border>' . $setLastpage . '</a></li>';
                }
                else {
                    $paging .= '<li><a  href ="' . URL . $section . '/1' . '" data-size="small" data-color="secondary" data-border>1</a></li>';
                    $paging .= '<li><a  href ="' . URL . $section . '/2' . '" data-size="small" data-color="secondary" data-border>2</a></li>';
                    $paging .= "<li class = 'dot'>..</li>";
                    for ($counter = $setLastpage - (2 + ($adjacents * 2)); $counter <= $setLastpage; $counter++) {
                        if ($counter == $page)
                            $paging .= "<li class='active'><a href='#'>$counter</a></li>"
                            ;
                        else
                            $paging .= '<li><a  href="' . URL . $section . '/' . $counter . '" data-size="small" data-color="secondary" data-border>' . $counter . '</a></li>';
                    }
                }
            }

            if ($page < $counter - 1) {
                $paging .= '<li><a href="' . URL . $section . '/' . $next . '" data-size="small" data-color="secondary" data-border >Siguiente</a></li>';
                $paging .= '<li><a href="' . URL . $section . '/' . $setLastpage . '" data-size="small" data-color="secondary" data-border>Ultima</a></li>';
            } else {
                $paging .= "<li class='active'><a href='#'>Siguiente</a></li>";
                $paging .= "<li class='active'><a href='#'>Ultima</a></li>";
            }

            $paging .= "</ul>";
        }

        return $paging;
    }

    function redimensionar($file, $nameFile, $ancho, $alto, $serverdir) {
        # se obtene la dimension y tipo de imagen 
        $datos = getimagesize($file);

        $ancho_orig = $datos[0]; # Anchura de la imagen original 
        $alto_orig = $datos[1];    # Altura de la imagen original 
        $tipo = $datos[2];

        if ($tipo == 1) { # GIF 
            if (function_exists("imagecreatefromgif"))
                $img = imagecreatefromgif($file);
            else
                return false;
        }
        else if ($tipo == 2) { # JPG 
            if (function_exists("imagecreatefromjpeg"))
                $img = imagecreatefromjpeg($file);
            else
                return false;
        }
        else if ($tipo == 3) { # PNG 
            if (function_exists("imagecreatefrompng"))
                $img = imagecreatefrompng($file);
            else
                return false;
        }

        # Se calculan las nuevas dimensiones de la imagen 
        if ($ancho_orig > $alto_orig) {
            $ancho_dest = $ancho;
            $alto_dest = ($ancho_dest / $ancho_orig) * $alto_orig;
        } else {
            $alto_dest = $alto;
            $ancho_dest = ($alto_dest / $alto_orig) * $ancho_orig;
        }

        // imagecreatetruecolor, solo estan en G.D. 2.0.1 con PHP 4.0.6+ 
        $img2 = @imagecreatetruecolor($ancho_dest, $alto_dest) or $img2 = imagecreate($ancho_dest, $alto_dest);

        // Redimensionar 
        // imagecopyresampled, solo estan en G.D. 2.0.1 con PHP 4.0.6+ 
        @imagecopyresampled($img2, $img, 0, 0, 0, 0, $ancho_dest, $alto_dest, $ancho_orig, $alto_orig) or imagecopyresized($img2, $img, 0, 0, 0, 0, $ancho_dest, $alto_dest, $ancho_orig, $alto_orig);

        // Crear fichero nuevo, según extensión. 
        if ($tipo == 1) // GIF 
            if (function_exists("imagegif"))
                imagegif($img2, $serverdir . $nameFile, 60);
            else
                return false;

        if ($tipo == 2) // JPG 
            if (function_exists("imagejpeg"))
                imagejpeg($img2, $serverdir . $nameFile, 60);
            else
                return false;

        if ($tipo == 3)  // PNG 
            if (function_exists("imagepng"))
                imagepng($img2, $serverdir . $nameFile, 6);
            else
                return false;

        return true;
    }

    /**
     * Funcion que envia un correo a travez de la funcion mail de PHP.
     * @param string $para
     * @param string $asunto
     * @param string $mensaje
     * @param string $cc
     */
    public function sendMail($para, $asunto, $mensaje, $cc = NULL) {
        $page = $this->getHost();
        #Libreria para enviar mails
        require 'util/mailer/vendor/autoload.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->CharSet = "UTF-8";
        if (($page == 'localhost') || ($page == '192.168.1.89')) {
            $mail->isSMTP();
        }
        $mail->Host = 'smtp.office365.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = 'servidor@cadiem.com.py';
        $mail->Password = 'servi4926*';
        $mail->SetFrom('servidor@cadiem.com.py', 'Servidor - CADIEM Casa de Bolsa S.A.');
        $mail->AddAddress($para, $para);
        //$mail->SMTPDebug = 2;
        if ($cc == 1) {
            $mail->AddCC($cc_para, $cc_para);
            $mail->addReplyTo($cc_para, $cc_para);
        }
        $mail->IsHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;
        $mail->AltBody = $mensaje;

        if (!$mail->send()) {

            $estadomail = $mail->ErrorInfo;
        } else {

            $estadomail = 'OK';
        }
    }

    public function sendMailFondo($para, $asunto, $mensaje, $cc = NULL) {
        $page = $this->getHost();
        #Libreria para enviar mails
        require 'util/mailer/vendor/autoload.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->CharSet = "UTF-8";
        $mail->isSMTP();
        $mail->Host = 'cadiemfondos.com.py';
        $mail->Port = 465;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = 'servidor@cadiemfondos.com.py';
        $mail->Password = 'servi4926*';
        $mail->SetFrom('servidor@cadiemfondos.com.py', 'Servidor - CADIEM Casa de Bolsa S.A.');
        $mail->AddAddress($para, $para);
        $mail->SMTPDebug = 2;
        if ($cc == 1) {
            $mail->AddCC($cc_para, $cc_para);
            $mail->addReplyTo($cc_para, $cc_para);
        }
        $mail->IsHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;
        $mail->AltBody = $mensaje;

        if (!$mail->send()) {

            $estadomail = $mail->ErrorInfo;
        } else {

            $estadomail = 'OK';
        }
    }

    public function sendMailPhp($para, $asunto, $mensaje, $cc = NULL) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: CADIEM Fondos <atc@cadiemfondos.com.py>' . "\r\n";
        if (!empty($cc))
            $headers .= 'Bcc:' . $emailAsesor . "\r\n";
        $headers .= 'Reply-To: atc@cadiemfondos.com.py' . "\r\n";
        mail($para, $asunto, $mensaje, $headers);
    }

    /**
     * Ver http://php.net/manual/es/function.date.php para mas información sobre los formatos de fecha.
     * @param string $format
     * @param type $month (Nombre abreviado o completo del mes en ingles de acuerdo al formato elegido)
     * @return string
     */
    public function TranslateDate($format, $month, $language = 'es') {
        $mes = '';
        switch ($format) {
            case 'F':
                switch ($month) {
                    case 'January':
                        $mes = 'Enero';
                        break;
                    case 'February':
                        $mes = 'Febrero';
                        break;
                    case 'March':
                        $mes = 'Marzo';
                        break;
                    case 'April':
                        $mes = 'Abril';
                        break;
                    case 'May':
                        $mes = 'Mayo';
                        break;
                    case 'June':
                        $mes = 'Junio';
                        break;
                    case 'July':
                        $mes = 'Julio';
                        break;
                    case 'August':
                        $mes = 'Agosto';
                        break;
                    case 'September':
                        $mes = 'Septiembre';
                        break;
                    case 'October':
                        $mes = 'Octubre';
                        break;
                    case 'November':
                        $mes = 'Noviembre';
                        break;
                    case 'December':
                        $mes = 'Diciembre';
                        break;
                }
                break;
            case 'M':
                switch ($language) {
                    case 'es':
                        switch ($month) {
                            case 'Jan':
                                $mes = 'Ene';
                                break;
                            case 'Feb':
                                $mes = 'Feb';
                                break;
                            case 'Mar':
                                $mes = 'Mar';
                                break;
                            case 'Apr':
                                $mes = 'Abr';
                                break;
                            case 'May':
                                $mes = 'May';
                                break;
                            case 'Jun':
                                $mes = 'Jun';
                                break;
                            case 'Jul':
                                $mes = 'Jul';
                                break;
                            case 'Aug':
                                $mes = 'Ago';
                                break;
                            case 'Sept':
                                $mes = 'Set';
                                break;
                            case 'Sep':
                                $mes = 'Set';
                                break;
                            case 'Oct':
                                $mes = 'Oct';
                                break;
                            case 'Nov':
                                $mes = 'Nov';
                                break;
                            case 'Dec':
                                $mes = 'Dic';
                                break;
                        }
                        break;
                    case 'en':
                        switch ($month) {
                            case 'Ene':
                                $mes = 'Jan';
                                break;
                            case 'Feb':
                                $mes = 'Feb';
                                break;
                            case 'Mar':
                                $mes = 'Mar';
                                break;
                            case 'Abr':
                                $mes = 'Apr';
                                break;
                            case 'May':
                                $mes = 'May';
                                break;
                            case 'Jun':
                                $mes = 'Jun';
                                break;
                            case 'Jul':
                                $mes = 'Jul';
                                break;
                            case 'Ago':
                                $mes = 'Aug';
                                break;
                            case 'Set':
                                $mes = 'Sept';
                                break;
                            case 'Set':
                                $mes = 'Sep';
                                break;
                            case 'Oct':
                                $mes = 'Oct';
                                break;
                            case 'Nov':
                                $mes = 'Nov';
                                break;
                            case 'Dic':
                                $mes = 'Dec';
                                break;
                        }
                        break;
                }
                break;
        }
        return $mes;
    }

    /**
     * Funcion que retorna un string aleatorio
     * @param string $type ('numerico','alfanumerico','especial')
     * @param int $length
     * @return string
     */
    public function generateRandomString($type, $length = 10) {
        switch ($type) {
            case 'numerico':
                $characters = '0123456789';
                break;
            case 'alfanumerico':
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'especial':
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-{}[],.;¿?!¡';
                break;
        }

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Encrypt and decrypt
     * 
     * @author Nazmul Ahsan <n.mukto@gmail.com>
     * @link http://nazmulahsan.me/simple-two-way-function-encrypt-decrypt-string/
     *
     * @param string $string string to be encrypted/decrypted
     * @param string $action what to do with this? e for encrypt, d for decrypt
     */
    function encrypt($string, $action = 'e') {
        $secret_key = '!@123456789ABCDEFGHIJKLMNOPRSTWYZ[¿]{?}<->';
        $secret_iv = '12345678910AABBCCDDEEFFGG';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if (!empty($string)) {
            if ($action == 'e') {
                $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
            } else if ($action == 'd') {
                $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
            }
        } else {
            $output = '';
        }
        return $output;
    }

    public function simple_php_captcha($config = array()) {
        // Check for GD library
        if (!function_exists('gd_info')) {
            throw new Exception('Required GD library is missing');
        }

        $bg_path = URL . 'public/captcha/backgrounds/';
        $font_path = URL . 'public/captcha/fonts/';
        // Default values
        $captcha_config = array(
            'code' => '',
            'min_length' => 5,
            'max_length' => 5,
            'backgrounds' => array(
                $bg_path . '45-degree-fabric.png',
                $bg_path . 'cloth-alike.png',
                $bg_path . 'grey-sandbag.png',
                $bg_path . 'kinda-jean.png',
                $bg_path . 'polyester-lite.png',
                $bg_path . 'stitched-wool.png',
                $bg_path . 'white-carbon.png',
                $bg_path . 'white-wave.png'
            ),
            'fonts' => array(
                $font_path . 'times_new_yorker.ttf'
            ),
            'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz23456789',
            'min_font_size' => 28,
            'max_font_size' => 28,
            'color' => '#666',
            'angle_min' => 0,
            'angle_max' => 10,
            'shadow' => true,
            'shadow_color' => '#fff',
            'shadow_offset_x' => -1,
            'shadow_offset_y' => 1
        );

        // Overwrite defaults with custom config values
        if (is_array($config)) {
            foreach ($config as $key => $value)
                $captcha_config[$key] = $value;
        }

        // Restrict certain values
        if ($captcha_config['min_length'] < 1)
            $captcha_config['min_length'] = 1;
        if ($captcha_config['angle_min'] < 0)
            $captcha_config['angle_min'] = 0;
        if ($captcha_config['angle_max'] > 10)
            $captcha_config['angle_max'] = 10;
        if ($captcha_config['angle_max'] < $captcha_config['angle_min'])
            $captcha_config['angle_max'] = $captcha_config['angle_min'];
        if ($captcha_config['min_font_size'] < 10)
            $captcha_config['min_font_size'] = 10;
        if ($captcha_config['max_font_size'] < $captcha_config['min_font_size'])
            $captcha_config['max_font_size'] = $captcha_config['min_font_size'];

        // Generate CAPTCHA code if not set by user
        if (empty($captcha_config['code'])) {
            $captcha_config['code'] = '';
            $length = mt_rand($captcha_config['min_length'], $captcha_config['max_length']);
            while (strlen($captcha_config['code']) < $length) {
                $captcha_config['code'] .= substr($captcha_config['characters'], mt_rand() % (strlen($captcha_config['characters'])), 1);
            }
        }

        // Generate HTML for image src
        if (strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT'])) {
            $image_src = substr(__FILE__, strlen(realpath($_SERVER['DOCUMENT_ROOT']))) . '?_CAPTCHA&amp;t=' . urlencode(microtime());
            $image_src = '/' . ltrim(preg_replace('/\\\\/', '/', $image_src), '/');
        } else {
            $_SERVER['WEB_ROOT'] = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME']);
            $image_src = substr(__FILE__, strlen(realpath($_SERVER['WEB_ROOT']))) . '?_CAPTCHA&amp;t=' . urlencode(microtime());
            $image_src = '/' . ltrim(preg_replace('/\\\\/', '/', $image_src), '/');
        }

        $_SESSION['_CAPTCHA']['config'] = serialize($captcha_config);

        return array(
            'code' => $captcha_config['code'],
            'image_src' => $image_src
        );
    }

    private function hex2rgb($hex_str, $return_string = false, $separator = ',') {
        $hex_str = preg_replace("/[^0-9A-Fa-f]/", '', $hex_str); // Gets a proper hex string
        $rgb_array = array();
        if (strlen($hex_str) == 6) {
            $color_val = hexdec($hex_str);
            $rgb_array['r'] = 0xFF & ($color_val >> 0x10);
            $rgb_array['g'] = 0xFF & ($color_val >> 0x8);
            $rgb_array['b'] = 0xFF & $color_val;
        } elseif (strlen($hex_str) == 3) {
            $rgb_array['r'] = hexdec(str_repeat(substr($hex_str, 0, 1), 2));
            $rgb_array['g'] = hexdec(str_repeat(substr($hex_str, 1, 1), 2));
            $rgb_array['b'] = hexdec(str_repeat(substr($hex_str, 2, 1), 2));
        } else {
            return false;
        }
        return $return_string ? implode($separator, $rgb_array) : $rgb_array;
    }

    /**
     * Funcion que obtiene el host desde donde se este ejecutando el script
     * @return string
     */
    public function getHost() {
        $host = $_SERVER['HTTP_HOST'];
        return $host;
    }

    /*     * ***************************
     * FUNCIONES DEL INERENTES AL SITIO
     * ****************************** */

    public function getWebData() {
        $sql = $this->db->select("select * from web_data where id = 1");
        return $sql[0];
    }

    public function getWebRedes() {
        $sql = $this->db->select("select descripcion, enlace, fa_style from web_redes where estado = 1");
        return $sql;
    }

    public function getCurrentPageMenu($page) {
        $directores = "";
        $fondo_mutuo = "";
        $preguntas_frecuentes = "";
        $fondos = "";
        $contacto = "";
        $clientes = "";
        $inicio = "";
        switch ($page) {
            case 'directores':
                $directores = 'active';
                break;
            case 'fondos':
                $fondos = 'active';
                break;
            case 'preguntas_frecuentes':
                $preguntas_frecuentes = 'active';
                break;
            case 'contacto':
                $contacto = 'active';
                break;
            case 'clientes':
                $clientes = 'active';
                break;
            default :
                $inicio = 'active';
                break;
        }
        $data = array(
            'directores' => $directores,
            'fondo_mutuo' => $fondos,
            'fondos' => $fondos,
            'preguntas_frecuentes' => $preguntas_frecuentes,
            'contacto' => $contacto,
            'clientes' => $clientes,
            'inicio' => $inicio
        );
        return $data;
    }

    public function youAreHere($page) {
        $seccion = '';
        $pagina = '';
        switch ($page) {
            case 'directores':
                $seccion = 'Directores';
                break;
            case 'fondo_mutuo':
                $seccion = 'Fondo Mutuo';
                break;
            case 'fondos':
                $url = $this->getUrl();
                switch ($url[1]) {
                    case 'mutuo':
                        $seccion = 'Fondo Mutuo';
                        break;
                    case 'crecimiento':
                        $seccion = 'Fondo Crecimiento';
                        break;
                }
                break;
            case 'preguntas_frecuentes':
                $seccion = 'Preguntas Frecuentes';
                break;
            case 'clientes':
                $seccion = 'Clientes';
                $url = $this->getUrl();
                $enlace = URL . 'clientes/dashboard';
                switch ($url[1]) {
                    case 'dashboard':
                        $pagina = 'Cuentas';
                        break;
                    case 'extracto':
                        $pagina = 'Extracto';
                        break;
                    case 'rescate':
                        $pagina = 'Orden de Rescate';
                        break;
                    case 'inversiones':
                        $pagina = 'Orden de Inversión';
                        break;
                    case 'documentos':
                        $pagina = 'Documentos';
                        break;
                }
                break;
            case 'contacto':
                $seccion = 'Contacto';
                break;
        }
        if (empty($pagina)) {
            $data = '<div class="col-sm-6 breadcrumb-block text-right">
                <ol class="breadcrumb">
                    <li><span>Estas aquí:  </span><a href="' . URL . '">Inicio</a></li>
                    <li class="active">' . $seccion . '</li>
                </ol>
            </div>';
        } else {
            $data = '<div class="col-sm-6 breadcrumb-block text-right">
                <ol class="breadcrumb">
                    <li><span>Estas aquí:  </span><a href="' . URL . '">Inicio</a></li>
                    <li><a href="' . $enlace . '">' . $seccion . '</a></li>
                    <li class="active">' . $pagina . '</li>
                </ol>
            </div>';
        }
        return $data;
    }

    public function getWebFondoCaracteristica($fondo, $order_columna, $order_tipo) {
        $sql = $this->db->select("select * from $fondo where estado = 1 ORDER BY $order_columna $order_tipo");
        return $sql;
    }

    public function getWebTestimonios() {
        $sql = $this->db->select("select nombre, testimonio, imagen, puesto from web_testimonios where estado = 1");
        return $sql;
    }

    public function getHomeSlider() {
        $sql = $this->db->select("select * from web_slider where estado = 1 order by orden ASC");
        return $sql;
    }

    public function getTextPositionSlider($posicion, $tipo, $speed = 800) {
        switch ($posicion) {
            case'centro':
                switch ($tipo) {
                    case 'texto1':
                        $etiquetas = 'data-x="264" data-y="198" data-speed="800" data-start="1300"';
                        break;
                    case 'texto2':
                        $etiquetas = 'data-x="331" data-y="236" data-speed="800" data-start="1900"';
                        break;
                    case 'boton':
                        $etiquetas = 'data-x="355" data-y="331" data-speed="800" data-start="2700"';
                        break;
                }
                break;
            case'derecha':
                switch ($tipo) {
                    case 'texto1':
                        $etiquetas = 'data-x="441" data-y="225" data-speed="800" data-start="1500"';
                        break;
                    case 'texto2':
                        $etiquetas = 'data-x="439" data-y="267" data-speed="800" data-start="1750"';
                        break;
                    case 'boton':
                        $etiquetas = 'data-x="441" data-y="327" data-speed="800" data-start="2500"';
                        break;
                }
                break;
            case'izquierda':
                switch ($tipo) {
                    case 'texto1':
                        $etiquetas = 'data-x="-19" data-y="222" data-speed="800" data-start="800"';
                        break;
                    case 'texto2':
                        $etiquetas = 'data-x="-19" data-y="264" data-speed="800" data-start="1300"';
                        break;
                    case 'boton':
                        $etiquetas = 'data-x="-17" data-y="324" data-speed="800" data-start="2200"';
                        break;
                }
                break;
        }
        return $etiquetas;
    }

    public function getRendimientoPromedio() {
        $sql = $this->db->select("SELECT
	IFNULL(
		(
			(
				(
					SELECT
						valor_cuota_hoy
					FROM
						cartera_historico
					WHERE
						fecha = SUBDATE(CURRENT_DATE, INTERVAL 1 DAY)
                                                AND id_fondo = 1
				) - (
					SELECT
						valor_cuota_hoy
					FROM
						cartera_historico
					WHERE
						fecha = SUBDATE(
							SUBDATE(CURRENT_DATE, INTERVAL 1 DAY),
							INTERVAL 30 DAY
						) 
                                                AND id_fondo = 1
				)
			) / (
				SELECT
					valor_cuota_hoy
				FROM
					cartera_historico
				WHERE
					fecha = SUBDATE(
						SUBDATE(CURRENT_DATE, INTERVAL 1 DAY),
						INTERVAL 30 DAY
					)
                                        AND id_fondo = 1
			)
		) / 30 * 365,
		0
	) AS rentabilidad_promedio_30");
        $calculoPorcentaje = $sql[0]['rentabilidad_promedio_30'];
        $data = array(
            'mascara' => number_format($calculoPorcentaje * 100, 2, ',', '.'),
            'valor' => $calculoPorcentaje
        );
        return $data;
    }

    public function getDatosInico() {
        $data = array(
            'titulo_que_es_un_fondo' => '',
            'que_es_un_fondo' => '',
            'titulo_sabias_que' => '',
            'sabias_que' => '',
            'video_institucional' => '',
            'texto_video' => '',
            'titulo_para_que_sirve' => '',
            'para_que_sirve' => '',
            'titulo_requisitos_para_invertir' => '',
            'requisitos_para_invertir' => '',
            'titulo_como_invertir' => '',
            'como_invertir' => '',
            'titulo_quieres_ser_cliente' => '',
            'quieres_ser_cliente' => ''
        );
        $sqlInicio = $this->db->select("select * from web_inicio where id = 1");
        $sqlRequisitos = $this->db->select("select * from web_inicio_requisitos where estado = 1");
        $sqlSabias = $this->db->select("select * from web_inicio_sabias where estado = 1");
        $data['titulo_que_es_un_fondo'] = utf8_encode($sqlInicio[0]['titulo_que_es_un_fondo']);
        $data['que_es_un_fondo'] = utf8_encode($sqlInicio[0]['que_es_un_fondo']);
        $data['titulo_sabias_que'] = utf8_encode($sqlInicio[0]['titulo_sabias_que']);
        $data['sabias_que'] = $sqlSabias;
        $data['video_institucional'] = utf8_encode($sqlInicio[0]['video_institucional']);
        $data['texto_video'] = utf8_encode($sqlInicio[0]['texto_video']);
        $data['titulo_para_que_sirve'] = utf8_encode($sqlInicio[0]['titulo_para_que_sirve']);
        $data['para_que_sirve'] = utf8_encode($sqlInicio[0]['para_que_sirve']);
        $data['titulo_requisitos_para_invertir'] = utf8_encode($sqlInicio[0]['titulo_requisitos_para_invertir']);
        $data['requisitos_para_invertir'] = $sqlRequisitos;
        $data['titulo_como_invertir'] = utf8_encode($sqlInicio[0]['titulo_como_invertir']);
        $data['como_invertir'] = utf8_encode($sqlInicio[0]['como_invertir']);
        $data['titulo_quieres_ser_cliente'] = utf8_encode($sqlInicio[0]['titulo_quieres_ser_cliente']);
        $data['quieres_ser_cliente'] = utf8_encode($sqlInicio[0]['quieres_ser_cliente']);
        return $data;
    }

    public function getDatosDirecciones() {
        $sql = $this->db->select("select * from web_direcciones where id = 1");
        return $sql[0];
    }

    public function getLogos() {
        $sql = $this->db->select("select * from web_logos where id = 1");
        return $sql[0];
    }

    public function getReglamentos($idFondo) {
        $sql = $this->db->select("select id, reglamento from web_reglamentos where id_fondo = $idFondo and estado = 1");
        return $sql[0];
    }

    public function getFichaTecnica($idFondo) {
        $sql = $this->db->select("select id, documento from web_documentos where id_fondo = $idFondo and estado = 1");
        return $sql[0];
    }

    /**
     * Función que arma el cuerpo del mail con un formato predefinido
     * @param string $body
     * @return string
     */
    public function mailTemplate($body) {
        $logos = $this->getLogos();
        $direcciones = $this->getDatosDirecciones();
        $data = '<table border="0" style="width: 600px; margin: 0 auto; border-collapse: collapse; border-spacing: 0;">
                        <thead style="background: #fff;">
                            <tr>
                                <th><img src="' . URL . 'public/images/' . utf8_encode($logos['logo_cabecera']) . '" style="width: 220px; padding: 15px;"></th>
                            </tr>
                        </thead>
                        <tbody style="background: #F8F8F8;">
                            ' . $body . '
                        </tbody>
                        <tfoot style="background: #1A1E21; color: #9e9e9e;">
                            <tr>
                                <tr>
                                    <td><img src="' . URL . 'public/images/' . utf8_encode($logos['logo_pie']) . '" alt="Cadiem" style="width: 120px; padding: 15px;"></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 25px;">atc@cadiemfondos.com.py</td>
                                </tr>
                                <!--<tr>
                                    <td style="padding: 5px 25px;">' . utf8_encode($direcciones['email']) . '</td>
                                </tr>-->
                                <tr>
                                    <td style="padding: 5px 25px;">' . utf8_encode($direcciones['telefono']) . '</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 25px;">' . utf8_encode($direcciones['direccion']) . '<br>' . utf8_encode($direcciones['ciudad']) . '</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                             </tr>
                        </tfoot>
                    </table>';
        return $data;
    }

    /**
     * Funcion que obtiene las cuentas asociadas a un cliente y/o apoderado
     * @param string $tipo (cliente,apoderado)
     * @param int $id_fondo
     * @return array
     */
    public function cuentasCliente($tipo, $id_fondo) {
        $documento = $_SESSION['loggedIn']['documento'];
        $data = array();
        $tabla = ($tipo == 'cliente') ? 'cuentas_clientes' : 'cuentas_apoderados';
        $participe = ($tipo == 'cliente') ? 1 : 0;
        #obtenemos las cuentas
        $sqlCuentas = $this->db->select("select cc.id_cuenta,
                                                cc.id_fondo,
                                                c.nombre,
                                                c.tipo,
                                                im.saldo_cuotas,
                                                (im.saldo_cuotas * cart.valor_cuota_hoy) as saldo_monto
                                        from $tabla cc
                                        LEFT JOIN cuentas c on c.id_cuenta = cc.id_cuenta
                                                            and c.id_fondo = cc.id_fondo
                                        LEFT JOIN inversiones_maestro im on im.id_cuenta = cc.id_cuenta
                                                            and im.id_fondo = cc.id_fondo
                                        LEFT JOIN cartera cart on cart.id_fondo = cc.id_fondo
                                        WHERE cc.documento = '$documento' 
                                        and cc.id_fondo = $id_fondo
                                        order by cc.id_cuenta ASC;");
        foreach ($sqlCuentas as $cuenta) {
            array_push($data, array(
                'id_cuenta' => $cuenta['id_cuenta'],
                'id_fondo' => $cuenta['id_fondo'],
                'nombre' => $this->encrypt($cuenta['nombre'], 'd'),
                'tipo' => $cuenta['tipo'],
                'saldo_cuotas' => $cuenta['saldo_cuotas'],
                'saldo_monto' => $cuenta['saldo_monto'],
                'participe' => $participe
            ));
        }
        return $data;
    }

    public function getApoderadoPermisos($id_cuenta, $id_fondo) {
        $documento = $_SESSION['loggedIn']['documento'];
        $sql = $this->db->select("SELECT UPPER(ca.permiso_todo) as permiso_todo, 
                                        UPPER(ca.permiso_extracto) as permiso_extracto, 
                                        UPPER(ca.permiso_inversion) as permiso_inversion, 
                                        UPPER(ca.permiso_rescate) as permiso_rescate 
                                FROM cuentas_apoderados ca 
                                where ca.documento = '$documento'
                                and ca.id_cuenta = $id_cuenta
                                and ca.id_fondo = $id_fondo");
        return $sql[0];
    }

    public function loginCorrecto($documento, $atc = FALSE) {
        #actualizamos el campo ultima visita
        $update = array(
            'ultimaVisita' => date('Y-m-d H:i:s')
        );
        $this->db->update('clientes', $update, "documento = '$documento'");
        $sql = $this->db->select("select id_cliente,documento, nombre, email from clientes where TRIM(documento) = '$documento'");
        $data = array(
            'id_cliente' => $sql[0]['id_cliente'],
            'documento' => $documento,
            'nombre' => $this->encrypt($sql[0]['nombre'], 'd'),
            'email' => $this->encrypt($sql[0]['email'], 'd'),
            'id_cuenta' => '',
            'id_fondo' => '',
            'apoderado' => FALSE,
            'atc' => $atc
        );
        #Inicializamos la sesion del cliente con sus datos
        Session::set('loggedIn', $data);
    }

    public function getFondos() {
        $sql = $this->db->select("select * from web_fondos where estado = 1");
        return $sql;
    }

    public function bcpowfact($x, $n) {
        if (bccomp($n, '0') == 0)
            return '1.0';
        if (bccomp($n, '1') == 1)
            return $x;
        $a = $x; // nth step: a *= x / 1
        $i = $n;
        while (bccomp($i, '1') == 1) {
            // ith step: a *= x / i
            $a = bcmul($a, bcdiv($x, $i));
            $i = bcsub($i, '1'); // bc idiom for $i--
        }
        return $a;
    }

    public function potencia($numero, $exponente) {
        $numeroo = 1;
        for ($i = 0; $i < $exponente; $i++) {
            $numeroo .= ($numeroo * $numero);
        }
        return $numeroo;
    }

    public function informacionFinanciera() {
        $sql = $this->db->select("SELECT * FROM `web_informacion_financiera` where estado = 1 LIMIT 1");
        return $sql[0];
    }

    public function premios() {
        $sql = $this->db->select("select * from web_crecimiento_premios where estado = 1 ORDER BY orden ASC;");
        return $sql;
    }

    public function getListadoPreguntas($idCategoria) {
        $sql = $this->db->select("select * from web_preguntas_frecuentes where id_categoria_pregunta_frecuente = $idCategoria and estado = 1");
        return $sql;
    }

    public function sendPostData($rutaWS, $fields) {
        //extract data from the post
        //set POST variables
        $fields_string = '';
        $url = $rutaWS;
        //url-ify the data for the POST
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }

        rtrim($fields_string, '&');
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);
    }

    public function verificaVista() {
        $id_cuenta = $_SESSION['loggedIn']['id_cuenta'];
        $id_fondo = $_SESSION['loggedIn']['id_fondo'];
        $participe = $_SESSION['loggedIn']['participe'];
        $data = array(
            'vista_extracto' => FALSE,
            'vista_inversion' => FALSE,
            'vista_rescate' => FALSE,
        );
        if ($participe == 1) {
            $data = array(
                'vista_extracto' => TRUE,
                'vista_inversion' => TRUE,
                'vista_rescate' => TRUE,
            );
        } else {
            $permisos = $this->getApoderadoPermisos($id_cuenta, $id_fondo);
            if (($permisos['permiso_extracto'] == 'S') || ($permisos['permiso_todo'] == 'S')) {
                $data['vista_extracto'] = TRUE;
            }
            if (($permisos['permiso_inversion'] == 'S') || ($permisos['permiso_todo'] == 'S')) {
                $data['vista_inversion'] = TRUE;
            }
            if (($permisos['permiso_rescate'] == 'S') || ($permisos['permiso_todo'] == 'S')) {
                $data['vista_rescate'] = TRUE;
            }
        }
        return $data;
    }

    public function validaOficina() {
        $ip = $this->getReal_ip();
        $oficina = FALSE;
        $ip_permitidas = array(
            '181.40.124.94',
            '192.168.1.89',
            '192.168.1.84'
        );
        if (in_array($ip, $ip_permitidas)) {
            $oficina = TRUE;
        }
        return $oficina;
    }

}
