-- Final null driver fix - 2026-04-10 01:52:54


-- Final count verification
SELECT 'Total active drivers' as label, COUNT(*) as count FROM drivers WHERE deleted_at IS NULL
UNION ALL SELECT 'With names', COUNT(*) FROM drivers WHERE first_name IS NOT NULL AND first_name != '' AND deleted_at IS NULL
UNION ALL SELECT 'Still null', COUNT(*) FROM drivers WHERE (first_name IS NULL OR first_name='') AND deleted_at IS NULL;

-- Sample of restored drivers
SELECT d.id, d.first_name, d.last_name, u.plate_number FROM drivers d
LEFT JOIN units u ON u.driver_id = d.id WHERE d.first_name IS NOT NULL
ORDER BY d.last_name LIMIT 20;