SELECT d.id, d.first_name, d.last_name, u.plate_number, '=primary' as role
FROM drivers d INNER JOIN units u ON u.driver_id = d.id
WHERE d.first_name IS NOT NULL AND d.first_name != ''
ORDER BY u.plate_number LIMIT 20;

SELECT d.id, d.first_name, d.last_name, u.plate_number, '=secondary' as role
FROM drivers d INNER JOIN units u ON u.secondary_driver_id = d.id
WHERE d.first_name IS NOT NULL AND d.first_name != ''
ORDER BY u.plate_number LIMIT 10;

SELECT COUNT(*) as total_drivers FROM drivers;
SELECT COUNT(*) as drivers_with_name FROM drivers WHERE first_name IS NOT NULL AND first_name != '';
SELECT COUNT(*) as drivers_null_name FROM drivers WHERE first_name IS NULL OR first_name = '';
