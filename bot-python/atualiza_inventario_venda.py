import json
import db_connection
import requests
from mysql.connector import Error
from datetime import datetime
from nacl.bindings import crypto_sign
import time

# URL da API principal
rootApiUrl = "https://api.dmarket.com"

def fetch_and_save_data(user_id, private_key, public_key):
    """
    Função para buscar dados da API e salvar no banco de dados.
    """
    nonce = str(round(datetime.now().timestamp()))
    api_url_path = "/marketplace-api/v1/user-offers"
    method = "GET"
    string_to_sign = method + api_url_path + nonce
    signature_prefix = "dmar ed25519 "
    encoded = string_to_sign.encode('utf-8')
    secret_bytes = bytes.fromhex(private_key)
    signature_bytes = crypto_sign(encoded, secret_bytes)
    signature = signature_bytes[:64].hex()
    headers = {
        "X-Api-Key": public_key,
        "X-Request-Sign": signature_prefix + signature,
        "X-Sign-Date": nonce
    }

    print(f"Buscando dados da API do DMARKET para o usuário {user_id}...")
    try:
        resp = requests.get(rootApiUrl + api_url_path, headers=headers)
        resp.raise_for_status()
        api_response_data = resp.json()

        # Salva os dados no banco de dados
        save_data_to_db(user_id, api_response_data)
    except requests.exceptions.RequestException as e:
        print(f"Erro ao obter dados da API para o usuário {user_id}: {e}")

import json
import db_connection
from mysql.connector import Error

def calculate_total_value(json_data):
    total_value = 0.0
    
    # Acessa a lista de itens no JSON
    items = json_data.get("Items", [])
    
    for item in items:
        # Acessa o valor do item dentro de Offer -> Price -> Amount
        offer = item.get("Offer", {})
        price = offer.get("Price", {})
        amount = price.get("Amount", 0.0)
        
        # Adiciona o valor ao total
        total_value += amount
    
    return total_value

def save_data_to_db(user_id, data):
    """
    Função para salvar ou atualizar os dados no banco de dados.
    """
    connection = db_connection.create_db_connection()  # Cria uma conexão com o banco de dados
    if connection is None:  # Verifica se a conexão não foi estabelecida corretamente
        return  # Retorna se a conexão não foi bem-sucedida
    
    cursor = connection.cursor()  # Cria um cursor para executar operações SQL
    try:
        # Calcula o valor total do inventário
        total_value = calculate_total_value(data)
        
        # Primeiro, tenta selecionar os dados do usuário
        cursor.execute("SELECT * FROM inventariodmarket WHERE user_id = %s AND status = 'a venda'", (user_id,))
        result = cursor.fetchone()  # Obtém o primeiro registro encontrado
        
        if result:
            # Se o usuário já existe, faz um UPDATE
            sql = """
                UPDATE inventariodmarket
                SET json = %s, valor_inventario = %s
                WHERE user_id = %s AND status = 'a venda'
            """
            values = (json.dumps(data), total_value, user_id)
            cursor.execute(sql, values)
            print(f"Dados do usuário {user_id} atualizados com sucesso na tabela inventariodmarket.")
        else:
            # Se o usuário não existe, faz um INSERT
            sql = """
                INSERT INTO inventariodmarket (user_id, json, valor_inventario, status)
                VALUES (%s, %s, %s, 'a venda')
            """
            values = (user_id, json.dumps(data), total_value)
            cursor.execute(sql, values)
            print(f"Dados do usuário {user_id} inseridos com sucesso na tabela inventariodmarket.")
        
        connection.commit()  # Confirma a transação
    except Error as e:
        print(f"Erro ao inserir ou atualizar dados na tabela inventariodmarket: {e}")  # Exibe mensagem de erro
    finally:
        if connection.is_connected():  # Verifica se a conexão está aberta
            cursor.close()  # Fecha o cursor
            db_connection.close_db_connection(connection)  # Fecha a conexão com o banco de dados




def select_from_credenciais():
    """
    Função para selecionar credenciais do banco de dados e interagir com a API.
    """
    connection = db_connection.create_db_connection()  # Cria uma conexão com o banco de dados
    if connection is None:  # Verifica se a conexão não foi estabelecida corretamente
        return  # Retorna se a conexão não foi bem-sucedida
    
    cursor = connection.cursor()  # Cria um cursor para executar operações SQL
    try:
        cursor.execute("SELECT id_credenciais, id_usuario, dmarket_password as private_key, dmarket_keyAPI as public_key FROM credenciais WHERE dmarket_password IS NOT NULL AND dmarket_keyAPI IS NOT NULL AND ativo_dmarket = 1")
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
                
                # Imprime a resposta para depuração
                print(f"Resposta da API de decriptação para id_credenciais {id_credenciais}: {response_data}")
                
                # Verifica se as chaves esperadas estão presentes
                if 'private_key' in response_data and 'public_key' in response_data:
                    private_key = response_data['private_key']
                    public_key = response_data['public_key']
                    print(f"Chaves encontradas: private_key={private_key}, public_key={public_key}")
                    fetch_and_save_data(id_usuario, private_key, public_key)
                    time.sleep(2)
                else:
                    print(f"Chaves esperadas não encontradas na resposta para id_credenciais {id_credenciais}.")
            
            except requests.exceptions.RequestException as e:
                print(f"Erro ao decriptar valores para id_credenciais {id_credenciais}: {e}")

    except Error as e:
        print(f"Erro ao executar o select: {e}")  # Exibe mensagem de erro ao executar o select
        
    finally:
        if connection.is_connected():  # Verifica se a conexão está aberta
            cursor.close()  # Fecha o cursor
            db_connection.close_db_connection(connection)  # Fecha a conexão com o banco de dados

def main():
    select_from_credenciais()

if __name__ == '__main__':
    while True:
        main()
        time.sleep(15)  # Pausa por 15 segundos antes de rodar novamente