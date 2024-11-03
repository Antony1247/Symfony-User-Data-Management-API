
# User Data Management API


#VIDEO EXPLANATION:

https://drive.google.com/drive/folders/1gCFSq_IIuOossvFgJs7BuYINdKMFrQDM?usp=drive_link


## Overview

This project provides a set of APIs to manage user data within a Symfony application. The API allows for operations such as uploading and viewing user data, backing up the database, restoring the database, and sending asynchronous email notifications.


## Table of Contents
- [Technologies](#technologies)
- [Requirements](#requirements)
- [API Endpoints](#api-endpoints)
  - [Upload and Store Data API](#upload-and-store-data-api)
  - [View Data API](#view-data-api)
  - [Backup Database API](#backup-database-api)
  - [Restore Database API](#restore-database-api)
- [Installation](#installation)
- [Usage](#usage)
- [Data.csv File](#datacsv-file)
- [Asynchronous Email Notifications](#asynchronous-email-notifications)

## Technologies

- **Language**: PHP
- **Framework**: Symfony
- **Database**: MySQL

## Requirements

- Create a `data.csv` file with the following columns: `name`, `email`, `username`, `address`, and `role`.
- Include 10 users with a mix of roles (e.g., `USER`, `ADMIN`), with at least 2-3 valid email addresses.

Example CSV data:
```csv
name,email,username,address,role
John Doe,john.doe@example.com,johndoe,123 Main St,USER
Jane Smith,jane.smith@example.com,janesmith,456 Elm St,ADMIN
Michael Johnson,michael.j@example.com,mjohnson,789 Pine St,USER
Emily Davis,emily.d@example.com,emilydavis,101 Oak St,ADMIN
David Brown,david.b@example.com,davidbrown,202 Maple St,USER
Sarah Wilson,sarah.w@example.com,sarahwilson,303 Birch St,USER
Daniel Lee,daniel.l@example.com,daniellee,404 Cedar St,ADMIN
Jessica Martinez,jessica.m@example.com,jessicam,505 Walnut St,USER
Paul Garcia,paul.g@example.com,paulgarcia,606 Ash St,USER
Laura Clark,laura.c@example.com,lauraclark,707 Cherry St,ADMIN
```

## API Endpoints

### Upload and Store Data API

- **Endpoint**: `POST /api/upload`
- **Description**: Allows an admin to upload the `data.csv` file to populate the database with user data.
- **Functionality**:
  - Parses the uploaded `data.csv` file.
  - Saves each user record to the database.
  - Sends an asynchronous email to each user upon successful storage.

### View Data API

- **Endpoint**: `GET /api/users`
- **Description**: Allows viewing of all user data stored in the database.

### Backup Database API

- **Endpoint**: `GET /api/backup`
- **Description**: Allows an admin to take a backup of the database.
- **Functionality**:
  - Generates a backup file (e.g., `backup.sql`) containing the current state of the database.

### Restore Database API

- **Endpoint**: `POST /api/restore`
- **Description**: Allows an admin to restore the database from the backup file.
- **Functionality**:
  - Restores the database using the provided backup file.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/your-repo-name.git
   cd your-repo-name
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Configure the `.env` file:
   - Set up your database credentials.
   - Set up email credentials for asynchronous notifications.

4. Run database migrations:
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

5. Start the Symfony server:
   ```bash
   symfony server:start
   ```

## Usage

### Upload Data

1. Ensure the `data.csv` file is available in the project directory.
2. Use the `POST /api/upload` endpoint to upload the CSV file.
3. Upon successful upload, each user receives an email notification.

### View User Data

1. Access `GET /api/users` to retrieve all user data.

### Database Backup and Restore

1. Access `GET /api/backup` to generate a `backup.sql` file.
2. Use `POST /api/restore` to restore the database using an existing `backup.sql` file.

## Data.csv File

The `data.csv` file must contain the following columns:
- **name**: Full name of the user.
- **email**: User's email address (must be unique).
- **username**: Unique username for the user.
- **address**: User's address.
- **role**: User role, either `USER` or `ADMIN`.

Sample CSV data:
```csv
name,email,username,address,role
John Doe,john.doe@example.com,johndoe,123 Main St,USER
Jane Smith,jane.smith@example.com,janesmith,456 Elm St,ADMIN
...
```

## Asynchronous Email Notifications

This project implements an asynchronous email notification system using Symfony's Messenger component. Users receive email notifications upon successful data storage.

### Features

- **Asynchronous Processing**: Emails are sent in the background, allowing the application to operate without delays during data storage.
- **Message Handler**: Utilizes a message handler to manage email notifications efficiently.

### Configuration

#### Environment Variables

To configure email credentials, update the `.env` file with your SMTP server details:

```dotenv
MAILER_DSN=smtp://username:password@smtp.example.com:port


