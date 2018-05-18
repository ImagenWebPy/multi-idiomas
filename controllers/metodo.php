<?php

class Metodo extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->view->idioma = $this->idioma;
        $this->view->title = SITE_TITLE . 'Metodo';
        $this->view->description = '';
        $this->view->keywords = '';
        $this->view->render('header');
        $this->view->render('metodo/index');
        $this->view->render('footer');
    }
    
    public function hola(){
        $this->view->idioma = $this->idioma;
        $this->view->title = SITE_TITLE . 'Hola';
        $this->view->description = '';
        $this->view->keywords = '';
        $this->view->render('header');
        $this->view->render('metodo/hola');
        $this->view->render('footer');
    }
}