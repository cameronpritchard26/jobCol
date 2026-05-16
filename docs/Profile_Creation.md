# User Profile Creation Flow

## Overview

This document outlines the flow for user profile creation, editing, and viewing in the JobCol application. After creating an account, both students and employers can create a profile for themselves to showcase their information to other users on the platform. Most of the functionality of the application revolves around the user profile, so it is important to understand how it works.

## Topics
- [Profile Overview](#profile-overview)
- [Student Profile Overview](#student-profile-overview)
    - [Education Entries](#education-entries)
    - [Experience Entries](#experience-entries)
- [Employer Profile Overview](#employer-profile-overview)
- [Profile Creation Flow](#profile-creation-flow)
    - [Education and Experience Entry Creation](#education-and-experience-entry-creation)
    - [Profile Picture Management](#profile-picture-management)
- [Profile Editing Flow](#profile-editing-flow)
- [Profile Viewing Flow](#profile-viewing-flow)

## Relevant Files
- [Employer Profile Model](../app/Models/EmployerProfile.php) - Defines the structure and relationships for employer profiles
- [Student Profile Model](../app/Models/StudentProfile.php) - Defines the structure and relationships for student profiles
- [Education Entry Model](../app/Models/EducationEntry.php) - Defines the structure and relationships for student education entries
- [Experience Entry Model](../app/Models/ExperienceEntry.php) - Defines the structure and relationships for student work experience entries
- [Profile Controller](../app/Http/Controllers/ProfileController.php) - Profile controller interface that delegates to the appropriate controller based on account type
- [Employer Profile Controller](../app/Http/Controllers/EmployerProfileController.php) - Handles employer profile creation, editing, and viewing
- [Student Profile Controller](../app/Http/Controllers/StudentProfileController.php) - Handles student profile creation, editing, and viewing
- [Education Entry Controller](../app/Http/Controllers/EducationEntryController.php) - Handles education entry creation, editing, and viewing
- [Experience Entry Controller](../app/Http/Controllers/ExperienceEntryController.php) - Handles experience entry creation, editing, and viewing
- [Profile Picture Controller](../app/Http/Controllers/ProfilePictureController.php) - Handles profile picture upload and management

## Profile Overview

Profiles serve as the primary means for both students and employers to showcase their information to other users on the platform. They are essential for building connections and opportunities within the application.

The app allows users to create, edit, and view their own profiles, as well as view the profiles of other users on the platform. The app also allows users to upload profile pictures to their profiles.

## Student Profile Overview

Student profiles contain information relating to a student's current and previous education, as well as their work experience.

Currently, student profiles contain the following information:
- Name
- School
- Major
- Graduation Date
- About Me
- Education History via Education Entries
- Work Experience via Experience Entries

When creating a profile, the user is required to enter their name, school, major, and graduation date. They may optionally add an about me section, education history, and work experience.

### Education Entries

JobCol allows students to detail their educational background by adding education entries to their profile. Profiles are limited to a maximum of 3 education entries, and are displayed in chronological order with the most recent entry at the top.

Each education entry contains the following information:
- Degree
- School
- Start Year
- End Year

### Experience Entries

JobCol also allows students to detail their work experience by adding experience entries to their profile. Profiles are limited to a maximum of 3 experience entries, and are also displayed in chronological order with the most recent entry at the top.

Each experience entry contains the following information:
- Title
- Company
- Start and End Dates
- Description

Students may forgo adding a description of their experience, but all other fields are required.

## Employer Profile Overview

Employer profiles contain information relating to a specific company that students can view to learn more about the company and its opportunities.

Currently, employer profiles contain the following information:
- Name
- Industry
- Location
- Website
- Description

Like with student profiles, employer profiles may optionally include a description about themselves, but all other fields are required.

## Profile Creation Flow

The process for creating a profile is largely the same for both students and employers, with the main difference being the information required for each profile type.

The process for creating a profile involves the following steps:
1. After logging in, the user clicks on the "Profile" link of the navigation bar on the top of the page, which takes them to their profile page
2. If the user doesn't currently have a profile, they will be prompted to create one
3. The user fills out the required information for their profile type
4. The user clicks the "Create Profile" button to create their profile
5. The system validates the provided information according to the rules for that profile type
6. If the validation passes, the profile is created and the user is redirected to their profile page
7. If the validation fails, the user is redirected back to the profile creation page with an error message

### Education and Experience Entry Creation

When a student is creating a profile, they will have the option to add an education or experience entry. This is done through a Add Education/Experience link on their profile page.

The process for creating an education or experience entry involves the following steps:
1. The user clicks on the Add Education/Experience link
2. The user is taken to a page where they can fill out the information for the entry
3. The user clicks the "Create Entry" button to create the entry
4. The system validates the provided information according to the rules for that entry type
5. If the validation passes, the entry is created and the user is redirected back to their profile page
6. If the validation fails, the user is redirected back to the entry creation page with an error message

### Profile Picture Management

Users will have the ability to upload a profile picture for their profile. This will be done through a button on their profile page. The profile picture will be stored in the public disk and will be accessible to other users. This process utilizes a queue to handle file processing and storage without interrupting the user's experience.

The process for uploading a profile picture is as follows:
1. The user clicks the "Upload Profile Picture" link below their current profile picture
2. The user is allows to upload an image file, with a maximum size of 5MB
3. The user clicks the "Upload" button to upload the image
4. The system validates the provided image file to ensure it's within the size limit
5. If the validation passes, the image is stored in a temporary location on the local disk before being processed by a queue job
6. When the queue job is dispatched, it retrieves the image from the temporary location if it exists and extracts the raw image data
7. The image is then resized to 300x300 pixels while preserving image quality
8. The image is then converted to JPEG format and is given a unique filename to prevent conflicts before being stored in the public disk
9. The job then deletes the user's old profile picture if one exists, updates the profile with the new profile picture, then deletes the temporary image file
10. Once the queue job is complete, the user is redirected back to their profile page with a success message

## Profile Editing Flow

If a user wishes to edit their profile, they will have the option to do so through a button on their profile page. This will take them to a page where they can edit their profile information.

The process for editing a profile involves the following steps:
1. The user clicks the "Edit" button on their profile page to navigate to the profile editing page
2. The form is pre-filled with the user's current profile information
3. The user can then edit any fields they wish to change
4. The user clicks the "Update Profile" button to update their profile
5. The system validates the provided information according to the rules for that entry type
6. If the validation passes, the profile is updated and the user is redirected back to their profile page
7. If the validation fails, the user is redirected back to the profile editing page with an error message

## Profile Viewing Flow

Users can easily view their own profile via the "Profile" link in the navigation bar. To view another user's profile, they can search for their name using the search bar in the navigation bar. The search will scan through all user profiles for a match and display the results in a list. Clicking on a result will take the user to that user's profile page.