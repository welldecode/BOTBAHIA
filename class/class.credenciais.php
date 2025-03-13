<?php
include '/var/www/html/includes/config.php';

class credenciais {
    private $db;

    function __construct() {
        $this->db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if (mysqli_connect_errno()) {
            echo "Erro ao conectar com o banco de dados: " . mysqli_connect_error();
            exit();
        }
    }
    public function buscarUsuario($id_usuario) {
        $query = "SELECT * FROM credenciais WHERE id_usuario = ?";
        $params = [$id_usuario];
        return $this->executeQuery($query, $params);
    }

    public function getInfo($id_usuario) {
        $query = "SELECT * FROM credenciais WHERE id_usuario = ?";
        return $this->executeQuery($query, [$id_usuario]);
    }

    public function insertCredenciaisSteam($user, $password, $email, $id_usuario) {
        $query = "INSERT INTO credenciais (id_usuario, steam_user, steam_password, steam_email) VALUES (?,?,?,?)";
        $params = [$id_usuario, $user, $password, $email];
        return $this->executeQuery($query, $params);
    }
    public function updateCredenciaisSteam($user, $password, $email, $id_usuario) {
        $query = "UPDATE credenciais SET steam_user = ?, steam_password = ?, steam_email = ? WHERE id_usuario = ?";
        $params = [$user, $password, $email, $id_usuario];
        return $this->executeQuery($query, $params);
    }

    public function insertCredenciaisDmarket($password, $keyapi, $id_usuario) {
        $query = "INSERT INTO credenciais (id_usuario, dmarket_password, dmarket_keyAPI) VALUES (?,?,?)";
        $params = [$id_usuario, $password, $keyapi];
        return $this->executeQuery($query, $params);
    }
    public function updateCredenciaisDmarket( $password, $keyapi, $id_usuario) {
        $query = "UPDATE credenciais SET  dmarket_password = ?, dmarket_keyAPI = ? WHERE id_usuario = ?";
        $params = [$password, $keyapi, $id_usuario];
        return $this->executeQuery($query, $params);
    }

    public function insertCredenciaisBuff($email, $password, $fone, $id_usuario) {
        $query = "INSERT INTO credenciais (id_usuario, buff_email, buff_fone, buff_password) VALUES (?,?,?,?)";
        $params = [$id_usuario, $email, $fone, $password];
        return $this->executeQuery($query, $params);
    }
    public function updateCredenciaisBuff($email, $password, $fone, $id_usuario) {
        $query = "UPDATE credenciais SET buff_email = ?, buff_fone = ?, buff_password = ? WHERE id_usuario = ?";
        $params = [$email, $fone, $password, $id_usuario];
        return $this->executeQuery($query, $params);
    }

    public function insertCredenciaisPaypal($key, $token, $id_usuario) {
        $query = "INSERT INTO credenciais (id_usuario, paypal_token, paypal_secretkey) VALUES (?,?,?)";
        $params = [$id_usuario, $token, $key];
        return $this->executeQuery($query, $params);
    }
    public function updateCredenciaisPaypal($key, $token, $id_usuario) {
        $query = "UPDATE credenciais SET paypal_token = ?, paypal_secretkey = ? WHERE id_usuario = ?";
        $params = [$token, $key, $id_usuario];
        return $this->executeQuery($query, $params);
    }

    public function updateLigarBot($status, $id_usuario) {
        $query = "UPDATE credenciais SET ativo_dmarket = ? WHERE id_usuario = ?";
        $params = [$status, $id_usuario];
        return $this->executeQuery($query, $params);
    }

    public function updateBloqueado($id_usuario) {
        $query = "UPDATE credenciais SET bloqueado = 1 WHERE id_usuario = ?";
        $params = [$id_usuario];
        return $this->executeQuery($query, $params);
    }

    public function updateDesbloquear($id_usuario) {
        $query = "UPDATE credenciais SET bloqueado = 0 WHERE id_usuario = ?";
        $params = [$id_usuario];
        return $this->executeQuery($query, $params);
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
?>