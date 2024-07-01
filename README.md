# Correio Sesi

Correio Sesi is a social network platform inspired by the likes of Twitter and Facebook, tailored for the community of @sesisenaipr.org.br users. It provides a space for communication, updates, and social interaction within the Sesi Senai PR network.

## Features

- **User Registration:** Requires @sesisenaipr.org.br email for registration.
- **Social Networking:** Share posts, updates, and interact with other users.
- **Teams Integration:** Previously utilized Teams API for verifying user existence within the school network (source code lost).

## Technology Stack

- **Initial Implementation:** PHP (MVP)
- **Current Implementation:** Reimplementation needed (originally migrated to Node.js, now requires PHP with MySQL)
- **Database:** MySQL
- **Requirements:** PHP, MySQL

## Getting Started

To set up and run the application, follow these steps:

1. **Clone the repository:**
   ```bash
   git clone https://github.com/felipeczpaz/CorreioSesi.git
   cd CorreioSesi
   ```
   
2. **Install Composer Dependencies:**
   ```bash
   composer install
   ```
   
3. **Database Setup:**
   - Create a MySQL database for Correio Sesi.
   - Import the SQL schema and initial data from `DATABASE.sql`.

4. **Configure PHP:**
   - Ensure PHP is installed and configured on your server or local environment.
   - Create a .env file based on .env.example and configure necessary environment variables.

5. **Run the application:**
   - Deploy the PHP files to your web server or run locally using PHP's built-in server.

6. **Access the application:**
   - Open your web browser and navigate to `http://localhost/CorreioSesi` (or the appropriate URL).

## Contributing

Since the original Node.js implementation's source code is lost, contributions will involve reimplementing features in PHP. Contributions are welcome! Please fork the repository, make your changes, and submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE).
