<?php
include '/var/www/html/includes/config.php';

class ConfigCartao {
    private $db;

    function __construct() {
        $this->db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if (mysqli_connect_errno()) {
            echo "Erro ao conectar com o banco de dados: " . mysqli_connect_error();
            exit();
        }
    }
  
    public function insertConfig($nomeCompleto, $cpf, $numeroCartao, $validade, $cvv) {
        $query = "INSERT INTO config_cartao (nome_completo, cpf, numero_cartao, vencimento, cvv) VALUES (?,?,?,?,?)";
        $params = [$nomeCompleto, $cpf, $numeroCartao, $validade, $cvv];
        return $this->executeQuery($query, $params);
    }
    
    public function updateConfig($nomeCompleto, $cpf, $numeroCartao, $validade, $cvv) {
        $query = "UPDATE FROM config_cartao SET nome_completo = ?, cpf = ?, numero_cartao = ?, vencimento = ?, cvv = ?";
        $params = [$nomeCompleto, $cpf, $numeroCartao, $validade, $cvv];
        return $this->executeQuery($query, $params);
    }

    public function getConfig() {
        $query = "SELECT * FROM config_cartao";
        return $this->executeQuery($query);
    }

    public function delConfig() {
        $query = "DELETE FROM config_cartao";
        return $this->executeQuery($query);
    }

    public function alreadyConfig() {
        $query = "SELECT COUNT(*) as total FROM config_cartao";
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