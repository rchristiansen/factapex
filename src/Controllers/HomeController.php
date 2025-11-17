<?php

namespace Factapex\Controllers;

use Factapex\Core\Controller;

class HomeController extends Controller {
    
    public function index() {
        $this->render('home/index', [
            'title' => 'Inicio - Factapex'
        ]);
    }
}