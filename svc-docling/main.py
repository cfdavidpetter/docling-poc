import json

from docling.document_converter import DocumentConverter

converter = DocumentConverter()

pdf_file_path = "./pdfs/doc.pdf"
output_file = "./output/doc.json"

result = converter.convert(pdf_file_path)
structured_content = result.document.export_to_dict()

with open(output_file, "w", encoding="utf-8") as json_file:
  json.dump(structured_content, json_file, ensure_ascii=False, indent=4)

print(f"PDF content was saved in: {output_file}")
