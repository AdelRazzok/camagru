#!/bin/bash

set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$DB_NAME" <<-EOSQL
    CREATE TABLE IF NOT EXISTS tokens (
        id SERIAL PRIMARY KEY,
        user_id INTEGER NOT NULL,
        token TEXT NOT NULL,
        type TEXT NOT NULL,
        expires_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
        created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
        updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
    );
EOSQL
