# VMM Investment and Lottery

## Requirements

-   PHP version **8.2.0** or higher
-   Composer
-   Node.js and npm

## Installation Steps

1. **Open the project in your preferred code editor.**

2. **Rename the environment file:**

    - Rename `.env.example` to `.env`.

3. **Create a database:**

    - Open MySQL and create a new database named `vmm_db`.

4. **Install PHP dependencies:**

    - composer update

5. **Install JavaScript dependencies::**

    - npm install

6. **Generate the application key:**

    - php artisan key:generate

7. **Run migrations and seed the database:**

    - php artisan migrate:refresh --seed

8. **Compile assets:**

    - npm run dev

9. **Run the project:**

    - php artisan serve

10. **Access the application:**
    - Open your web browser and go to http://localhost:8000.
