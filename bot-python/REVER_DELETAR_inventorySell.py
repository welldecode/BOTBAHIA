import json
from datetime import datetime
from nacl.bindings import crypto_sign
import requests
from flask import Flask, jsonify, request
import threading

public_key = ""
secret_key = ""
user_id = ""

rootApiUrl = "https://api.dmarket.com"

app = Flask(__name__)

# Variável global para armazenar os dados retornados pela API
api_response_data = {}

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
            api_response_data = resp.json()  # Armazena o JSON de retorno na variável global
            print(resp)
        except requests.exceptions.RequestException as e:
            print(f"Erro ao obter dados da API: {e}")

@app.route('/set_keys_inventory', methods=['POST'])
def set_keys():
    global public_key, secret_key, user_id
    data = request.get_json()
    public_key = data['public_key']
    secret_key = data['private_key']
    user_id = data['user_id']
    print(public_key, secret_key, user_id)
    
    # Chama a função fetch_api_data diretamente após definir as chaves
    fetch_thread = threading.Thread(target=fetch_api_data)
    fetch_thread.start()
    
    return jsonify({"message": "Keys and user ID set successfully"})

@app.route('/total_inventory', methods=['GET'])
def get_total_item_value():
    global api_response_data
    return jsonify(api_response_data)

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=2007)
