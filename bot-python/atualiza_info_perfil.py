import json
import requests
from flask import Flask
import threading
from datetime import datetime
from nacl.bindings import crypto_sign
import time
from db_connection import create_db_connection, close_db_connection
from mysql.connector import Error

rootApiUrl = "https://api.dmarket.com"
app = Flask(__name__)

def fetch_balance(user_id, public_key, secret_key):
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

        print(f"Atualizando informações do usuário {user_id}...")
        try:
            resp = requests.get(rootApiUrl + api_url_path, headers=headers)
            resp.raise_for_status()
            user_balance = resp.json()
            update_database(user_id, user_balance)
        except requests.exceptions.RequestException as e:
            print(f"Erro ao obter o saldo para o usuário {user_id}: {e}")

def update_database(user_id, data):
    connection = create_db_connection()
    if connection is None:
        print("Falha na conexão com o banco de dados")
        return

    try:
        with connection.cursor() as cursor:
            sql = """
                INSERT INTO infodmarket (usuario_id, nome, email, nivel, local, id_dmarket, email_verificado)
                VALUES (%s, %s, %s, %s, %s, %s, %s)
                ON DUPLICATE KEY UPDATE
                    nome = VALUES(nome),
                    email = VALUES(email),
                    nivel = VALUES(nivel),
                    local = VALUES(local),
                    id_dmarket = VALUES(id_dmarket),
                    email_verificado = VALUES(email),
                    atualizado = CURRENT_TIMESTAMP
            """
            cursor.execute(sql, (
                user_id,
                data.get('username'),
                data.get('email'),
                data.get('level'),
                data.get('countryCode'),
                data.get('id'),
                data.get('isEmailVerified')
            ))
        connection.commit()
        print(f"Dados atualizados com sucesso para o usuário {user_id}")
    except Error as e:
        print(f"Erro ao atualizar os dados do usuário {user_id}: {e}")
    finally:
        close_db_connection(connection)

def select_from_credenciais():
    connection = create_db_connection()
    if connection is None:
        print("Falha na conexão com o banco de dados")
        return

    cursor = connection.cursor()
    try:
        cursor.execute("""
            SELECT id_credenciais, id_usuario, dmarket_password as private_key, dmarket_keyAPI as public_key
            FROM credenciais
            WHERE dmarket_password IS NOT NULL AND dmarket_keyAPI IS NOT NULL AND ativo_dmarket = 1
        """)
        records = cursor.fetchall()

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
                response.raise_for_status()
                response_data = response.json()
                
                print(f"Resposta da API de decriptação para id_credenciais {id_credenciais}: {response_data}")
                
                if 'private_key' in response_data and 'public_key' in response_data:
                    private_key = response_data['private_key']
                    public_key = response_data['public_key']
                    print(f"Chaves encontradas: private_key={private_key}, public_key={public_key}")
                    fetch_balance(id_usuario, public_key, private_key)
                else:
                    print(f"Chaves esperadas não encontradas na resposta para id_credenciais {id_credenciais}.")
            
            except requests.exceptions.RequestException as e:
                print(f"Erro ao decriptar valores para id_credenciais {id_credenciais}: {e}")

    except Error as e:
        print(f"Erro ao executar o select: {e}")
        
    finally:
        if connection.is_connected():
            cursor.close()
            close_db_connection(connection)

def main():
    select_from_credenciais()

if __name__ == '__main__':
    while True:
        main()
        time.sleep(60)
