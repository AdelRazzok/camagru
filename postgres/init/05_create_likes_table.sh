#!/bin/bash

set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$DB_NAME" <<-EOSQL
  CREATE TABLE IF NOT EXISTS likes (
    user_id     INTEGER NOT NULL,
    image_id    INTEGER NOT NULL,
    created_at  TIMESTAMP WITHOUT TIME ZONE NOT NULL,
    PRIMARY KEY (user_id, image_id),
    FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    FOREIGN KEY (image_id) REFERENCES images(id) ON DELETE CASCADE
  );

  CREATE INDEX IF NOT EXISTS idx_likes_image_id ON likes(image_id);
  CREATE INDEX IF NOT EXISTS idx_likes_user_id  ON likes(user_id);
EOSQL
