import json
import db_connection  # Importa o módulo db_connection para gerenciar a conexão com o banco de dados
import mysql.connector  # Importa o conector MySQL para Python
import requests  # Importa o módulo requests para fazer requisições HTTP
from mysql.connector import Error  # Importa a classe Error do conector MySQL
from datetime import datetime, timedelta  # Importa datetime e timedelta para manipulação de datas
from nacl.bindings import crypto_sign  # Importa crypto_sign da biblioteca nacl.bindings
import json  # Importa o módulo json para manipular dados JSON
import time  # Importa o módulo time para adicionar pausas entre as execuções
from pytz import timezone


# change url to prod
rootApiUrl = "https://api.dmarket.com"

def select_from_credenciais():
    """
    Função para selecionar credenciais do banco de dados e interagir com a API.
    """
    connection = db_connection.create_db_connection()  # Cria uma conexão com o banco de dados
    if connection is None:  # Verifica se a conexão não foi estabelecida corretamente
        return  # Retorna se a conexão não foi bem-sucedida
    
    cursor = connection.cursor()  # Cria um cursor para executar operações SQL
    try:
        # Executa a consulta SQL para obter credenciais válidas
        cursor.execute("SELECT id_credenciais, id_usuario, dmarket_password as private_key, dmarket_keyAPI as public_key FROM credenciais WHERE dmarket_password IS NOT NULL AND dmarket_keyAPI IS NOT NULL AND ativo_dmarket = 1")
        records = cursor.fetchall()  # Obtém todas as linhas do resultado da consulta

        for record in records:
            # Extrai os dados de cada linha da consulta
            id_credenciais, id_usuario, private_key, public_key = record  
            
            # URL para decriptar valores
            url = "http://66.70.170.171/decryption_values.php"  
            
            # Dados a serem enviados para decriptação
            data = {
                'id_credenciais': id_credenciais,
                'private_key': private_key,
                'public_key': public_key
            }
            
            # Envia uma requisição POST para decriptar valores
            response = requests.post(url, data=data)  
            response_data = response.json()  # Converte a resposta para JSON

            #CHAMAR FUNÇÃO PARA BUSCAR DA API E COLOCAR NO ENDPOINT PERSONALIZADO
            
    except Error as e:
        print(f"Erro ao executar o select: {e}")  # Exibe mensagem de erro ao executar o select
        
    finally:
        if connection.is_connected():  # Verifica se a conexão está aberta
            cursor.close()  # Fecha o cursor
            db_connection.close_db_connection(connection)  # Fecha a conexão com o banco de dados

def fetch_api_data():
    global public_key, secret_key, user_id, api_response_data
    if public_key and secret_key:
        nonce = str(round(datetime.now().timestamp()))
        api_url_path = "/marketplace-api/v1/user-offers"
        method = "GET"
        string_to_sign = method + api_url_path + nonce
        signature_prefix = "dmar ed25519 "
        encoded = string_to_sign.encode('utf-8')
        secret_bytes = bytes.fromhex(secret_key)
        signature_bytes = crypto_sign(encoded, secret_bytes)
        signature = signature_bytes[:64].hex()
        headers = {
            "X-Api-Key": public_key,
            "X-Request-Sign": signature_prefix + signature,
            "X-Sign-Date": nonce
        }

        print("Buscando dados da API do DMARKET...")
        try:
            resp = requests.get(rootApiUrl + api_url_path, headers=headers)
            resp.raise_for_status()
        except requests.exceptions.RequestException as e:
            print(f"Erro ao obter dados da API: {e}")

def main():
    select_from_credenciais()

# Loop infinito para rodar o código a cada 1 hora
while True:
    main()
    time.sleep(120)  # Pausa por 2 hora (7200 segundos) antes de rodar novamente
