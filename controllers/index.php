<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->view->idioma = $this->idioma;
        $this->view->title = SITE_TITLE . 'Inicio';
        $this->view->description = '';
        $this->view->keywords = '';
        $this->view->render('header');
        $this->view->render('index/index');
        $this->view->render('footer');
        
    }

}
