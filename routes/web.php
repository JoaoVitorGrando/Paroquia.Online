<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MissaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\VoluntarioController;
use App\Http\Controllers\AvisoController;
use App\Http\Controllers\AdminController;

// Página inicial
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sobre e Contato
Route::get('/sobre', [HomeController::class, 'sobre'])->name('sobre');
Route::get('/contato', [HomeController::class, 'contato'])->name('contato');
Route::post('/contato', [HomeController::class, 'contatoEnviar'])->name('contato.enviar');

// Catequese e Sacramentos
Route::get('/catequese', [HomeController::class, 'catequese'])->name('catequese');
Route::get('/sacramentos', [HomeController::class, 'sacramentos'])->name('sacramentos');

// US001 - Horários de Missas
Route::get('/missas', [MissaController::class, 'index'])->name('missas.index');

// US002 - Eventos e Festas
Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');

// Avisos
Route::get('/avisos', [AvisoController::class, 'index'])->name('avisos.index');

// US007 - Grupos (público)
Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos.index');
// Página informativa do Grupo de Dança Folclórica (pública, sem login)
Route::get('/grupos/danca', [GrupoController::class, 'danca'])->name('grupos.danca');

// US003 - Cadastro
Route::get('/cadastro', [AuthController::class, 'showCadastro'])->name('cadastro.form');
Route::post('/cadastro', [AuthController::class, 'cadastrar'])->name('cadastro.store');

// US004 - Login / Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas autenticadas
Route::middleware('auth')->group(function () {
    // US005 - Inscrição em grupos
    Route::post('/grupos/{id}/inscrever', [GrupoController::class, 'inscrever'])->name('grupos.inscrever');
    Route::post('/grupos/{id}/cancelar', [GrupoController::class, 'cancelar'])->name('grupos.cancelar');

    // US008 - Voluntariado em eventos
    Route::post('/eventos/{id}/voluntario', [VoluntarioController::class, 'inscrever'])->name('voluntario.inscrever');
    Route::post('/eventos/{id}/voluntario/cancelar', [VoluntarioController::class, 'cancelar'])->name('voluntario.cancelar');
});

// Rotas admin - US006
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::get('/eventos', [AdminController::class, 'eventos'])->name('eventos');
    Route::get('/eventos/criar', [AdminController::class, 'criarEvento'])->name('eventos.criar');
    Route::post('/eventos', [AdminController::class, 'salvarEvento'])->name('eventos.salvar');
    Route::get('/eventos/{id}/editar', [AdminController::class, 'editarEvento'])->name('eventos.editar');
    Route::put('/eventos/{id}', [AdminController::class, 'atualizarEvento'])->name('eventos.atualizar');
    Route::delete('/eventos/{id}', [AdminController::class, 'excluirEvento'])->name('eventos.excluir');
    Route::get('/eventos/{id}/voluntarios', [AdminController::class, 'voluntariosEvento'])->name('eventos.voluntarios');

    Route::get('/avisos', [AdminController::class, 'avisos'])->name('avisos');
    Route::get('/avisos/criar', [AdminController::class, 'criarAviso'])->name('avisos.criar');
    Route::post('/avisos', [AdminController::class, 'salvarAviso'])->name('avisos.salvar');
    Route::get('/avisos/{id}/editar', [AdminController::class, 'editarAviso'])->name('avisos.editar');
    Route::put('/avisos/{id}', [AdminController::class, 'atualizarAviso'])->name('avisos.atualizar');
    Route::delete('/avisos/{id}', [AdminController::class, 'excluirAviso'])->name('avisos.excluir');

    // US013 - Gerenciamento de Grupos (Sprint 3)
    Route::get('/grupos', [AdminController::class, 'grupos'])->name('grupos');
    Route::get('/grupos/criar', [AdminController::class, 'criarGrupo'])->name('grupos.criar');
    Route::post('/grupos', [AdminController::class, 'salvarGrupo'])->name('grupos.salvar');
    Route::get('/grupos/{id}/editar', [AdminController::class, 'editarGrupo'])->name('grupos.editar');
    Route::put('/grupos/{id}', [AdminController::class, 'atualizarGrupo'])->name('grupos.atualizar');
    Route::patch('/grupos/{id}/alternar', [AdminController::class, 'alternarGrupo'])->name('grupos.alternar');
    Route::delete('/grupos/{id}', [AdminController::class, 'excluirGrupo'])->name('grupos.excluir');
    Route::get('/grupos/{id}/inscritos', [AdminController::class, 'inscritosGrupo'])->name('grupos.inscritos');

    // US014 - Gerenciamento de Missas (Sprint 3)
    Route::get('/missas', [AdminController::class, 'missas'])->name('missas');
    Route::get('/missas/criar', [AdminController::class, 'criarMissa'])->name('missas.criar');
    Route::post('/missas', [AdminController::class, 'salvarMissa'])->name('missas.salvar');
    Route::get('/missas/{id}/editar', [AdminController::class, 'editarMissa'])->name('missas.editar');
    Route::put('/missas/{id}', [AdminController::class, 'atualizarMissa'])->name('missas.atualizar');
    Route::patch('/missas/{id}/alternar', [AdminController::class, 'alternarMissa'])->name('missas.alternar');
    Route::delete('/missas/{id}', [AdminController::class, 'excluirMissa'])->name('missas.excluir');
});
