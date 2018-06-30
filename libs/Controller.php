<?php

class Controller {

    public $helper = '';
    public $idioma = '';

    function __construct() {
        //echo 'Main controller<br />';
        $this->view = new View();
        $this->helper = new Helper;
        $lng = 'en';
        if (!empty($this->helper->getUrl()[0])) {
            if ($this->helper->getUrl()[0] != 'index.php') {
                $lng = $this->helper->getUrl()[0];
            }
        }
        $this->idioma = $lng;
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
