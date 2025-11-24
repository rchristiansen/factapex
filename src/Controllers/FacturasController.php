<?php

namespace Factapex\Controllers;

use Factapex\Core\Controller;

class FacturasController extends Controller {
    
    public function index() {
        $this->render('partials/facturas', [
            'title' => 'Facturas - Factapex'
        ]);
    }
}