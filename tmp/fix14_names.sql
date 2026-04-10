-- Targeted fix for 14 null-name drivers
-- 2026-04-10 01:46:45

-- Driver 45 (plate=DAT 2567): NO MATCH FOUND in CSV
-- Driver 91 (plate=NEO 6716, role=primary) => 'Boroy, Morlino'
UPDATE drivers SET first_name='Morlino', last_name='Boroy' WHERE id=91;

-- Driver 92 (plate=NEO 6716, role=secondary) => 'Bonsol, Henner'
UPDATE drivers SET first_name='Henner', last_name='Bonsol' WHERE id=92;


SELECT 'Total drivers' as label, COUNT(*) as count FROM drivers WHERE deleted_at IS NULL
UNION ALL
SELECT 'With names', COUNT(*) FROM drivers WHERE first_name IS NOT NULL AND first_name != '' AND deleted_at IS NULL
UNION ALL
SELECT 'Still null', COUNT(*) FROM drivers WHERE (first_name IS NULL OR first_name = '') AND deleted_at IS NULL;