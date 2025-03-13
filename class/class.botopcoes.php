<?php
include '/var/www/html/includes/config.php';

class BotOpcoes {
    private $db;

    function __construct() {
        $this->db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if (mysqli_connect_errno()) {
            echo "Erro ao conectar com o banco de dados: " . mysqli_connect_error();
            exit();
        }
    }
  
    public function getPerms() {
        $query = "SELECT * FROM permissoes_bot";
        return $this->executeQuery($query);
    }

    public function updatePerms($id, $status) {
        $query = "UPDATE permissoes_bot SET status = ? WHERE id = ?";
        return $this->executeQuery($query, [$status, $id]);
    }

    public function executeQuery($query, $params = []) {
        global $pdo;
    
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
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