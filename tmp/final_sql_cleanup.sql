-- Final cleanup for null-name drivers
-- Strategy: update any null-name driver that has a unit linked, using plate-based identification
-- Soft-delete orphaned null-name drivers (no unit linked)

-- Step 1: Soft-delete null-name drivers that have NO linked unit (orphaned records)
UPDATE drivers d
SET d.deleted_at = NOW()
WHERE (d.first_name IS NULL OR d.first_name = '')
  AND d.deleted_at IS NULL
  AND d.id NOT IN (
    SELECT driver_id FROM units WHERE driver_id IS NOT NULL AND deleted_at IS NULL AND driver_id != 0
    UNION
    SELECT secondary_driver_id FROM units WHERE secondary_driver_id IS NOT NULL AND deleted_at IS NULL AND secondary_driver_id != 0
  );

-- Step 2: For null-name drivers still linked to units, set placeholder name based on plate
UPDATE drivers d
INNER JOIN units u ON (u.driver_id = d.id OR u.secondary_driver_id = d.id)
SET d.first_name = CONCAT('Driver-', u.plate_number),
    d.last_name  = ''
WHERE (d.first_name IS NULL OR d.first_name = '')
  AND d.deleted_at IS NULL
  AND u.deleted_at IS NULL;

-- Final verification
SELECT 'Total active drivers' as label, COUNT(*) as count FROM drivers WHERE deleted_at IS NULL
UNION ALL
SELECT 'With names', COUNT(*) FROM drivers WHERE first_name IS NOT NULL AND first_name != '' AND deleted_at IS NULL
UNION ALL
SELECT 'Still null', COUNT(*) FROM drivers WHERE (first_name IS NULL OR first_name='') AND deleted_at IS NULL;

-- Show sample of all drivers with their unit assignment
SELECT 
    d.id, 
    d.first_name, 
    d.last_name,
    u_primary.plate_number as primary_unit,
    u_secondary.plate_number as secondary_unit,
    d.license_number
FROM drivers d
LEFT JOIN units u_primary ON u_primary.driver_id = d.id AND u_primary.deleted_at IS NULL
LEFT JOIN units u_secondary ON u_secondary.secondary_driver_id = d.id AND u_secondary.deleted_at IS NULL
WHERE d.deleted_at IS NULL
ORDER BY d.last_name, d.first_name
LIMIT 30;
