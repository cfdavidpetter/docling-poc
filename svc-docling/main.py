import json
import os
import base64
import uuid
import pika

from docling.document_converter import DocumentConverter

# Queue configuration
RABBITMQ_HOST = 'rabbitmq'
PDF_QUEUE = 'pdf_queue'
JSON_QUEUE = 'json_queue'

# Initialize the converter
converter = DocumentConverter()

def process_file(ch, method, properties, body):
    try:
        # Generate a unique identifier for the file names
        unique_id = str(uuid.uuid4())

        # Save the received base64 data as a PDF file
        print("Decoding received base64 data...")
        input_dir = "./pdfs"
        os.makedirs(input_dir, exist_ok=True)  # Ensure the directory exists
        pdf_file_path = os.path.join(input_dir, f"{unique_id}.pdf")
        
        with open(pdf_file_path, "wb") as pdf_file:
            pdf_file.write(base64.b64decode(body))  # Decode base64 and write as binary
        
        print(f"PDF saved at: {pdf_file_path}")
        
        # Process the PDF
        result = converter.convert(pdf_file_path)
        structured_content = result.document.export_to_dict()
        
        # Save the JSON temporarily
        output_dir = "./output"
        os.makedirs(output_dir, exist_ok=True)  # Ensure the directory exists
        output_file = os.path.join(output_dir, f"{unique_id}.json")
        
        with open(output_file, "w", encoding="utf-8") as json_file:
            json.dump(structured_content, json_file, ensure_ascii=False, indent=4)
        
        print(f"JSON saved at: {output_file}")
        
        # Publish the JSON to the next queue
        with open(output_file, "r", encoding="utf-8") as json_file:
            json_data = json_file.read()
        
        channel.basic_publish(
            exchange='',
            routing_key=JSON_QUEUE,
            body=json_data
        )
        print(f"JSON sent to queue: {JSON_QUEUE}")

    except Exception as e:
        print(f"Error processing file: {e}")

    finally:
        # Acknowledge the message receipt
        ch.basic_ack(delivery_tag=method.delivery_tag)

print("Starting service...")

# Connect to RabbitMQ
connection = pika.BlockingConnection(pika.ConnectionParameters(host=RABBITMQ_HOST))
channel = connection.channel()

print("Connecting with queue...")

# Declare the queues
channel.queue_declare(queue=PDF_QUEUE)
channel.queue_declare(queue=JSON_QUEUE)

# Consume messages from the queue
channel.basic_consume(queue=PDF_QUEUE, on_message_callback=process_file)

print("Waiting for files in the queue...")
channel.start_consuming()
