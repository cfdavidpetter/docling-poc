# Proof of Concept (POC) - Docling Integration

## Description
This repository contains a proof of concept (POC) intended to test integration with the Python library [Docling](https://github.com/docling). The goal of this experiment is to create a simple web application that allows:

1. Upload a file to the server.
2. Process the file using a service with the Docling library installed.
3. Return the processed result in the format specified by the user.

## Objectives
- Demonstrate the feasibility of using the Docling library in a web application flow.
- Validate the communication between the client (front-end) and the service that executes Docling.
- Test the flexibility of the library for different output formats.

## Arquitetura

A aplicação será composta pelos seguintes componentes, todos executados em contêineres Docker:**

1. **Web:**
   - Web interface for uploading files.
   - Field to specify the desired output format.
   - Display of results.

2. **Processing Service:**
   - Dedicated service that uses the Docling library to process the received file.
   - Returns the processed result to the client in the requested format.

## Contributions
Feel free to open issues or submit pull requests with improvements and suggestions.

## License
This project is licensed under the MIT License. See the `LICENSE` file for more details.


