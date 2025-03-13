import json
from datetime import datetime
from nacl.bindings import crypto_sign
import requests
from flask import Flask, jsonify, request
from db_connection import create_db_connection, close_db_connection
from mysql.connector import Error
import time


app = Flask(__name__)

rootApiUrl = "https://api.dmarket.com"

def fetch_balance(user_id, public_key, secret_key):
    """
    Função para buscar o saldo do usuário.
    """
    user_balance = None
    try:
        if public_key and secret_key:
            nonce = str(round(datetime.now().timestamp()))
            api_url_path = "/account/v1/user"
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

            print("Atualizando saldo...")
            resp = requests.get(rootApiUrl + api_url_path, headers=headers)
            resp.raise_for_status()
            user_balance = resp.json()
            print("Dados recebidos da API:", user_balance)  # Adicionado para depuração

            # Ajustar a verificação com base na estrutura real dos dados retornados
            if not user_balance:
                raise ValueError("Resposta da API está vazia")
            if 'id' not in user_balance:
                raise ValueError("Chave 'id' não encontrada na resposta da API")

    except Exception as e:
        print(f"Ocorreu um erro ao buscar o saldo: {e}")
        update_database(user_id)  # Exclui as credenciais se a requisição falhar


def update_database(user_id):
    """
    Função para deletar as credenciais do banco de dados.
    """
    connection = create_db_connection()
    if connection is None:
        print("Falha na conexão com o banco de dados")
        return

    try:
        with connection.cursor() as cursor:
            sql = """
                DELETE FROM credenciais
                WHERE id_usuario = %s
            """
            cursor.execute(sql, (user_id,))
        connection.commit()
        print("Tupla deletada com sucesso")
    except Error as e:
        print(f"Erro ao deletar a tupla: {e}")
    finally:
        close_db_connection(connection)

def select_from_credenciais():
    """
    Função para selecionar credenciais do banco de dados e interagir com a API.
    """
    connection = create_db_connection()  # Cria uma conexão com o banco de dados
    if connection is None:  # Verifica se a conexão não foi estabelecida corretamente
        return  # Retorna se a conexão não foi bem-sucedida
    
    cursor = connection.cursor()  # Cria um cursor para executar operações SQL
    try:
        cursor.execute("SELECT id_credenciais, id_usuario, dmarket_password as private_key, dmarket_keyAPI as public_key FROM credenciais WHERE dmarket_password IS NOT NULL AND dmarket_keyAPI IS NOT NULL")
        records = cursor.fetchall()  # Obtém todas as linhas do resultado da consulta

        for record in records:
            id_credenciais, id_usuario, private_key, public_key = record
            
            # Envia uma requisição POST para decriptar valores
            url = "http://66.70.170.171/decryption_values.php"
            data = {
                'id_credenciais': id_credenciais,
                'private_key': private_key,
                'public_key': public_key
            }
            
            try:
                response = requests.post(url, data=data)
                response.raise_for_status()  # Garante que a resposta foi bem-sucedida
                response_data = response.json()  # Converte a resposta para JSON
                
                print(f"Resposta da API de decriptação para id_credenciais {id_credenciais}: {response_data}")
                
                # Verifica se as chaves esperadas estão presentes
                if 'private_key' in response_data and 'public_key' in response_data:
                    private_key = response_data['private_key']
                    public_key = response_data['public_key']
                    print(f"Chaves encontradas: private_key={private_key}, public_key={public_key}")
                    fetch_balance(id_usuario, public_key, private_key)  # Atualiza o saldo do usuário
                    time.sleep(2)
                else:
                    print(f"Chaves esperadas não encontradas na resposta para id_credenciais {id_credenciais}.")
                    # Se as chaves esperadas não estiverem na resposta, considere a exclusão
                    update_database(id_usuario)
            
            except requests.exceptions.RequestException as e:
                print(f"Erro ao decriptar valores para id_credenciais {id_credenciais}: {e}")
                update_database(id_usuario)  # Exclui as credenciais se a requisição falhar

    except Error as e:
        print(f"Erro ao executar o select: {e}")  # Exibe mensagem de erro ao executar o select
        
    finally:
        if connection.is_connected():  # Verifica se a conexão está aberta
            cursor.close()  # Fecha o cursor
            close_db_connection(connection)  # Fecha a conexão com o banco de dados

def main():
    select_from_credenciais()

if __name__ == '__main__':
    while True:
        main()
        time.sleep(120)  
