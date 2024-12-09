# nasa-api

Laravel Framework 11.34.2
PHP 8.2.12

**********************************

{
	"info": {
		"_postman_id": "dbfb7e60-5931-4c85-b251-5ed6aa78bb4c",
		"name": "nasa-api",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json",
		"_exporter_id": "20509973"
	},
	"item": [
		{
			"name": "http://127.0.0.1:8000/fetch-data",
			"protocolProfileBehavior": {
				"disableBodyPruning": true
			},
			"request": {
				"method": "GET",
				"header": [],
				"body": {
					"mode": "raw",
					"raw": "{\r\n    \"start_date\": \"2024-01-01\",\r\n    \"end_date\": \"2024-01-10\"\r\n}\r\n",
					"options": {
						"raw": {
							"language": "json"
						}
					}
				},
				"url": {
					"raw": "http://127.0.0.1:8000/fetch-data",
					"protocol": "http",
					"host": [
						"127",
						"0",
						"0",
						"1"
					],
					"port": "8000",
					"path": [
						"fetch-data"
					]
				}
			},
			"response": []
		}
	]
}
