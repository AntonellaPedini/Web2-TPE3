<?php
require_once __DIR__ . '/Model.php';

class ArtistasModel extends Model{

    public function getAll() {
        $query = $this->db->prepare ('SELECT * FROM artista') ;
        $query->execute ();
        $artistas = $query->fetchAll (PDO::FETCH_OBJ) ;
        return $artistas;
    }

    public function getByName($name) {
        $query = $this->db->prepare ('SELECT * FROM artista WHERE nombre_completo = ?');
        $query->execute ([$name]);
        $artista = $query->fetch (PDO::FETCH_OBJ) ;
        return $artista;
    }

    public function getById($id_artista) {
        $query = $this->db->prepare ('SELECT * FROM artista WHERE id_artista = ?');
        $query->execute ([$id_artista]);
        $artista = $query->fetch (PDO::FETCH_OBJ) ;
        return $artista;
    }

    public function insert ($nombre_completo, $fecha_nacimiento, $fecha_fallecimiento, $corriente_artistica, $nacionalidad, $biografia, $imagen){
        $query = $this->db-> prepare ('INSERT INTO artista (`nombre_completo`, `fecha_nacimiento`, `fecha_fallecimiento`, `corriente_artistica`, `nacionalidad`, `biografia`, `imagen`) VALUES (?,?,?,?,?,?,?)');
        $query -> execute ([$nombre_completo, $fecha_nacimiento, $fecha_fallecimiento, $corriente_artistica, $nacionalidad, $biografia, $imagen]);
        return $this->db->lastInsertId();
    }

    public function delete ($id){
        $query = $this->db->prepare ('DELETE FROM artista WHERE id_artista = ?') ;
        $query->execute ([$id]) ;
        return $query->rowCount();
    }

    public function update ($nombre_completo, $fecha_nacimiento, $fecha_fallecimiento, $corriente_artistica, $nacionalidad, $biografia, $imagen, $id_artista){
        $query = $this->db-> prepare ('UPDATE artista SET `nombre_completo`=?,`fecha_nacimiento`=?,`fecha_fallecimiento`=?,`corriente_artistica`= ?,`nacionalidad`=?,`biografia`=?,`imagen`=? WHERE `id_artista`=?');
        $query -> execute ([$nombre_completo, $fecha_nacimiento, $fecha_fallecimiento, $corriente_artistica, $nacionalidad, $biografia, $imagen, $id_artista]);
        return $query->rowCount();
        
    }

    public function getObrasByArtista($id) {
        $query = $this->db->prepare('SELECT * FROM obras WHERE id_artista = ?');
        $query->execute([$id]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

}
