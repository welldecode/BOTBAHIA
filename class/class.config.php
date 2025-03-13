<?php
include '/var/www/html/includes/config.php';

class config {
    private $db;

    function __construct() {
        $this->db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if (mysqli_connect_errno()) {
            echo "Erro ao conectar com o banco de dados: " . mysqli_connect_error();
            exit();
        }
    }
  
    public function getConfigEncrypt() {
        $query = "SELECT * FROM config WHERE id_config = 1";
        return $this->executeQuery($query);
    }

    public function getCotacaoDolar() {
        $query = "SELECT * FROM config WHERE id_config = 2";
        return $this->executeQuery($query);
    }

    public function getCotacaoEuro() {
        $query = "SELECT * FROM config WHERE id_config = 3";
        return $this->executeQuery($query);
    }

    public function updateConfig($dado, $id) {
        $query = "UPDATE config SET `key` = ? WHERE id_config = $id";
        $params = [$dado];
        return $this->executeQuery($query, $params);
    }

    public function getConfigMin() {
        $query = "SELECT * FROM config WHERE id_config = 2";
        return $this->executeQuery($query);
    }

    public function getConfigMax() {
        $query = "SELECT * FROM config WHERE id_config = 3";
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