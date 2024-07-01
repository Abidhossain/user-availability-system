# User Availability Management System

## Introduction

This project is a simplified system for managing user availability in a service marketplace. Each user provides services
and sets their availability based on weekdays, and buyers can view this availability in their local timezone.

## Installation

1. Clone the repository:
    ```bash
    git clone <repository-url>
    ```

2. Navigate to the project directory:
    ```bash
    cd <project-directory>
    ```

3. Install the dependencies:
    ```bash
    composer install
    ```

4. Set up the environment file:
    ```bash
    cp .env.example .env
    ```

5. Generate the application key:
    ```bash
    php artisan key:generate
    ``` 
   ```bash
    php artisan key:generate --env=testing
    ```

6. Set up your database credentials in the `.env` file.

7. Set up your database credentials in the `phpunit.xml` file.

8. Run the migrations:
    ```bash
    php artisan test
    ```

## Usage

### Setting Availability

To set a user's weekly availability, make a POST request to `/api/availability` with the following JSON payload:

```json
{
    "availability": [
        {
            "day": "monday",
            "start": "09:00",
            "end": "12:00"
        },
        {
            "day": "monday",
            "start": "13:00",
            "end": "17:00"
        },
        {
            "day": "tuesday",
            "start": "10:00",
            "end": "14:00"
        }
    ]
}
```

### Additional Recommendations:

- **Environment Setup:** Ensure the environment variables for the test database in `phpunit.xml` match your local test
  database setup.
- **Database:** Make sure to create a separate test database and configure it accordingly in the `phpunit.xml` file to
  avoid any data conflicts.

This should cover all necessary instructions for running the tests along with your project's setup and usage details.
