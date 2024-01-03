To set up a PHP Laravel project after cloning it from GitHub, you can follow these steps:

1. **Clone the GitHub repo**: Use the command `git clone https://github.com/huuhieunguyen/cookhub-backend-api.git` to clone the project onto your local computer. Make sure you have git installed locally on your computer first.

2. **Navigate into your project**: Use the command `cd cookhub-backend-api` to move your terminal working location to the project file.

3. **Install Composer Dependencies**: Run `composer install` to install the necessary dependencies.

4. **Create .env file**: Make a copy of `.env.example` and rename it to `.env`.

5. **Generate Application Key**: Run `php artisan key:generate` to generate the application key.

6. **Configure .env file**: Update the `.env` file with your database credentials.</br>
    Change the 'database name' & 'username' & 'password'</br>
    DB_HOST=localhost</br>
    DB_DATABASE=<own_databse_name> </br>
    DB_USERNAME= </br>
    DB_PASSWORD= </br>

7. **Run Migrations**: Use `php artisan migrate` to create the tables in your database.

8. **Seed the Database (Optional)**: If your project includes seeders, run `php artisan db:seed` to populate your database with any necessary data.

9.  **Start the Server**: Finally, use `php artisan serve` to start the Laravel development server.

Please ensure that you have PHP, Composer, and Laravel installed on your system. If you encounter any issues, please refer to the official Laravel documentation or the project's README file..