SELECT 
groups.name AS group_name,
CASE 
    WHEN trip_types.type = 'Full Week Ministry' THEN 'ministry'
    WHEN trip_types.type = 'Full Week Medical' THEN 'medical'
    WHEN trip_types.type = 'Full Week Leader' THEN 'leader'
    WHEN trip_types.type = 'Full Week Family Ministry' THEN 'family'
    ELSE 'ministry'
END AS type,
countries.abbr AS country_code,
CASE
    WHEN difficulty_id = 1 THEN 'level_1'
    WHEN difficulty_id = 2 THEN 'level_2'
    WHEN difficulty_id = 3 THEN 'level_3'
    ELSE 'level_1'
END AS difficulty,
spots,
companion_limit,
CASE
    WHEN trip_pages.public = 1 THEN 'public'
    ELSE 'private'
END AS visibility,
CONCAT('### WHAT TO EXPECT', '
', trip_pages.what_to_expect, '
### WHAT\'S INCLUDED IN MY TRIP REGISTRATION?
', trip_pages.included, '
### WHAT\'S NOT INCLUDED IN MY TRIP REGISTRATION?
', trip_pages.not_included, '
### PRE-TRIP TRAINING
', trip_pages.training, '
### HOW YOU WILL GET THERE
', trip_pages.flight_information) AS description,
GROUP_CONCAT(DISTINCT travelers.traveler) AS prospects,
GROUP_CONCAT(roles.name) AS team_roles,
CONCAT('Send shirt', ',', 'Send wrist band', ',', 'Enter into LGL', ',', 'Send launch guide', ',', 'Send luggage tag') AS todos,
trips.start_date AS started_at,
trips.end_date AS ended_at,
CASE 
    WHEN trip_deadlines.registration IS NULL THEN DATE_SUB(trips.start_date, INTERVAL 51 DAY)
    ELSE trip_deadlines.registration
END AS closed_at,
NOW() AS published_at,
trips.created_at,
trips.updated_at
FROM trips
LEFT JOIN groups
ON groups.id=trips.group_id
LEFT JOIN trip_types
ON trip_types.id=trips.trip_type_id
LEFT JOIN trip_deadlines
ON trip_deadlines.trip_id=trips.id
LEFT JOIN trip_pages
ON trip_pages.trip_id=trips.id
LEFT JOIN travelerables
ON travelerables.travelerable_id=trips.id
AND travelerables.travelerable_type='Trip'
LEFT JOIN travelers
ON travelers.id=travelerables.traveler_id
LEFT JOIN role_trip
ON role_trip.trip_id=trips.id
LEFT JOIN roles
ON role_trip.role_id=roles.id
LEFT JOIN campaigns
ON campaigns.id=trips.campaign_id
LEFT JOIN countries
ON campaigns.country_id=countries.id
WHERE campaign_id=54
GROUP BY trips.id