SELECT coupon_id AS full_id
FROM coupons
WHERE coupon_id=:full_id AND coupon_id NOT NULL
OR coupon_short_id=:short_id AND coupon_shot_id NOT NULL
LIMIT 1;