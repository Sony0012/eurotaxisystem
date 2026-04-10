-- Check what units the 12 remaining null-name drivers are linked to
SELECT 
    d.id as driver_id, 
    d.license_number,
    COALESCE(up.plate_number, '-- NO PRIMARY UNIT --') as primary_unit_plate,
    COALESCE(us.plate_number, '-- NO SECONDARY UNIT --') as secondary_unit_plate,
    d.created_at
FROM drivers d
LEFT JOIN units up ON up.driver_id = d.id AND up.deleted_at IS NULL
LEFT JOIN units us ON us.secondary_driver_id = d.id AND us.deleted_at IS NULL
WHERE (d.first_name IS NULL OR d.first_name = '') 
  AND d.deleted_at IS NULL
ORDER BY d.id;
