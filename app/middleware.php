<?php

use Slim\App;

return function (App $app) {
    // Analisa dados JSON, formulários e XML
    $app->addBodyParsingMiddleware();

    // Adiciona o middleware de roteamento interno do aplicativo Slim
    $app->addRoutingMiddleware();

    // Trata exceções
    $app->addErrorMiddleware(true, true, true);
};