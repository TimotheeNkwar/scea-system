
SELECT sp.person_short_id AS short_id,
    sp.email AS email,
    sp.phone_number_1 AS phone,
    sl.password AS password,
    sl.role_id AS role_id
FROM persons sp
    INNER JOIN login sl ON sp.person_id = sl.person_id
WHERE sp.person_short_id = :short_id AND sp.visible = TRUE AND sl.visible = TRUE
    OR sp.person_id = :full_id AND sp.visible = TRUE AND sl.visible = TRUE
    OR sp.email = :email AND sp.visible = TRUE AND sl.visible = TRUE
    OR sp.phone_number_1 = :phone1 AND sp.visible = TRUE AND sl.visible = TRUE
    OR sp.phone_number_2 = :phone2 AND sp.visible = TRUE AND sl.visible = TRUE
LIMIT 1;