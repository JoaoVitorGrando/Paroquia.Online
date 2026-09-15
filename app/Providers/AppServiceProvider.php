<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->forcarHttpsEmProducao();
    }

    /*
     * Em hospedagem compartilhada o certificado HTTPS costuma ficar em um
     * servidor na frente do site. O Laravel recebe a requisicao como http e,
     * sem este ajuste, gera os enderecos das imagens e do CSS com http.
     * O navegador bloqueia esse conteudo misto e a pagina abre sem estilo.
     *
     * A regra so vale quando o APP_URL configurado ja e https, entao o
     * ambiente local em http://localhost continua funcionando normalmente.
     */
    private function forcarHttpsEmProducao(): void
    {
        $url = (string) config('app.url');

        if (str_starts_with($url, 'https://')) {
            URL::forceScheme('https');
        }
    }
}
