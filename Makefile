start:
	docker-compose up -d
build:
	docker-compose up -d --build

stop:
	docker-compose down

api1:
	docker exec -it api_app bash