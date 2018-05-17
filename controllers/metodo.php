<?php

class Metodo extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    public function index(){
        var_dump($this->helper->getUrl());
    }
    
    public function hola(){
        var_dump($this->helper->getUrl());
        echo $this->model->saludo();
    }
}