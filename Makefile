all:
	docker-compose -f docker-composer.yml up -d --build

up:
	docker-compse -f docker-composer.yml up -d

down:
	docker-compose -f docker-composer.yml down

fclean:
	docker system prune -af --volumes

re: fclean all
