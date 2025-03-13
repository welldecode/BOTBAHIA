<?php
include '/var/www/html/includes/config.php';

class historico_dmarket
{
    private $db;

    function __construct()
    {
        $this->db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if (mysqli_connect_errno()) {
            echo "Erro ao conectar com o banco de dados: " . mysqli_connect_error();
            exit();
        }
    }

    public function getTransacoes($id_user)
    {
        $query = "SELECT * FROM historico_dmarket WHERE id_user = ?";
        return $this->executeQuery($query, [$id_user]);
    }

    public function getTransacaoMaisAntiga($id_user)
    {
        $query = "
            SELECT * 
            FROM historico_dmarket 
            WHERE id_user = ? 
            AND tipo_transacao = 'Deposit' 
            AND status_transacao = 'success' 
            ORDER BY data_transacao ASC 
            LIMIT 1
        ";
        return $this->executeQuery($query, [$id_user]);
    }

    public function getTotalCompas($id_user)
    {
        $query = "SELECT SUM(valor_transacao) as total FROM historico_dmarket WHERE id_user = ? AND tipo_transacao = 'Purchase' AND status_transacao = 'success'";
        return $this->executeQuery($query, [$id_user]);
    }
    public function getTotalVendas($id_user)
    {
        $query = "SELECT SUM(valor_transacao) as total FROM historico_dmarket WHERE id_user = ? AND tipo_transacao = 'Sell' AND status_transacao = 'success'";
        return $this->executeQuery($query, [$id_user]);
    }
    public function getTotalDepositado($id_user)
    {
        $query = "SELECT SUM(valor_transacao) as total FROM historico_dmarket WHERE id_user = ? AND tipo_transacao = 'Deposit' AND status_transacao = 'success'";
        return $this->executeQuery($query, [$id_user]);
    }

    public function getInventarioJsonVendas($id_usuario){
        
    $query = "SELECT * FROM inventariodmarket WHERE user_id = ? AND status = 'a venda'";
    return $this->executeQuery($query, [$id_usuario]);
    }

    public function getInventarioJsonCompras($id_usuario){
        
    $query = "SELECT * FROM inventariodmarket WHERE user_id = ? AND status = 'comprados'";
    return $this->executeQuery($query, [$id_usuario]);
    }

    public function getLucros($id_user, $ano)
    {
        // Consulta SQL para obter lucros agrupados por mês
        $query = "
            SELECT 
                MONTH(data) AS mes, 
                COALESCE(SUM(value), 0) AS total 
            FROM 
                lucros_dmarket 
            WHERE 
                id_user = ? AND 
                YEAR(data) = ? 
            GROUP BY 
                mes
            ORDER BY 
                mes;
        ";

        // Executa a consulta e retorna os resultados
        return $this->executeQuery($query, [$id_user, $ano]);
    }

    

    public function insertLucros($id_user, $value)
    {
        $query = "INSERT INTO lucros_dmarket (id_user, value)  VALUES (?,?)";
        return $this->executeQuery($query, [$id_user, $value]);
    }

    public function totalVendasSemanal($id_user)
    {
        $query = "
            SELECT SUM(valor_transacao) as total 
            FROM historico_dmarket 
            WHERE id_user = ? 
            AND tipo_transacao = 'Sell' 
            AND status_transacao = 'success'
            AND YEARWEEK(data_transacao, 1) = YEARWEEK(CURDATE(), 1)
        ";
        return $this->executeQuery($query, [$id_user]);
    }

    public function totalVendasMensalDash($id_user)
    {
        $query = "
            SELECT 
            meses.mes,
            COALESCE(SUM(historico_dmarket.valor_transacao), 0) AS total 
        FROM 
            (SELECT 1 AS mes UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
             UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 
             UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12) AS meses
        LEFT JOIN historico_dmarket 
        ON MONTH(historico_dmarket.data_transacao) = meses.mes 
        AND YEAR(historico_dmarket.data_transacao) = YEAR(CURDATE())
        AND historico_dmarket.id_user = ? 
        AND historico_dmarket.tipo_transacao = 'Sell' 
        AND historico_dmarket.status_transacao = 'success'
        GROUP BY meses.mes
        ORDER BY meses.mes
        ";

        return $this->executeQuery($query, [$id_user]);
    }

    public function totalComprasMensalDash($id_user)
    {
        $query = "
            SELECT 
            meses.mes,
            COALESCE(SUM(historico_dmarket.valor_transacao), 0) AS total 
        FROM 
            (SELECT 1 AS mes UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
             UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 
             UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12) AS meses
        LEFT JOIN historico_dmarket 
        ON MONTH(historico_dmarket.data_transacao) = meses.mes 
        AND YEAR(historico_dmarket.data_transacao) = YEAR(CURDATE())
        AND historico_dmarket.id_user = ? 
        AND historico_dmarket.tipo_transacao = 'Purchase' 
        AND historico_dmarket.status_transacao = 'success'
        GROUP BY meses.mes
        ORDER BY meses.mes
        ";

        return $this->executeQuery($query, [$id_user]);
    }

    public function totalComprasSemanal($id_user)
    {
        $query = "
            SELECT SUM(valor_transacao) as total 
            FROM historico_dmarket 
            WHERE id_user = ? 
            AND tipo_transacao = 'Purchase' 
            AND status_transacao = 'success'
            AND YEARWEEK(data_transacao, 1) = YEARWEEK(CURDATE(), 1)
        ";
        return $this->executeQuery($query, [$id_user]);
    }

    public function teste($id_user, $mes, $ano)
{
    $query = "
        SELECT 
            m.mes, 
            COALESCE(FORMAT(SUM(CASE WHEN h.tipo_transacao = 'Sell' AND h.status_transacao = 'success' THEN h.valor_transacao ELSE 0 END), 2), '0') AS total
        FROM (
            SELECT ? AS mes
        ) m
        LEFT JOIN historico_dmarket h
        ON MONTH(h.data_transacao) = m.mes
        AND YEAR(h.data_transacao) = ?
        AND h.id_user = ? 
        AND h.tipo_transacao = 'Sell' 
        AND h.status_transacao = 'success'
        GROUP BY m.mes
        ORDER BY m.mes
    ";
    return $this->executeQuery($query, [$mes, $ano, $id_user]);
}


    public function totalVendasMensal($id_user)
    {
        $query = "
            SELECT SUM(valor_transacao) as total 
            FROM historico_dmarket 
            WHERE id_user = ? 
            AND tipo_transacao = 'Sell' 
            AND status_transacao = 'success'
            AND MONTH(data_transacao) = MONTH(CURDATE())
            AND YEAR(data_transacao) = YEAR(CURDATE())
        ";
        return $this->executeQuery($query, [$id_user]);
    }

    public function totalComprasMensal($id_user)
    {
        $query = "
            SELECT SUM(valor_transacao) as total 
            FROM historico_dmarket 
            WHERE id_user = ? 
            AND tipo_transacao = 'Sell' 
            AND status_transacao = 'Purchase'
            AND MONTH(data_transacao) = MONTH(CURDATE())
            AND YEAR(data_transacao) = YEAR(CURDATE())
        ";
        return $this->executeQuery($query, [$id_user]);
    }

    public function totalVendasDiario($id_user)
    {
        $query = "
            SELECT SUM(valor_transacao) as total 
            FROM historico_dmarket 
            WHERE id_user = ? 
            AND tipo_transacao = 'Sell' 
            AND status_transacao = 'success'
            AND DATE(data_transacao) = CURDATE()
        ";
        return $this->executeQuery($query, [$id_user]);
    }

    public function totalComprasDiario($id_user)
    {
        $query = "
            SELECT SUM(valor_transacao) as total 
            FROM historico_dmarket 
            WHERE id_user = ? 
            AND tipo_transacao = 'Purchase' 
            AND status_transacao = 'success'
            AND DATE(data_transacao) = CURDATE()
        ";
        return $this->executeQuery($query, [$id_user]);
    }


    //////////////////////////////////
    public function totalVendasUltimoMes($id_user)
    {
        $query = "
            SELECT SUM(valor_transacao) as total 
            FROM historico_dmarket 
            WHERE id_user = ? 
            AND tipo_transacao = 'Sell' 
            AND status_transacao = 'success'
            AND data_transacao BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE()
        ";
        return $this->executeQuery($query, [$id_user]);
    }


    public function totalVendasUltimos3Meses($id_user)
    {
        $query = "
            SELECT SUM(valor_transacao) as total 
            FROM historico_dmarket 
            WHERE id_user = ? 
            AND tipo_transacao = 'Sell' 
            AND status_transacao = 'success'
            AND data_transacao BETWEEN DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND CURDATE()
        ";
        return $this->executeQuery($query, [$id_user]);
    }


    public function totalVendasUltimos6Meses($id_user)
    {
        $query = "
            SELECT SUM(valor_transacao) as total 
            FROM historico_dmarket 
            WHERE id_user = ? 
            AND tipo_transacao = 'Sell' 
            AND status_transacao = 'success'
            AND data_transacao BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()
        ";
        return $this->executeQuery($query, [$id_user]);
    }


    public function totalVendasUltimoAno($id_user)
    {
        $query = "
            SELECT SUM(valor_transacao) as total 
            FROM historico_dmarket 
            WHERE id_user = ? 
            AND tipo_transacao = 'Sell' 
            AND status_transacao = 'success'
            AND data_transacao BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND CURDATE()
        ";
        return $this->executeQuery($query, [$id_user]);
    }

    
    public function totalVendas($id_user)
    {
        $query = "
            SELECT SUM(valor_transacao) as total 
            FROM historico_dmarket 
            WHERE id_user = ? 
            AND tipo_transacao = 'Sell' 
            AND status_transacao = 'success'
        ";
        return $this->executeQuery($query, [$id_user]);
    }


    public function executeQuery($query, $params = [])
    {
        global $pdo;

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    private function executeQuerySingleResult($query)
    {
        $result = mysqli_query($this->db, $query);
        if ($result) {
            return mysqli_fetch_assoc($result);
        } else {
            echo "Erro ao executar consulta: " . mysqli_error($this->db);
            exit();
        }
    }
}