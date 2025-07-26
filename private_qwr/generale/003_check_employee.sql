SELECT person_id AS full_id
FROM employees
WHERE person_id=:full_id
LIMIT 1;