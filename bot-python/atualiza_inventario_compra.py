import json
import db_connection
import requests
from mysql.connector import Error
from datetime import datetime
from nacl.bindings import crypto_sign
import time

rootApiUrl = "https://api.dmarket.com"

# Função para fechar a conexão com o banco de dados
def close_db_connection(connection):
    if connection.is_connected():
        connection.close()

# Função para calcular o valor total do inventário
def calculate_total_value(data):
    total_value = 0
    for item in data.get('objects', []):
        price_usd = item.get('price', {}).get('USD', '0')
        try:
            total_value += float(price_usd)/100
        except ValueError:
            print(f"Valor USD inválido: {price_usd}")
    return total_value

# Função para salvar ou atualizar os dados no banco de dados
def save_data_to_db(user_id, data):
    connection = db_connection.create_db_connection()
    if connection is None:
        return
    
    cursor = connection.cursor()
    try:
        total_value = calculate_total_value(data)
        
        cursor.execute("SELECT * FROM inventariodmarket WHERE user_id = %s AND status = 'comprados'", (user_id,))
        result = cursor.fetchone()
        
        if result:
            sql = """
                UPDATE inventariodmarket
                SET json = %s, valor_inventario = %s
                WHERE user_id = %s AND status = 'comprados'
            """
            values = (json.dumps(data), total_value, user_id)
            cursor.execute(sql, values)
            print(f"Dados do usuário {user_id} atualizados com sucesso na tabela inventariodmarket.")
        else:
            sql = """
                INSERT INTO inventariodmarket (user_id, json, valor_inventario, status)
                VALUES (%s, %s, %s, 'comprados')
            """
            values = (user_id, json.dumps(data), total_value)
            cursor.execute(sql, values)
            print(f"Dados do usuário {user_id} inseridos com sucesso na tabela inventariodmarket.")
        
        connection.commit()
    except Error as e:
        print(f"Erro ao inserir ou atualizar dados na tabela inventariodmarket: {e}")
    finally:
        if connection.is_connected():
            cursor.close()
            close_db_connection(connection)

# Função para buscar dados da API e salvar no banco de dados
def fetch_and_save_data(user_id, private_key, public_key):
    nonce = str(round(datetime.now().timestamp()))
    api_url_path = "/exchange/v1/user/items?gameId=a8db&limit=50&offset=0&orderBy=title&orderDir=desc&currency=USD&priceFrom=0&priceTo=0"
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

    print("Buscando dados da API do DMARKET...")
    try:
        resp = requests.get(rootApiUrl + api_url_path, headers=headers)
        resp.raise_for_status()
        api_response_data = resp.json()
        print(f"Dados da API obtidos: {api_response_data}")
        save_data_to_db(user_id, api_response_data)
    except requests.exceptions.RequestException as e:
        print(f"Erro ao obter dados da API: {e}")

# Função para selecionar credenciais do banco de dados
def select_from_credenciais():
    connection = db_connection.create_db_connection()
    if connection is None:
        return
    
    cursor = connection.cursor()
    try:
        cursor.execute("SELECT id_credenciais, id_usuario, dmarket_password as private_key, dmarket_keyAPI as public_key FROM credenciais WHERE dmarket_password IS NOT NULL AND dmarket_keyAPI IS NOT NULL AND ativo_dmarket = 1")
        records = cursor.fetchall()

        for record in records:
            id_credenciais, id_usuario, private_key, public_key = record
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
                    fetch_and_save_data(id_usuario, private_key, public_key)
                    time.sleep(2)
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
        time.sleep(15)  # Pausa por 30 segundos antes de rodar novamente
