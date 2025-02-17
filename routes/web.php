<?php

use Core\Router;

// Página inicial
Router::get('/', 'HomeController@index');

// Página de projetos
Router::get('/projetos', 'ProjetoController@index');
Router::get('/projeto/criar', 'ProjetoController@criar');
Router::post('/projeto/salvar', 'ProjetoController@salvar');
Router::get('/projeto/editar/{id}', 'ProjetoController@editar');
Router::post('/projeto/apagar/{id}', 'ProjetoController@apagar');
Router::post('/projeto/participar/{id}', 'ProjetoController@participar');
Router::post('/projeto/alterar_status/{id}/{usuario_id}', 'ProjetoController@alterar_status');
Router::post('/projeto/adicionar_relatorio/{id}', 'ProjetoController@adicionarRelatorio');


// Página de projeto específico (modelo)
Router::get('projeto/{id}', 'ProjetoController@show');

// Página sobre o site
Router::get('/sobre', 'SobreController@index');

// Página de login e cadastro
Router::get('/login', 'AuthController@showLoginForm');
Router::post('/login', 'AuthController@login');
Router::get('/cadastro', 'AuthController@showRegisterForm');
Router::post('/cadastro', 'AuthController@register');

// Logout
Router::get('/logout', 'AuthController@logout');

Router::get('/usuario', 'UsuarioController@perfil');

Router::get('/admin/usuarios', 'UsuarioController@salvarUsuarios');
Router::post('/admin/salvarAlteracoes', 'UsuarioController@salvarAlteracoes');