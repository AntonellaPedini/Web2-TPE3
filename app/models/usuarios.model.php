<?php
require_once __DIR__ . '/Model.php';

class usuariosModel extends Model{

    public function getAll(){
        // 2. prepara y ejecuta la consulta
        $query = $this->db->prepare('SELECT * FROM usuarios');
        $query->execute();

        // 3. obtiene los resultados
        $usuarios = $query->fetchAll(PDO::FETCH_OBJ);

        return $usuarios;
    }

    public function get($id){
        $query = $this->db->prepare('SELECT * FROM usuarios WHERE id = ?');
        $query->execute([$id]);

        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getByEmail($email){
        $query = $this->db->prepare('SELECT * FROM usuarios WHERE email = ?');
        $query->execute([$email]);

        return $query->fetch(PDO::FETCH_OBJ);
    }
}
