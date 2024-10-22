all:
	mkdir -p C:\Users\skurt\Desktop\camagru\postgres
	docker-compose -f docker-composer.yml up -d --build

up:
	docker-compse -f docker-composer.yml up -d

down:
	docker-compose -f docker-composer.yml down

fclean: down
	docker system prune -af --volumes
	rm -rf C:\Users\skurt\Desktop\camagru

re: fclean all
