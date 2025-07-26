
SELECT person_id AS full_id
FROM persons
WHERE person_id=:full_id
OR person_short_id=:short_id
OR email=:email
OR phone_number_1=:phone1
OR phone_number_2=:phone2
LIMIT 1;