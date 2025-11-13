# Livewire Chat App

### It's WIP but already usable

## Getting Started 🚀

These instructions will guide you through setting up the project on your local machine for development and testing.

### Prerequisites

You need to have installed the following software:

- PHP 8.3
- Composer 2.0.8
- Node 20.10.0
- Redis (optional, for Reverb scaling)

### Installing

Follow these steps to set up a development environment:

1. **Clone the repository**

    ```bash
    git clone https://github.com/mrpunyapal/livewire-chat-app.git
    ```

2. **Install dependencies**

    ```bash
    composer install
    ```

    ```bash
    npm install
    ```

3. **Duplicate the .env.example file and rename it to .env**

    ```bash
    cp .env.example .env
    ```

4. **Generate the application key**

    ```bash
    php artisan key:generate
    ```

    **Configure environment variables for Reverb (optional but recommended)**

    Add the following to your `.env` file for Reverb configuration:

    ```env
    BROADCAST_CONNECTION=reverb
    REVERB_APP_ID=your-app-id
    REVERB_APP_KEY=your-app-key  
    REVERB_APP_SECRET=your-app-secret
    REVERB_HOST="localhost"
    REVERB_PORT=8080
    REVERB_SCHEME=http
    VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
    VITE_REVERB_HOST="${REVERB_HOST}"
    VITE_REVERB_PORT="${REVERB_PORT}"
    VITE_REVERB_SCHEME="${REVERB_SCHEME}"
    ```

    **Queue Configuration**

    You can keep the queue driver as `sync` for local development, or start a queue worker for better performance:

    ```env
    # For local development (synchronous processing)
    QUEUE_CONNECTION=sync

    # Or use database queue for background processing
    QUEUE_CONNECTION=database
    ```

5. **Run migration and seed**

    ```bash
    php artisan migrate --seed
    ```

6. **Run the application**

    You need to run these commands in separate terminal windows:

    ```bash
    # Terminal 1: Start the frontend build process
    npm run dev
    ```

    ```bash
    # Terminal 2: Start the Laravel development server
    php artisan serve
    ```

    ```bash
    # Terminal 3: Start Reverb WebSocket server for real-time features
    php artisan reverb:start
    ```

    **Optional: If using database queue driver, start the queue worker in a fourth terminal:**

    ```bash
    # Terminal 4: Start queue worker (only if QUEUE_CONNECTION=database)
    php artisan queue:listen
    ```

## How to Test the Application 🧪

- Copy .env.testing.example to .env.testing
- Run the following commands

    ```bash
    php artisan key:generate --env=testing
    ```

    ```bash
    npm install && npm run build
    ```

    ```bash
    # Lint and refactor the code using Rector, then format with Pint
    composer lint
    composer test:lint

    # Run PHPStan
    composer test:types

    # Run type coverage
    composer test:type-coverage

    # Run the test suite
    composer test:unit

    # Run all the tests
    composer test
    ```
Check [composer.json](/composer.json#L57-L71) for more details on scripts.

### Give Feedback 💬

Give your feedback on [@MrPunyapal](https://x.com/MrPunyapal)

### Contribute 🤝

Contribute if you have any ideas to improve this project.
