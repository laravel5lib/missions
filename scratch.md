# Medical Release

Automatically purge unmodified medical releases 5 years or older.

Force users to verify (and update if needed) their medical release for every trip.
Must check box saying they have verified.

Users need to upload their doctor's note.

New question flow:

- Are you currently taking any medication?
    - Yes
        - Show list of conditions
        - Are you taking medication for any of these conditions?
        - Doctor Note Required
        - Show list of allergies
        - Are you taking medication for any of these allergies?
    - No
        - Do you have any medically diagnosed conditions?
            - Yes
                - Show list of conditions
                - Please select any conditions you are currenlty experiencing
                - Are you taking medication for any of these conditions?
                    - Yes
                    - No
                - Doctor Note Required
            - No
        - Do you have any known allergies?
            - Yes
                - Show list of allergies
                - Are you taking medication for any of these allergies?
                - Doctor Note Required
            - No

# Passports

Re-using passports:

Force users to verify their passport details.
Must check box saying they have verified.

Provide a better explanation and example photo:

A photo of your passport is required for verification.
Please upload a single, full-color photo (.jpg or .png) of your passport.
Please be sure that both the photo page and signed signiture page are clearly visible and legible.
[ show example photo ]
    - All four corners of the passport must be within the photo frame.
    - Do your best to avoid any shadows or glares.

- Has your name legally changed since your last trip?
    - Yes
        - See edit form
        - Require new upload
    - No
- Has your passport been renewed or replaced since your last trip?
    - Yes
        - See edit form
        - Require new upload
    - No
- Hey, your passport is expired or will expire within 6 months of the trip!
    - See edit form
    - Require new upload


# Requesting Requirement Changes

Trip Rep flags a requirement as "Needs Attention".
Selects the reason why or types a reason.
Sends an email to the user.

# Domestic Travel

User must email photo(s) or forward copies of their domestic travel details.
Trip rep will then enter those details into the admin and upload a screenshot(s) of the email(s) or photo(s).

# Referrals

Send automated reminders to recipients of referral requests until the referral is completed.

# Influencer Application

Admins need to "approve" or "deny" applications (can only be seen by admins).
Admins need ability to rate 1-5 star

# Media Credential

Admins can rate their ability from 1-5 star

# User Leadership Ratings

Admins can rate user's leadership level, 1-5 star. (Staff & Admins only)

# Tagging
Need to tag users/reservations:
VIP, Do Not Contact, Team Facilitator, Pastor
Need to show in exports.
Can filter by tags.



# DASHBOARD

- Need to show all travel requirements of all statuses (emphasis on status).
- Prefer dates over timeframes.
- Users are unaware of companions feature. Move this to a "step" on the dashboard.
- Seeing two navigation bars has been confusing users (maybe scrap left side nav bar)
    - Most are using their phone don't even see this.
    - User details should be listed in menu aside (i.e. my profile, etc.)
    - Rename "settings" to "account" or "profile & settings" or just "profile"

# NAVIGATION

- Menu does not remain consistent.
    - Certain links are only available from the dashboard screen

# Contact Information

- Mailing addresses need to be verified on reservations!!!
- Contact information needs to be verified on reservations!!!

# Payments Dues

- Confusing, overcomplicated
    - Users care about how much they've raised, how much still to raise, and when payment is due
    - Some users might want to see a breakdown but not most

# Trip Interests

- Hide old trip interests
- Phone number required
- Default todos for each new interest:
    - First contact
    - Second contact
    - Third contact
    - Fourth contact

# Todos
- fix bug where todos not updating on reservations from trip


