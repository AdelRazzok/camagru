all:
	mkdir -p C:\Users\skurt\Desktop\camagru\postgres
	docker-compose -f ./docker-compose.yml up -d --build

up:
	docker-compose -f ./docker-compose.yml up -d

stop:
	docker-compose -f ./docker-compose.yml stop

down:
	docker-compose -f ./docker-compose.yml down -v

fclean: down
	docker system prune -af --volumes
	rm -rf C:\Users\skurt\Desktop\camagru

re: fclean all
