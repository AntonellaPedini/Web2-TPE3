<?php

require_once __DIR__ . '/jwt.php';

class JWTMiddleware extends Middleware {
    public function run($request, $response) {
        $resource = $_GET['resource'] ?? '';
        $rutasPublicas = ['login'];

        if (in_array($resource, $rutasPublicas)) {
            return;
        }

        $metodosProtegidos = ['POST', 'PUT'];
        $metodoActual = $_SERVER['REQUEST_METHOD'];

        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $auth_header = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        $auth_header = explode(' ', $auth_header);

        $user = null;
        if (count($auth_header) == 2 && $auth_header[0] == 'Bearer') {
            $user = validateJWT($auth_header[1]);
        }

        $request->user = $user;

        if (in_array($metodoActual, $metodosProtegidos) && $user === null) {
            $response->json("Token inválido o no enviado", 401);
        }
    }
}