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
        cursor.execute("SELECT id_credenciais, id_usuario, dmarket_password as private_key, dmarket_keyAPI as public_key FROM credenciais WHERE dmarket_password IS NOT NULL AND dmarket_keyAPI IS NOT NULL AND ativo_dmarket = 1 AND bloqueado = 0")
        records = cursor.fetchall()  # Obtém todas as linhas do resultado da consulta
        cursor.execute("SELECT * FROM config WHERE id_config = 2")
        min_row = cursor.fetchone()
        min_value = min_row[-1] if min_row else None  # Pega o valor da última coluna


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

            cursor.execute("SELECT * FROM usuario WHERE id_usuario = %s", (id_usuario,))
            user = cursor.fetchone()

            min_value = user[14]  # valor 4
            max_value = user[15]  # valor 100

            min = int(min_value) * 100
            max = int(max_value) * 100

            initial_url = (rootApiUrl + f"/exchange/v1/market/items"
               f"?gameId=a8db"
               f"&limit=100"
               f"&offset=0"
               f"&orderBy=personal"
               f"&orderDir=asc"
               f"&treeFilters=cheapestBySteamAnalyst%5B%5D%3Dtrue"
               f"&currency=USD"
               f"&priceFrom={min}"
               f"&priceTo={max}")

            fallback_url = (rootApiUrl + f"/exchange/v1/market/items"
                        f"?gameId=a8db"
                        f"&limit=100"
                        f"&offset=0"
                        f"&orderBy=personal"
                        f"&orderDir=asc"
                        f"&treeFilters=cheapestBySteamAnalyst%5B%5D%3Dtrue"
                        f"&currency=USD"
                        f"&priceFrom={min}"
                        f"&priceTo={max}"
                        f"&cursor=WzI4NDgxLDE3MTkzMTg4NDkwMDAsIjA1ODdiZDNjLTE4YjYtNGIyZi04OWM2LTc3ODg1NWE3NjM0NiJd")
            
            # Envia uma requisição POST para decriptar valores
            response = requests.post(url, data=data)  
            response_data = response.json()  # Converte a resposta para JSON
            
            # Chama a função para retornar os dados da API
            time.sleep(2)
            if not process_offers(initial_url, response_data['public_key'], response_data['private_key']):
                process_offers(fallback_url, response_data['public_key'], response_data['private_key'], start_attempt=101)  # Continuar a contagem de tentativas
            
    except Error as e:
        print(f"Erro ao executar o select: {e}")  # Exibe mensagem de erro ao executar o select
        
    finally:
        if connection.is_connected():  # Verifica se a conexão está aberta
            cursor.close()  # Fecha o cursor
            db_connection.close_db_connection(connection)  # Fecha a conexão com o banco de dados


BAD_ITEMS = ['key', 'pin', 'sticker', 'case', 'operation', 'pass', 'capsule', 'package', 'challengers', 'patch', 'music', 'kit', 'graffiti']

def get_market_offers(url):
    market_response = requests.get(url)
    response_data = json.loads(market_response.text)
    if "objects" in response_data and len(response_data["objects"]) > 0:
        return response_data["objects"]
    else:
        raise Exception("Nenhuma oferta encontrada no mercado")

def build_target_body_from_offer(offer):
    return {
        "offers": [
            {
                "offerId": offer["extra"]["offerId"],
                "price": {
                    "amount": offer["price"]["USD"],
                    "currency": "USD"
                },
                "type": "dmarket"
            }
        ]
    }

def try_to_buy_offer(offer, attempt, public_key, private_key):
    nonce = str(round(datetime.now().timestamp()))
    api_url_path = "/exchange/v1/offers-buy"
    method = "PATCH"
    body = build_target_body_from_offer(offer)
    string_to_sign = method + api_url_path + json.dumps(body) + nonce
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
    print(f"Tentativa de compra #{attempt} para a oferta {offer['extra']['offerId']}") 
    resp = requests.patch(rootApiUrl + api_url_path, json=body, headers=headers)
    response_data = json.loads(resp.text)
    if resp.status_code == 200 and response_data.get("status") != "TxFailed":
        print(json.dumps(response_data, indent=2))
        return True
    elif response_data.get("dmOffersFailReason", {}).get("code") == "BalanceInsufficientFunds":
        print(f"Saldo insuficiente para a oferta {offer['extra']['offerId']}: {resp.text}")
        return False
    else:
        print(f"Falha ao tentar comprar oferta {offer['extra']['offerId']}: {resp.text}")
        return False

def process_offers(url, public_key, private_key, start_attempt=1):
    try:
        offers = get_market_offers(url)
        attempt = start_attempt
        for offer in offers:
            item_type = offer.get("extra", {}).get("itemType", "").lower()
            if item_type not in BAD_ITEMS:
                if try_to_buy_offer(offer, attempt, public_key, private_key):
                    return True
                attempt += 1
    except Exception as e:
        print(e)
    return False

def main():
    select_from_credenciais()

# Loop infinito para rodar o código a cada 1 hora
while True:
    main()
    print("Aguardando ...")
    time.sleep(3600)  # Pausa por 2 hora (7200 segundos) antes de rodar novamente
