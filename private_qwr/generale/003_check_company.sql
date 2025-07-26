SELECT company_id AS full_id
FROM companies
WHERE company_id=:full_id AND company_id NOT NULL
OR company_short_id=:short_id AND company_shot_id NOT NULL
LIMIT 1;