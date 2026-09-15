<?php

/*
 * O site nao expoe API e nao e consumido por outro dominio, entao nao ha
 * nenhuma rota que precise de CORS. A lista de caminhos fica vazia de
 * proposito: e o ajuste mais restritivo possivel.
 */

return [
    'paths' => [],
    'allowed_methods' => [],
    'allowed_origins' => [],
    'allowed_origins_patterns' => [],
    'allowed_headers' => [],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
