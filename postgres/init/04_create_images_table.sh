#!/bin/bash

set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$DB_NAME" <<-EOSQL
  CREATE TABLE IF NOT EXISTS images (
    id            SERIAL PRIMARY KEY,
    user_id       INTEGER NOT NULL,
    file_path     TEXT NOT NULL UNIQUE,
    extension     TEXT NOT NULL,
    mime_type     TEXT NOT NULL,
    original_name TEXT NOT NULL,
    file_size     BIGINT NOT NULL,
    created_at    TIMESTAMP WITHOUT TIME ZONE NOT NULL,
    updated_at    TIMESTAMP WITHOUT TIME ZONE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
  );
EOSQL
