<?php
session_start();
include '/var/www/html/includes/config.php';

class SessionManager {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function createSession($userId, $userData) {
        $sessionId = session_create_id();
        $access = time();
        $data = serialize($userData); // Serializa os dados do usuário para armazenamento
    
        $sql = "INSERT INTO sessions (id, access, data) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$sessionId, $access, $data]);
    
        // Define o cookie com o ID da sessão
        setcookie('session_id', $sessionId, time() + (30 * 24 * 60 * 60), '/');
    
        $_SESSION['email'] = $userData['email_usuario'];
        $_SESSION['usuario_id'] = $userData['id_usuario'];
        $_SESSION['nome_usuario'] = $userData['nome_usuario'];
        $_SESSION['cargo'] = $userData['cargo_usuario'];
        $_SESSION['avatar'] = $userData['avatar'];
    
        return $sessionId;
    }    

    public function validateSession($sessionId) {
        $sql = "SELECT * FROM sessions WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$sessionId]);
        
        if ($stmt->rowCount() > 0) {
            $session = $stmt->fetch(PDO::FETCH_ASSOC);
            $sessionData = unserialize($session['data']);
            // Atualiza o timestamp de acesso
            $this->updateAccessTime($sessionId);
            return $sessionData;
        } else {
            return false;
        }
    }

    private function updateAccessTime($sessionId) {
        $sql = "UPDATE sessions SET access = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([time(), $sessionId]);
    }

    public function destroySession($sessionId) {
        $sql = "DELETE FROM sessions WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$sessionId]);
        setcookie('session_id', '', time() - 3600, '/');
    }
}

?>