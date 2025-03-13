import db_connection  # Importa o módulo db_connection para gerenciar a conexão com o banco de dados
import mysql.connector  # Importa o conector MySQL para Python
import requests  # Importa o módulo requests para fazer requisições HTTP
from mysql.connector import Error  # Importa a classe Error do conector MySQL
from datetime import datetime, timedelta  # Importa datetime e timedelta para manipulação de datas
from nacl.bindings import crypto_sign  # Importa crypto_sign da biblioteca nacl.bindings
import json  # Importa o módulo json para manipular dados JSON
import time  # Importa o módulo time para adicionar pausas entre as execuções
from pytz import timezone

rootApiUrl = "https://api.dmarket.com"  # URL raiz da API DMarket

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
        cursor.execute("SELECT id_credenciais, id_usuario, dmarket_password as private_key, dmarket_keyAPI as public_key FROM credenciais WHERE dmarket_password IS NOT NULL AND dmarket_keyAPI IS NOT NULL")
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
            
            # Chama a função para retornar os dados da API
            return_api(id_credenciais, id_usuario, response_data['private_key'], response_data['public_key'])
            time.sleep(2)
            
    except Error as e:
        print(f"Erro ao executar o select: {e}")  # Exibe mensagem de erro ao executar o select
        
    finally:
        if connection.is_connected():  # Verifica se a conexão está aberta
            cursor.close()  # Fecha o cursor
            db_connection.close_db_connection(connection)  # Fecha a conexão com o banco de dados

def get_sao_paulo_time():
    sao_paulo_tz = timezone('America/Sao_Paulo')
    return datetime.now(sao_paulo_tz)

def return_api(id_credenciais, id_usuario, private_key, public_key):
    """
    Função para interagir com a API DMarket.
    """
    if public_key and private_key:  # Verifica se as chaves públicas e privadas existem
        nonce = str(round(datetime.now().timestamp()))  # Gera um nonce com o timestamp atual
        api_url_path = "/exchange/v1/history?version=V3&offset=0&limit=200"  # Caminho da API para obter histórico
        method = "GET"  # Método HTTP para a requisição
        string_to_sign = method + api_url_path + nonce  # String a ser assinada
        signature_prefix = "dmar ed25519 "  # Prefixo da assinatura
        encoded = string_to_sign.encode('utf-8')  # Codifica a string para bytes
        secret_bytes = bytes.fromhex(private_key)  # Converte a chave privada de hexadecimal para bytes
        signature_bytes = crypto_sign(encoded, secret_bytes)  # Gera a assinatura
        signature = signature_bytes[:64].hex()  # Converte a assinatura para hexadecimal
        headers = {
            "X-Api-Key": public_key,
            "X-Request-Sign": signature_prefix + signature,
            "X-Sign-Date": nonce
        }

        try:
            # Faz a requisição GET para obter o histórico do usuário
            resp = requests.get(rootApiUrl + api_url_path, headers=headers)  
            resp.raise_for_status()  # Levanta uma exceção se a requisição falhar
            user_history = resp.json()  # Converte a resposta para JSON
            
            # Atualiza o banco de dados com o histórico obtido
            atualizar_bd_historico(user_history, id_usuario) 
            
        except requests.exceptions.RequestException as e:
            print(f"Erro ao obter o histórico: {e}")  # Exibe mensagem de erro ao obter o histórico da API

def atualizar_bd_historico(return_api, user_id):
    connection = db_connection.create_db_connection()  # Cria uma conexão com o banco de dados
    if connection is None:  # Verifica se a conexão não foi estabelecida corretamente
        return  # Retorna se a conexão não foi bem-sucedida
    
    try:
        with connection.cursor() as cursor:
            for obj in return_api['objects']:
                transaction_id = obj["customId"]
                transaction_type = obj["action"]
                transaction_status = obj["status"]
                transaction_amount = obj["changes"][0]["money"]["amount"]
                transaction_date = datetime.fromtimestamp(obj["updatedAt"], timezone('America/Sao_Paulo'))
                item_name = obj.get("subject", "")
                
                # Verifica se o id_transacao já existe na tabela historico_dmarket
                cursor.execute("SELECT id_transacao FROM historico_dmarket WHERE id_transacao = %s", (transaction_id,))
                existing_transaction = cursor.fetchone()
                
                if not existing_transaction:
                    # Insere os dados na tabela historico_dmarket
                    sql_insert = """
                        INSERT INTO historico_dmarket (id_transacao, id_user, data_transacao, nome_item, tipo_transacao, valor_transacao, status_transacao)
                        VALUES (%s, %s, %s, %s, %s, %s, %s)
                    """
                    cursor.execute(sql_insert, (transaction_id, user_id, transaction_date, item_name, transaction_type, transaction_amount, transaction_status))
                    
        connection.commit()  # Confirma as alterações no banco de dados
    except Error as e:
        print(f"Erro ao processar transações: {e}")
    finally:
        db_connection.close_db_connection(connection)  # Fecha a conexão com o banco de dados

while True:
    select_from_credenciais()
    print("Dormindo...")
    time.sleep(90)  # Pausa de 90 segundos entre as execuções