SELECT DISTINCT 
campaigns.name,
countries.abbr AS country_code,
start_date AS started_at,
end_date AS ended_at,
(CASE WHEN public=1 THEN NOW() ELSE NULL END) AS published_at,
slug AS url,
campaigns.created_at,
campaigns.updated_at
FROM campaigns
LEFT JOIN countries
ON countries.id=campaigns.country_id
LEFT JOIN campaign_pages
ON campaign_pages.campaign_id=campaigns.id