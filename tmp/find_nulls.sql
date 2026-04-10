-- Show all drivers with NULL names and their linked unit plate
SELECT d.id as driver_id, d.license_number,
       u.id as unit_id, u.plate_number,
       CASE WHEN u.driver_id = d.id THEN 'primary' ELSE 'secondary' END as role
FROM drivers d
LEFT JOIN units u ON (u.driver_id = d.id OR u.secondary_driver_id = d.id)
WHERE (d.first_name IS NULL OR d.first_name = '')
  AND d.deleted_at IS NULL
ORDER BY u.plate_number;
