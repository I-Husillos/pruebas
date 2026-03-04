<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Dba\DddSkeleton\Shared\Infrastructure\Laravel\ApiController as BaseApiController;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Termosalud API",
    description: "API RESTful para la plataforma global multi-mercado de equipamiento médico estético"
)]
#[OA\Server(
    url: "/",
    description: "Servidor API"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "Autenticación mediante token JWT de Laravel Passport"
)]
class ApiController extends BaseApiController
{
    #[OA\Get(
        path: "/api/health-check",
        tags: ["Health check"],
        summary: "Health check"
    )]
    #[OA\Response(
        response: 200,
        description: "OK"
    )]
    public function health() {}
}
