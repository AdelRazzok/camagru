SELECT
  datname AS database,
  pg_size_pretty(pg_database_size(datname)) AS size
FROM pg_database
ORDER BY pg_database_size(datname) DESC;

SELECT
  table_schema,
  table_name,
  pg_size_pretty(
    pg_total_relation_size(
      format('%I.%I', table_schema, table_name)::regclass
    )
  ) AS total_size
FROM information_schema.tables
WHERE table_schema NOT IN ('pg_catalog','information_schema')
ORDER BY pg_total_relation_size(format('%I.%I', table_schema, table_name)::regclass) DESC
LIMIT 20;

SELECT count(*) AS total_connections
FROM pg_stat_activity;

SELECT
  pid,
  usename,
  application_name,
  client_addr,
  state,
  now() - backend_start AS uptime,
  now() - query_start   AS query_duration,
  query
FROM pg_stat_activity
WHERE state = 'active'
ORDER BY query_duration DESC
LIMIT 20;

SELECT
  pid,
  now() - query_start AS duration,
  query
FROM pg_stat_activity
WHERE (now() - query_start) > interval '5 minutes'
  AND state = 'active';

SELECT
  CASE
    WHEN pg_last_xact_replay_timestamp() IS NULL THEN 'not a replica'
    ELSE now() - pg_last_xact_replay_timestamp()
  END AS replication_lag;

SELECT
  query,
  calls,
  round(total_time::numeric,2)   AS total_ms,
  round((total_time/calls)::numeric,2) AS avg_ms,
  rows
FROM pg_stat_statements
ORDER BY total_time DESC
LIMIT 5;
