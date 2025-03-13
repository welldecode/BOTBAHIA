<?php
include '/var/www/html/includes/config.php';

class infobot {
    private $db;

    function __construct() {
        $this->db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if (mysqli_connect_errno()) {
            echo "Erro ao conectar com o banco de dados: " . mysqli_connect_error();
            exit();
        }
    }
  
    public function getInfoUser($id) {
        $query = "SELECT * FROM infodmarket WHERE usuario_id = $id";
        return $this->executeQuery($query);
    }

    public function addUser($id) {
        $query = "INSERT INTO infodmarket (usuario_id) VALUES ($id)";
        return $this->executeQuery($query);
    }
    
    public function initDepositos($id) {
        $query = "INSERT INTO depositos (id_user, id_deposito, valor, data_deposito) VALUES ($id, 0, 0, NOW())";
        return $this->executeQuery($query);
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