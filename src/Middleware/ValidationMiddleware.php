<?php

namespace Factapex\Middleware;

use Factapex\Core\Middleware;

class ValidationMiddleware extends Middleware {
    
    private $rules;

    public function __construct($rules = []) {
        $this->rules = $rules;
    }

    public function handle($request) {
        $errors = [];

        foreach ($this->rules as $field => $ruleset) {
            $value = $_POST[$field] ?? null;
            $rules = explode('|', $ruleset);

            foreach ($rules as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $errors[$field] = "El campo $field es requerido";
                }

                if (strpos($rule, 'min:') === 0) {
                    $min = (int) substr($rule, 4);
                    if (strlen($value) < $min) {
                        $errors[$field] = "El campo $field debe tener al menos $min caracteres";
                    }
                }

                if (strpos($rule, 'max:') === 0) {
                    $max = (int) substr($rule, 4);
                    if (strlen($value) > $max) {
                        $errors[$field] = "El campo $field no debe exceder $max caracteres";
                    }
                }

                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "El campo $field debe ser un email válido";
                }

                if ($rule === 'numeric' && !is_numeric($value)) {
                    $errors[$field] = "El campo $field debe ser numérico";
                }
            }
        }

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'errors' => $errors
            ]);
            exit;
        }

        if ($this->next) {
            return $this->next->handle($request);
        }

        return true;
    }
}