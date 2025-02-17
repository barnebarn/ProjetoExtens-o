<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

if (!defined('APP_NOME')) {
    define('APP_NOME', 'Projif');
}

if (!defined('URL')) {
    define('URL', 'http://localhost/ProjetoExtensão/');
}
if (!defined('DB_HOST')) define('DB_HOST', '127.0.0.1');
if (!defined('DB_NAME')) define('DB_NAME', 'projif');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');

// Carrega variáveis de ambiente do arquivo .env (caso exista)
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

return [
    'db_host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'db_name' => $_ENV['DB_NAME'] ?? 'projif',
    'db_user' => $_ENV['DB_USER'] ?? 'root',
    'db_pass' => $_ENV['DB_PASS'] ?? '',
    'base_url' => $_ENV['BASE_URL'] ?? 'http://localhost:8000'
];






