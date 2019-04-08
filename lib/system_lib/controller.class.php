<?php

class Controller {

    protected $shablon; // Название шаблона для контроллера
    protected $model;   // Название класса модели контроллера
    protected $data = array();	  // Входные данные конроллера
    protected $params;  // Входные параметры конроллера

    public function __construct($data, $params) {

        $this->data = $data;
        $this->params = $params;

        $modelName = App::getRouter()->getController() . 'Model';
        $this->model = new $modelName();

        $shablonName = App::getRouter()->getController();
        $this->shablon = strtolower($shablonName . '.html.twig');
    }


}