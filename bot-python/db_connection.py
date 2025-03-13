import mysql.connector
from mysql.connector import Error

def create_db_connection():
    try:
        connection = mysql.connector.connect(
            host='127.0.0.1',
            database='bot',
            user='root',
            password='bkqTnt5MULfz'
        )
        if connection.is_connected():
            print("Conexão com o banco de dados foi bem-sucedida")
            return connection
    except Error as e:
        print(f"Erro ao conectar ao banco de dados: {e}")
        return None

def close_db_connection(connection):
    if connection.is_connected():
        connection.close()
        print("Conexão com o banco de dados foi encerrada")
