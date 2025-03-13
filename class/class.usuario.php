<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
include '/var/www/html/includes/config.php';

class usuario {
    private $db;

    function __construct() {
        $this->db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if (mysqli_connect_errno()) {
            echo "Erro ao conectar com o banco de dados: " . mysqli_connect_error();
            exit();
        }
    }
  
    public function getUsuarios() {
        $query = "SELECT * FROM usuario;";
        return $this->executeQuery($query, []);
    }

    public function getUsuariosRankLucroSemanal() {
        $query = "SELECT * FROM usuario ORDER BY lucro_semanal DESC";
        return $this->executeQuery($query, []);
    }
    public function getUsuariosRankLucroMensal() {
        $query = "SELECT * FROM usuario ORDER BY lucro_mensal DESC";
        return $this->executeQuery($query, []);
    }
    public function getUsuariosRankLucro() {
        $query = "SELECT * FROM usuario ORDER BY lucro_total DESC";
        return $this->executeQuery($query, []);
    }

    public function atualizarAvatar($id_usuario, $avatar) {
        $query = "UPDATE usuario SET avatar = ? WHERE id_usuario = ?";
        return $this->executeQuery($query, [$avatar, $id_usuario]);
    }

    public function atualizarPerfil($user, $img, $nome, $id, $rankativo) {
        $query = "UPDATE usuario SET nome_usuario = ?, avatar = ?, nome_completo = ?, rank_ativo = ? WHERE id_usuario = ?";
        return $this->executeQuery($query, [$user, $img, $nome, $rankativo, $id]);
    }
    public function getUsuariosID($id_usuario) {
        $query = "SELECT * FROM usuario WHERE id_usuario = ?;";
        return $this->executeQuery($query, [$id_usuario]);
    }

    public function editUsuario($id_usuario, $nome, $email, $nome_completo, $cargo) {
        $query = "UPDATE usuario SET nome_usuario = ?, email_usuario = ?, nome_completo = ?, cargo_usuario = $cargo WHERE id_usuario = ?";
        return $this->executeQuery($query, [$nome, $email, $nome_completo, $id_usuario]);
    }
    
    public function editUsuarioSenha($id_usuario, $nome, $email, $senha, $nome_completo, $cargo) {
        $query = "UPDATE usuario SET nome_usuario = ?, senha_usuario = ?, email_usuario = ?, nome_completo = ?, cargo_usuario = $cargo WHERE id_usuario = ?";
        return $this->executeQuery($query, [$nome, $senha, $email, $nome_completo, $id_usuario]);
    }

    public function generateEmailToken($length = 32) {
        // Caracteres que serão usados na geração do token
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charsLength = strlen($chars);
        $token = '';
    
        // Gera o token com o comprimento especificado
        for ($i = 0; $i < $length; $i++) {
            $token .= $chars[rand(0, $charsLength - 1)];
        }
    
        return $token;
    }

    public function verificaEmail($email_token) {
        $query = "SELECT nome_usuario FROM usuario WHERE email_token = ?";
        return $this->executeQuery($query, [$email_token]);
    }

    public function lastinsert() {
        $query = "SELECT * FROM usuario ORDER BY id_usuario DESC LIMIT 1;";
        return $this->executeQuery($query);
    }
    
    public function updateEmailVerificado($email_token) {
        $query = "UPDATE usuario SET email_confirmado = '1' WHERE email_token = ?";
        return $this->executeQuery($query, [$email_token]);
    }

    public function updateRankDash($value, $id_usuario) {
        $query = "UPDATE usuario SET lucro_total = ? WHERE id_usuario = ?";
        return $this->executeQuery($query, [$value, $id_usuario]);
    }

    public function addUsuario($nome_completo, $nome, $senha, $email, $email_token, $cargo) {
        $query = "INSERT INTO usuario (nome_usuario, senha_usuario, email_usuario, nome_completo, email_token, cargo_usuario, avatar)
        VALUES (?, ?, ?, ?, ?, ?, '/assets/perfilpadrao.png')";
        return $this->executeQuery($query, [$nome, $senha, $email, $nome_completo, $email_token, $cargo]);
    }
    
    public function bloquearUsuarios($id_usuario) {
        $query = "UPDATE usuario SET bloqueado = '1' WHERE id_usuario = ?";
        return $this->executeQuery($query, [$id_usuario]);
    }

    public function desbloquearUsuarios($id_usuario) {
        $query = "UPDATE usuario SET bloqueado = '0' WHERE id_usuario = ?";
        return $this->executeQuery($query, [$id_usuario]);
    }
    public function deleteUsuarios($id_usuario) {
        $query = "DELETE FROM usuario WHERE usuario.id_usuario = ?";
        return $this->executeQuery($query, [$id_usuario]);
    }
    
    public function updateLucrosUsuario($id_usuario, $total, $mensal, $semanal) {
        $query = "UPDATE usuario SET lucro_total = ?, lucro_mensal = ?, lucro_semanal = ? WHERE id_usuario = ?";
        return $this->executeQuery($query, [$total, $mensal, $semanal, $id_usuario]);
    }

    public function updateMax_price_dmarket($dado, $id_usuario) {
        $query = "UPDATE usuario SET max_price_dmarket = ? WHERE id_usuario = $id_usuario";
        $params = [$dado];
        return $this->executeQuery($query, $params);
    }

    public function updateMin_price_dmarket($dado, $id_usuario) {
        $query = "UPDATE usuario SET min_price_dmarket = ? WHERE id_usuario = $id_usuario";
        $params = [$dado];
        return $this->executeQuery($query, $params);
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