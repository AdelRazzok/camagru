all:
	mkdir -p /home/arazzok/Documents/camagru/database
	docker-compose -f ./docker-compose.yml up -d --build

up:
	docker-compose -f ./docker-compose.yml up -d

stop:
	docker-compose -f ./docker-compose.yml stop

down:
	docker-compose -f ./docker-compose.yml down -v

fclean: down
	docker system prune -af --volumes
	rm -rf /home/arazzok/Documents/camagru/database

re: fclean all
