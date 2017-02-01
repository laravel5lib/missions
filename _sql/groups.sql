SELECT DISTINCT 
groups.name,
group_types.name AS type,
slugs.name AS url,
(case when (group_profiles.public = 1) THEN 'public' ELSE 'private' END) as visibility,
group_profiles.short_desc AS description,
address AS address_one,
city,
state,
zip,
countries.abbr AS country_code,
phone AS phone_one,
groups.email,
CONCAT('approved') AS status,
timezones.iana AS timezone,
socials.website AS website_url, 
(CASE WHEN socials.facebook IS NOT NULL AND socials.facebook <> '' THEN CONCAT('https://facebook.com/',socials.facebook) ELSE NULL END) AS facebook_url, 
(CASE WHEN socials.instagram IS NOT NULL AND socials.instagram <> '' THEN CONCAT('https://instagram.com/',socials.instagram) ELSE NULL END) AS instagram_url, 
(CASE WHEN socials.twitter IS NOT NULL AND socials.twitter <> '' THEN CONCAT('https://twitter.com/',socials.twitter) ELSE NULL END) AS twitter_url, 
(CASE WHEN socials.linked_in IS NOT NULL AND socials.linked_in <> '' THEN CONCAT('https://linkedin.com/',socials.linked_in) ELSE NULL END) AS linkedin_url, 
(CASE WHEN socials.google IS NOT NULL AND socials.google <> '' THEN CONCAT('https://plus.google.com/+',socials.google) ELSE NULL END) AS google_url, 
(CASE WHEN socials.vimeo IS NOT NULL AND socials.vimeo <> '' THEN CONCAT('https://vimeo.com/',socials.vimeo) ELSE NULL END) AS vimeo_url, 
(CASE WHEN socials.youtube IS NOT NULL AND socials.youtube <> '' THEN CONCAT('https://youtube.com/',socials.youtube) ELSE NULL END) AS youtube_url, 
groups.created_at,
groups.updated_at,
GROUP_CONCAT(users.email) AS manager_emails
FROM groups
LEFT JOIN countries
ON countries.id=groups.country_id
LEFT JOIN timezones
ON groups.timezone_id=timezones.id
INNER JOIN group_types
ON group_types.id=groups.group_type_id
LEFT JOIN group_profiles
ON group_profiles.group_id=groups.id
LEFT JOIN slugs
ON slugs.slugable_id=group_profiles.id
AND slugs.slugable_type='GroupProfile'
LEFT JOIN socials
ON socials.socialable_id=groups.id
AND socials.socialable_type='Group'
LEFT JOIN group_user
ON group_user.group_id=groups.id
LEFT JOIN users
ON group_user.user_id=users.id
GROUP BY groups.id