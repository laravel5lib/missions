SELECT DISTINCT 
CONCAT(first_name, ' ', last_name) AS name, 
email, 
password,
contacts.address, 
contacts.city, 
contacts.state, 
contacts.zip, 
countries.abbr AS country_code, 
contacts.home_phone AS phone_one, 
contacts.cell_phone AS phone_two, dob AS birthday, 
gender, 
status, 
bio, 
(case when (public = 1) THEN 'public' ELSE 'private' END) as visibility, 
slugs.name AS url, 
socials.website AS website_url, 
(CASE WHEN socials.facebook IS NOT NULL AND socials.facebook <> '' THEN CONCAT('https://facebook.com/',socials.facebook) ELSE NULL END) AS facebook_url, 
(CASE WHEN socials.instagram IS NOT NULL AND socials.instagram <> '' THEN CONCAT('https://instagram.com/',socials.instagram) ELSE NULL END) AS instagram_url, 
(CASE WHEN socials.twitter IS NOT NULL AND socials.twitter <> '' THEN CONCAT('https://twitter.com/',socials.twitter) ELSE NULL END) AS twitter_url, 
(CASE WHEN socials.linked_in IS NOT NULL AND socials.linked_in <> '' THEN CONCAT('https://linkedin.com/',socials.linked_in) ELSE NULL END) AS linkedin_url, 
(CASE WHEN socials.google IS NOT NULL AND socials.google <> '' THEN CONCAT('https://plus.google.com/+',socials.google) ELSE NULL END) AS google_url, 
(CASE WHEN socials.vimeo IS NOT NULL AND socials.vimeo <> '' THEN CONCAT('https://vimeo.com/',socials.vimeo) ELSE NULL END) AS vimeo_url, 
(CASE WHEN socials.youtube IS NOT NULL AND socials.youtube <> '' THEN CONCAT('https://youtube.com/',socials.youtube) ELSE NULL END) AS youtube_url, 
users.created_at, 
users.updated_at, 
last_login AS login_at, 
timezones.iana AS timezone
FROM users
LEFT JOIN timezones
ON users.timezone_id=timezones.id
LEFT JOIN contacts
ON contacts.user_id=users.id
LEFT JOIN countries
ON contacts.country_id=countries.id
LEFT JOIN user_profiles
ON user_profiles.user_id=users.id
LEFT JOIN slugs
ON slugs.slugable_id=user_profiles.id
AND slugs.slugable_type='UserProfile'
LEFT JOIN socials
ON socials.socialable_id=users.id
AND socials.socialable_type='User'