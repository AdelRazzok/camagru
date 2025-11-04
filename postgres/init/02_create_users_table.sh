#!/bin/bash

set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$DB_NAME" <<-EOSQL
  CREATE TABLE IF NOT EXISTS users (
    id                      SERIAL PRIMARY KEY,
    email                   TEXT NOT NULL,
    username                TEXT NOT NULL,
    password                TEXT NOT NULL,
    email_verified BOOLEAN  NOT NULL DEFAULT FALSE,
    email_notif_on_comment  BOOLEAN NOT NULL DEFAULT TRUE,
    created_at              TIMESTAMP WITHOUT TIME ZONE NOT NULL,
    updated_at              TIMESTAMP WITHOUT TIME ZONE NOT NULL
  );
EOSQL

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$DB_NAME" <<'EOSQL'
  INSERT INTO users (email, username, password, email_verified, email_notif_on_comment, created_at, updated_at)
    VALUES ('test@mail.com', 'test', '$2y$10$ncxHnUY5K89mxZ9WYe6Pk.D8wEWz/G0szrPirCiZOQPn/BmX3DggO', TRUE, TRUE, NOW(), NOW());
EOSQL
