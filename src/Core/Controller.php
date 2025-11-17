<?php

namespace Factapex\Core;

class Controller {
    
    /**
     * Renderizar una vista con o sin layout
     */
    protected function render($view, $data = []) {
        // Extraer datos para usar en la vista
        extract($data);
        
        // Determinar el archivo de vista
        $viewFile = __DIR__ . '/../../views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            die("Vista no encontrada: {$view}");
        }

        // Vistas que NO necesitan layout (ya tienen header y footer incluidos)
        $noLayoutViews = ['auth/login', 'auth/register', 'auth/recover-password', 'home/index'];
        
        if (in_array($view, $noLayoutViews)) {
            // Renderizar solo la vista (ya tiene su propio HTML completo)
            require $viewFile;
        } else {
            // Renderizar con layout (main.php con sidebar)
            $content = '';
            ob_start();
            require $viewFile;
            $content = ob_get_clean();
            
            require __DIR__ . '/../../views/layouts/main.php';
        }
    }
}
