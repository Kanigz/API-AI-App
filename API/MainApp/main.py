from flask import Flask, request, jsonify
from flask_cors import CORS
import mysql.connector
from openai import OpenAI
from dotenv import load_dotenv
import os

app = Flask(__name__)
CORS(app)


db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'api'
}


load_dotenv()
api_key = os.getenv("OPENAI_API_KEY")
client = OpenAI(api_key=api_key)

def get_table_schema():
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor()
        cursor.execute("SHOW TABLES")
        tables = cursor.fetchall()
        
        schema = []
        for table in tables:
            table_name = table[0]
            cursor.execute(f"DESCRIBE {table_name}")
            columns = cursor.fetchall()
            schema.append({
                "table": table_name,
                "columns": [col[0] for col in columns]
            })
        return schema
    except Exception as e:
        return f"Error: {str(e)}"
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

def handle_database_operation(user_question):
    schema = get_table_schema()
    
    prompt = f"""
    Convert this request to SQL query: {user_question}
    Return only the SQL query without additional text or explanations.
    Support all SQL operations (SELECT, INSERT, UPDATE, DELETE).

    Database Schema:
    1. Table 'user' with columns: id, name, email, password, otp, is_active
    2. Table 'api_keys' with columns: id, user_id, api_key, usage_limit, used_count, is_active, created_at
    3. Table 'game_scores' with columns: id, player_name, score, game_name, created_at, api_key
    
    Table Relationships:
    - Table 'user' is related to 'api_keys' through users.id = api_keys.user_id
    - Table 'api_keys' is related to 'game_scores' through api_keys.api_key = game_scores.api_key
    
    Important Information:
    - User with id=8 and name='admin' has admin privileges
    - The 'used_count' column in the 'api_keys' table tracks how many times each API key has been used
    - The admin user's API key has been used 16 times (as of now)
    - All game records are for the game 'snake'
    - API keys have different usage limits (100, 200, 1000)
    
    Examples of natural language to SQL conversions:
    - "Wypisz uzytkownikow" → "SELECT * FROM user"
    - "Pokaz wszystkich graczy" → "SELECT * FROM game_scores"
    - "Ile razy jest uzyte api admina" → "SELECT a.used_count FROM api_keys a JOIN user u ON a.user_id = u.id WHERE u.name = 'admin'"
    - "List all users" → "SELECT * FROM user"
    - "Show admin API usage" → "SELECT a.used_count FROM api_keys a JOIN user u ON a.user_id = u.id WHERE u.name = 'admin'"
    """
    
    response = client.chat.completions.create(
    model="gpt-4", 
    messages=[
        {"role": "system", "content": "You are a SQL expert. Convert natural language requests to SQL queries based on the provided schema. Return the SQL query with explanations. Support all SQL operations including SELECT, INSERT, UPDATE, and DELETE. "},
        {"role": "user", "content": prompt}
    ],
    temperature=0.3
)
    
    sql_query = response.choices[0].message.content.strip()
    return execute_sql_operation(sql_query)

def execute_sql_operation(query):
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor(dictionary=True)
        

        cursor.execute(query)
        

        if query.strip().upper().startswith('SELECT'):
            results = cursor.fetchall()
        else:

            connection.commit()
            results = {
                'affected_rows': cursor.rowcount,
                'operation': query.split()[0].upper()
            }
        
        return results
    except Exception as e:
        return f"Error: {str(e)}"
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

@app.route('/api/query', methods=['POST'])
def api_query():
    try:
        data = request.json
        if not data or 'question' not in data:
            return jsonify({'error': 'No question provided'}), 400
        
        results = handle_database_operation(data['question'])
        

        if isinstance(results, str) and results.startswith('Error:'):
            return jsonify({'error': results}), 500
            
        return jsonify({'results': results})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/api/direct-query', methods=['POST'])
def direct_query():
    try:
        data = request.json
        if not data or 'query' not in data:
            return jsonify({'error': 'No SQL query provided'}), 400
        
        results = execute_sql_operation(data['query'])
        
        if isinstance(results, str) and results.startswith('Error:'):
            return jsonify({'error': results}), 500
            
        return jsonify({'results': results})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/api/schema', methods=['GET'])
def api_schema():
    try:
        schema = get_table_schema()
        return jsonify({'schema': schema})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/api/test', methods=['GET'])
def test_connection():
    return jsonify({'status': 'API is working!'})

if __name__ == '__main__':
    app.run(host='0.0.0.0', debug=True, port=5000)
