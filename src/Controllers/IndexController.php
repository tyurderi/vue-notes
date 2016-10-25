<?php

namespace Notes\Controllers;

use Favez\Mvc\Controller;

class IndexController extends Controller
{

    public function indexAction()
    {
        return $this->view()->render('index/index');
    }

}