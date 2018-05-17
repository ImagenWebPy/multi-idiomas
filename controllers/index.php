<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
        //echo Hash::create('sha256', '123456', HASH_PASSWORD_KEY);
    }

    public function index() {
        //echo Hash::create('sha256', 'PassdeAli93', HASH_PASSWORD_KEY);
        $this->view->title = SITE_TITLE . 'Inicio';
        $this->view->description = 'Fondo mutuo disponible renta fija en Guaraníes.';
        $this->view->keywords = 'cadiem, fondos patrimoniales, fondo mutuo';
        $this->view->public_js = array("js/big.js");
        $this->view->render('header');
        $this->view->render('index/index');
        $this->view->render('footer');
    }

}
