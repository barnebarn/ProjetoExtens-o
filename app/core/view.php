<?php

namespace Core;

class View
{
    public static function render($view, $data = [])
    {
        extract($data);

        $file = __DIR__ . "/../views/$view.php"; // Caminho absoluto

        if (file_exists($file)) {
            include $file;
        } else {
            die("Erro: A view <strong>$view.php</strong> não foi encontrada em <strong>$file</strong>");
        }
    }
}
    