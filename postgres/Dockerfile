FROM postgres:17

COPY init/ /docker-entrypoint-initdb.d/

RUN chmod +x /docker-entrypoint-initdb.d/*.sh
