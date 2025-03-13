<?php
include '/var/www/html/includes/config.php';

class Anuncio {
    private $db;

    function __construct() {
        $this->db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if (mysqli_connect_errno()) {
            echo "Erro ao conectar com o banco de dados: " . mysqli_connect_error();
            exit();
        }
    }
  
    public function getAnuncios() {
        $query = "SELECT * FROM anuncio";
        return $this->executeQuery($query);
    }

    public function getAnuncio($id) {
        $query = "SELECT * FROM anuncio WHERE id_anuncio = ?";
        return $this->executeQuery($query, [$id]);
    }

    public function getAnunciosAtivos() {
        $query = "SELECT * FROM anuncio WHERE ativo = 'on'";
        return $this->executeQuery($query);
    }

    public function countAnunciosAtivos() {
        $query = "SELECT COUNT(*) as total FROM anuncio WHERE ativo = 'on'";
        return $this->executeQuery($query);
    }

    public function insertAnuncio($titulo, $subtitulo, $conteudo, $ativo) {
        $query = "INSERT INTO anuncio (titulo, sub_titulo, descricao, ativo) VALUES (?,?,?,?)";
        $params = [$titulo, $subtitulo, $conteudo, $ativo];
        return $this->executeQuery($query, $params);
    }

    public function editAnuncio($titulo, $subtitulo, $conteudo, $ativo, $id) {
        $query = "UPDATE anuncio SET titulo = ?, sub_titulo = ?, descricao =?, ativo = ? WHERE id_anuncio = ?";
        $params = [$titulo, $subtitulo, $conteudo, $ativo, $id];
        return $this->executeQuery($query, $params);
    }

    public function delAnuncio($id) {
        $query = "DELETE FROM anuncio WHERE id_anuncio = ?";
        return $this->executeQuery($query, [$id]);
    }

    public function executeQuery($query, $params = []) {
        global $pdo; // Certifique-se de que a conexão com o banco esteja disponível
    
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt; // Retorna o objeto PDOStatement
    }    

    private function executeQuerySingleResult($query) {
        $result = mysqli_query($this->db, $query);
        if ($result) {
            return mysqli_fetch_assoc($result);
        } else {
            echo "Erro ao executar consulta: " . mysqli_error($this->db);
            exit();
        }
    }
}
?>