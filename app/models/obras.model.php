<?php
require_once __DIR__ . '/Model.php';

class ObrasModel extends Model {

    public function getAll($orderBy = null, $order = 'ASC', $page = null, $limit = null){
        $camposValidos = ['id_obra', 'nombre', 'año_creacion', 'corriente_artistica', 'tecnica', 'soporte', 'descripcion', 'id_artista', 'imagen'];
        if (!in_array($orderBy, $camposValidos)) {
            $orderBy = 'id_obra';
        }
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        $sql = "SELECT * FROM obras ORDER BY $orderBy $order";

        if ($limit !== null) {
            $page = max(1, (int)($page ?? 1));
            $offset = ($page - 1) * $limit;
            $sql .= " LIMIT ? OFFSET ?";
        }

        $query = $this->db->prepare($sql);
        if ($limit !== null) {
            $query->bindValue(1, $limit, PDO::PARAM_INT);
            $query->bindValue(2, $offset, PDO::PARAM_INT);
        }
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function get($id) {
        $query = $this->db->prepare ('SELECT obras.*, artista.nombre_completo
                                    FROM obras
                                    JOIN artista ON obras.id_artista = artista.id_artista
                                    WHERE obras.id_obra = ?') ;// Obtiene la obra junto con el nombre completo del artista
        $query->execute ([$id]);
        $obra = $query->fetch (PDO::FETCH_OBJ) ;
        return $obra;
    }

    public function insert ($nombre, $año_creacion, $tecnica, $soporte, $corriente_artistica, $descripcion, $imagen, $id_artista){
        $query = $this->db-> prepare ('INSERT INTO obras (`nombre`, `año_creacion`, `tecnica`, `soporte`, `corriente_artistica`, `descripcion`, `imagen`, `id_artista`) VALUES (?,?,?,?,?,?,?,?)');
        $query -> execute ([$nombre, $año_creacion, $tecnica, $soporte, $corriente_artistica, $descripcion, $imagen, $id_artista]);
        return $this->db->lastInsertId();
    }

    public function delete ($id){
        $query = $this->db->prepare ('DELETE FROM obras WHERE id_obra = ?') ;
        $query->execute ([$id]);
        return $query->rowCount();
    }

    public function update($nombre, $año_creacion, $tecnica, $soporte, $corriente_artistica, $descripcion, $imagen, $id_artista, $id_obra) {
        $query = $this->db->prepare('UPDATE obras SET nombre=?, año_creacion=?, tecnica=?, soporte=?, corriente_artistica=?, descripcion=?, imagen=?, id_artista=? WHERE id_obra=?');

        $query->execute([$nombre, $año_creacion, $tecnica, $soporte, $corriente_artistica, $descripcion, $imagen, $id_artista, $id_obra]);

        return $query->rowCount();
    }

    public function getObrasByArtista($id_artista, $orderBy = null, $order = 'ASC', $page = null, $limit = null) {
        $camposValidos = ['id_obra', 'nombre', 'año_creacion', 'corriente_artistica', 'tecnica', 'soporte', 'descripcion', 'id_artista', 'imagen'];
        if (!in_array($orderBy, $camposValidos)) {
            $orderBy = 'id_obra';
        }
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        $sql = "SELECT * FROM obras WHERE id_artista = ? ORDER BY $orderBy $order";

        if ($limit !== null) {
            $page = max(1, (int)($page ?? 1));
            $limit = max(1, (int)$limit);
            $offset = ($page - 1) * $limit;
            $sql .= " LIMIT ? OFFSET ?";
        }

        $query = $this->db->prepare($sql);
        $query->bindValue(1, $id_artista, PDO::PARAM_INT);
        if ($limit !== null) {
            $query->bindValue(2, $limit, PDO::PARAM_INT);
            $query->bindValue(3, $offset, PDO::PARAM_INT);
        }
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    //Punto 3: Endpoint para buscar obras por nombre (con paginación y ordenamiento)
    public function getByNombre($nombre, $orderBy = null, $order = 'ASC', $page = null, $limit = null){
        $camposValidos = ['id_obra', 'nombre', 'año_creacion', 'corriente_artistica', 'tecnica', 'soporte', 'descripcion', 'id_artista', 'imagen'];
        if (!in_array($orderBy, $camposValidos)) {
            $orderBy = 'id_obra';
        }
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        $sql = "SELECT * FROM obras WHERE nombre LIKE ? ORDER BY $orderBy $order";

        if ($limit !== null) {
            $page = max(1, (int)($page ?? 1));
            $limit = max(1, (int)$limit);
            $offset = ($page - 1) * $limit;
            $sql .= " LIMIT ? OFFSET ?";
        }

        $query = $this->db->prepare($sql);
        $query->bindValue(1, '%' . $nombre . '%', PDO::PARAM_STR);
        if ($limit !== null) {
            $query->bindValue(2, $limit, PDO::PARAM_INT);
            $query->bindValue(3, $offset, PDO::PARAM_INT);
        }
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    
}