UPDATE persons
SET person_short_id = :short_id,
    person_id = :full_id,
    person_short_idh = :short_idh,
    person_idh = :full_idh,
    first_name = :fname,
    second_name = :sname,
    third_name = :tname,
    birthdate = :bdate,
    birthplace = :bplace,
    gender = :gender,
    email = :email,
    phone_number_1 = :phone1,
    phone_number_2 = :phone2,
    role_id = :role_id
WHERE temp_id = :temp_id;