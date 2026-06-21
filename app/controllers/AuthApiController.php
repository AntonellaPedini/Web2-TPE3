<?php
require_once __DIR__ . '/../models/usuarios.model.php';

class AuthApiController {
    private $model;

    public function __construct() {
        $this->model = new usuariosModel();
    }

    public function login($req, $res) {
        $email = $req->body->email ?? null;
        $password = $req->body->password ?? null;

        if (empty($email) || empty($password)) {
            return $res->json("Faltan email o contraseña", 400);
        }

        $usuario = $this->model->getByEmail($email);

        if (!$usuario || !password_verify($password, $usuario->password)) { 
            return $res->json("Email o contraseña incorrectos", 401);
        }

        $token = createJWT ([
            "id" => $usuario->id,
            "email" => $usuario->email,
            "exp" => time() + 3600 // el token expira en 1 hora
        ]);

        return $res->json(["token" => $token], 200);
    }
}