COMPOSE_FILE = ./docker-compose.yml

all:
	docker-compose -f $(COMPOSE_FILE) up -d --build

up:
	docker-compose -f $(COMPOSE_FILE) up -d

stop:
	docker-compose -f $(COMPOSE_FILE) stop

down:
	docker-compose -f $(COMPOSE_FILE) down

hard-down:
	docker-compose -f $(COMPOSE_FILE) down -v

restart:
	docker-compose -f $(COMPOSE_FILE) restart

prune:
	docker system prune -af --volumes

fclean:
	docker-compose -f $(COMPOSE_FILE) down -v

freset: prune all

re: fclean all

