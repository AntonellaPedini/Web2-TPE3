<?php
require_once __DIR__ . '/../models/obras.model.php';
require_once __DIR__ . '/../models/artistas.model.php';

class ObrasApiController {
    private $model;
    private $artistasModel;

    public function __construct() {
        $this->model = new ObrasModel();
        $this-> artistasModel = new ArtistasModel();
    }

    public function getObras($req, $res){
        $artista = $req->query->id_artista ?? null;
        $nombre = $req->query->nombre ?? null;
        $orderBy = $req->query->orderBy ?? null;
        $order = $req->query->order ?? 'ASC';

        //Paginación. Si se envía el parámetro limit, se pagina. Si no, se devuelven todas las obras.
        $page = isset($req->query->page) ? max(1, (int)$req->query->page) : null;
        $limit = isset($req->query->limit) ? max(1, (int)$req->query->limit) : null;

        if ($artista) {
            $artistaExistente = $this->artistasModel->getById($artista);
            if (!$artistaExistente) {
                return $res->json("El artista con id=$artista no existe", 404);
            }
            $obras = $this->model->getObrasByArtista($artista, $orderBy, $order, $page, $limit);
        } elseif ($nombre) {
            $obras = $this->model->getByNombre($nombre, $orderBy, $order, $page, $limit);
        } else {
            $obras = $this->model->getAll($orderBy, $order, $page, $limit);
        }

        return $res->json($obras, 200);
    }

    public function getObraById($req, $res) { 
        $id_obra = $req->params->id;
        $obra = $this->model->get($id_obra);

        if (!$obra) {
            return $res->json("La obra con el id=$id_obra no existe", 404);
        }

        return $res->json($obra, 200);
    }
    
    public function insertObra ($req, $res){
        if (!$req->user) {
            return $res->json("Token inválido. Por favor, genere uno nuevo", 401);
        }

        if (!isset($req->body->nombre) || empty($req->body->nombre)) {
            return $res->json('Falta completar el nombre', 400);
        }
        if (!isset($req->body->año_creacion) || empty($req->body->año_creacion)) {
            return $res->json('Falta completar el año de creación', 400);
        }
        if (!isset($req->body->tecnica) || empty($req->body->tecnica)) {
            return $res->json('Falta completar la técnica', 400);
        }
        if (!isset($req->body->soporte) || empty($req->body->soporte)) {
            return $res->json('Falta completar el soporte', 400);
        }
        if (!isset($req->body->corriente_artistica) || empty($req->body->corriente_artistica)) {
            return $res->json('Falta completar la corriente artistica', 400);
        }
        if (!isset($req->body->descripcion) || empty($req->body->descripcion)) {
            return $res->json('Falta completar la descripción', 400);
        }
        if (!isset($req->body->id_artista) || empty($req->body->id_artista)) {
            return $res->json('Falta completar el identificador (id) del artista', 400);
        }

        $nombre = $req->body->nombre ?? null;
        $año_creacion = $req->body->año_creacion ?? null;
        $tecnica = $req->body->tecnica ?? null;
        $soporte = $req->body->soporte ?? null;
        $corriente_artistica = $req->body->corriente_artistica ?? null;
        $descripcion = $req->body->descripcion ?? null;
        $imagen = $req->body->imagen ?? null; // opcional, sin validación previa
        $id_artista = $req->body->id_artista ?? null;

        $artista = $this->artistasModel->getById($id_artista);
        if (!$artista) {
            return $res->json("El artista con id=$id_artista no existe", 404);
        }

        $id_obra = $this->model->insert($nombre, $año_creacion, $tecnica, $soporte, $corriente_artistica, $descripcion, $imagen, $id_artista);
        
        if (!$id_obra) {
            return $res->json("Error al crear la obra", 500);
        }

        $obraCreada = $this->model->get($id_obra);

        return $res->json($obraCreada, 201);
    }

    public function removeObra($req, $res) { 
        $id_obra = $req->params->id;
        $obra = $this->model->get($id_obra);

        if (!$obra) {
            return $res->json("La obra con el id=$id_obra no existe", 404);
        }

        $this->model->delete($id_obra);
        return $res->json("Obra con id=$id_obra eliminada", 200);

    }

    public function updateObra($req, $res) { 
        if (!$req->user) {
            return $res->json("Token inválido. Por favor, genere uno nuevo", 401);
        }

        $id_obra= $req->params->id;
        $obra = $this->model->get($id_obra);

        if (!$obra) {
            return $res->json("La obra con el id=$id_obra no existe", 404);
        }

        if (!isset($req->body->nombre) || empty($req->body->nombre)) {
            return $res->json('Falta completar el nombre', 400);
        }
        if (!isset($req->body->año_creacion) || empty($req->body->año_creacion)) {
            return $res->json('Falta completar el año de creación', 400);
        }
        if (!isset($req->body->tecnica) || empty($req->body->tecnica)) {
            return $res->json('Falta completar la técnica', 400);
        }
        if (!isset($req->body->soporte) || empty($req->body->soporte)) {
            return $res->json('Falta completar el soporte', 400);
        }
        if (!isset($req->body->corriente_artistica) || empty($req->body->corriente_artistica)) {
            return $res->json('Falta completar la corriente artistica', 400);
        }
        if (!isset($req->body->descripcion) || empty($req->body->descripcion)) {
            return $res->json('Falta completar la descripción', 400);
        }
        if (!isset($req->body->id_artista) || empty($req->body->id_artista)) {
            return $res->json('Falta completar el identificador (id) del artista', 400);
        }

        $nombre = $req->body->nombre ?? null;
        $año_creacion = $req->body->año_creacion ?? null;
        $tecnica = $req->body->tecnica ?? null;
        $soporte = $req->body->soporte ?? null;
        $corriente_artistica = $req->body->corriente_artistica ?? null;
        $descripcion = $req->body->descripcion ?? null;
        $imagen = $req->body->imagen ?? null; // opcional, sin validación previa
        $id_artista = $req->body->id_artista ?? null;

        $artista = $this->artistasModel->getById($id_artista);
        if (!$artista) {
            return $res->json("El artista con id=$id_artista no existe", 404);
        }

        $this->model->update($nombre, $año_creacion, $tecnica, $soporte, $corriente_artistica, $descripcion, $imagen, $id_artista, $id_obra);

        $obraActualizada = $this->model->get($id_obra);
        return $res->json($obraActualizada, 200);
    }

    public function patchObra($req, $res){
        if (!$req->user) {
            return $res->json("Token inválido. Por favor, genere uno nuevo", 401);
        }

        $id_obra = $req->params->id;
        $obra = $this->model->get($id_obra);

        if (!$obra) {
            return $res->json("La obra con el id=$id_obra no existe", 404);
        }

        // Solo se valida si el campo viene y, si viene, que no esté vacío
        $campos = ['nombre', 'año_creacion', 'tecnica', 'soporte', 'corriente_artistica', 'descripcion', 'id_artista'];
        foreach ($campos as $campo) {
            if (isset($req->body->$campo) && empty($req->body->$campo)) {
                return $res->json("El campo $campo no puede estar vacío", 400);
            }
        }

        if (isset($req->body->id_artista)) {
            $artista = $this->artistasModel->getById($req->body->id_artista);
            if (!$artista) {
                return $res->json("El artista con id={$req->body->id_artista} no existe", 404);
            }
        }

        // Si el campo no viene, se mantiene el valor actual de la obra
        $nombre = $req->body->nombre ?? $obra->nombre;
        $año_creacion = $req->body->año_creacion ?? $obra->año_creacion;
        $tecnica = $req->body->tecnica ?? $obra->tecnica;
        $soporte = $req->body->soporte ?? $obra->soporte;
        $corriente_artistica = $req->body->corriente_artistica ?? $obra->corriente_artistica;
        $descripcion = $req->body->descripcion ?? $obra->descripcion;
        $imagen = $req->body->imagen ?? $obra->imagen;
        $id_artista = $req->body->id_artista ?? $obra->id_artista;

        $this->model->update($nombre, $año_creacion, $tecnica, $soporte, $corriente_artistica, $descripcion, $imagen, $id_artista, $id_obra);

        $obraActualizada = $this->model->get($id_obra);
        return $res->json($obraActualizada, 200);
    }

}


