<?php

class Controller {

    public $helper = '';
    public $idioma = '';

    function __construct() {
        //echo 'Main controller<br />';
        $this->view = new View();
        $this->helper = new Helper;
        $this->idioma = $this->helper->getUrl()[0];
    }

    /**
     * 
     * @param string $name Name of the model
     * @param string $path Location of the models
     */
    public function loadModel($name, $modelPath = 'models/') {

        $path = $modelPath . $name . '_model.php';

        if (file_exists($path)) {
            require $modelPath . $name . '_model.php';

            $modelName = $name . '_Model';
            $this->model = new $modelName();
        }
    }

}
