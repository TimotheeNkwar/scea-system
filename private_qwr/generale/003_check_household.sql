SELECT household_id AS full_id
FROM households
WHERE household_id=:full_id AND household_id NOT NULL
OR household_short_id=:short_id AND household_shot_id NOT NULL
OR person_id=:full_id AND person_id NOT NULL
LIMIT 1;