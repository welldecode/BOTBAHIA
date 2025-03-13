<?php
include '/var/www/html/includes/config.php';

class login {
    private $db;

    function __construct() {
        $this->db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if (mysqli_connect_errno()) {
            echo "Erro ao conectar com o banco de dados: " . mysqli_connect_error();
            exit();
        }
    }
  
    public function getUsuario($email, $senha) {
        $query = "SELECT * FROM usuario  WHERE email_usuario = ? AND senha_usuario = ?";
        return $this->executeQuery($query, [$email, $senha]);
    }

    public function getUsuarioByEmail($email) {
        $query = "SELECT * FROM usuario  WHERE email_usuario = ?";
        return $this->executeQuery($query, [$email]);
    }

    public function updateUserToken($token, $userId) {
        $query = "UPDATE usuarios SET login_token = ? WHERE id = ?";
        $stmt = $this->executeQuery($query, [$token, $userId]);

        return $stmt->rowCount(); // Retorna o número de linhas afetadas
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