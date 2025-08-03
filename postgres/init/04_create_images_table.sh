#!/bin/bash

set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$DB_NAME" <<-EOSQL
  CREATE TABLE IF NOT EXISTS images (
    id            SERIAL PRIMARY KEY,
    original_name TEXT NOT NULL,
    mime_type     TEXT NOT NULL,
    extension     TEXT NOT NULL,
    created_at    TIMESTAMP WITHOUT TIME ZONE NOT NULL,
    updated_at    TIMESTAMP WITHOUT TIME ZONE NOT NULL,
    deleted_at    TIMESTAMP WITHOUT TIME ZONE
  );
EOSQL
