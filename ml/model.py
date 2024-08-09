import pandas as pd
from joblib import load
from flask import Flask, request, jsonify

app = Flask(__name__)

pipeline = load('pipeline.pkl')

@app.route('/predict', methods=['POST'])
def predict():
    try:
        if not request.is_json:
            return jsonify({'error': 'Request content-type must be application/json'}), 415

        data = request.get_json()

        custom_data = pd.DataFrame({
            'Product Name': [data['product_name']],
            'Raw Material Name': [data['raw_material_name']],
            'Raw Material Amount': [data['raw_material_amount']],
            'Labor Hours': [data['labor_hours']],
        })

        prediction = pipeline.predict(custom_data)
        result = {'predicted_products_made': prediction[0]}

        return jsonify(result)

    except Exception as e:
        return jsonify({'error': str(e)}), 400

if __name__ == '__main__':
    app.run(debug=True)
