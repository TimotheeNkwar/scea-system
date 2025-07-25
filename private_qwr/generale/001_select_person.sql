SELECT row_id,
    enlisment_date AS edate
FROM persons
WHERE temp_id = :temp_id
LIMIT 1;