<?php

namespace Factapex\Core;

class View
{
    public static function render($view, $data = [], $layout = 'main')
    {
        // Agregar base path automáticamente a todas las vistas
        $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        $basePath = rtrim($basePath, '/');
        $data['basePath'] = $basePath;
        
        extract($data);
        
        $viewFile = __DIR__ . '/../../views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            die("Vista no encontrada: {$view}");
        }

        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // Si layout es null, solo devolver el contenido (para vistas parciales)
        if ($layout === null) {
            echo $content;
            return;
        }

        $layoutFile = __DIR__ . '/../../views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            echo $content;
        }
    }

    // public static function partial($partial, $data = [])
    // {
    //     // Agregar base path automáticamente a las vistas parciales
    //     $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
    //     $basePath = rtrim($basePath, '/');
    //     $data['basePath'] = $basePath;
        
    //     extract($data);
    //     $partialFile = __DIR__ . '/../../views/partials/' . $partial . '.php';
    //     if (file_exists($partialFile)) {
    //         include $partialFile;
    //     }
    // }

    // public static function json($data, $statusCode = 200)
    // {
    //     http_response_code($statusCode);
    //     header('Content-Type: application/json');
    //     echo json_encode($data);
    //     exit;
    // }
}

