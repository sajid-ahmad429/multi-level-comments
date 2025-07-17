# Multi-Level Commenting System with Recursive Depth Check

This is a multi-level commenting system built with Laravel 12 and Livewire 3, supporting nested replies up to a specified maximum depth.

## Features

* **Multi-Level Comments:** Supports nested replies for a hierarchical comment structure.
* **Depth Limitation:** Enforces a configurable maximum nesting depth (default: 3 levels). Replies are disabled once the maximum depth is reached.
* **Livewire Integration (Optional/Preferred):**
    * Dynamic, real-time display of comments and replies without page reloads.
    * Livewire components for comment submission and recursive comment listing.
* **Scheduled Command:** An Artisan command to periodically delete comments with empty content, registered to run on a schedule.
* **Database Relationships:** Proper Eloquent relationships for Posts and Comments, including self-referencing for replies.

## Technologies Used

* **Laravel 12.x:** PHP Framework
* **Livewire 3.x:** Full-stack framework for Laravel
* **Tailwind CSS:** For styling
* **MySQL:** Database (configurable)

## Installation Steps

1.  **Clone the Repository:**
    ```bash
    git clone [YOUR_REPOSITORY_LINK]
    cd multi-level-comments
    ```

2.  **Install Composer Dependencies:**
    ```bash
    composer install
    ```

3.  **Install Node.js Dependencies & Compile Assets:**
    ```bash
    npm install
    npm run dev
    ```
    (You can use `npm run build` for production assets)

4.  **Environment Configuration:**
    Create a copy of the `.env.example` file and name it `.env`:
    ```bash
    cp .env.example .env
    ```
    Generate an application key:
    ```bash
    php artisan key:generate
    ```

5.  **Database Configuration:**
    Open your `.env` file and configure your database connection.
    **For Mysql (recommended for quick local setup):**
    ```env
    DB_CONNECTION=Mysql
    # Comment out other DB_ sections
    # DB_HOST=127.0.0.1
    # DB_PORT=3306
    # DB_DATABASE=laravel
    # DB_USERNAME=root
    # DB_PASSWORD=
    ```
    Then, create an empty `database.Mysql` file in the `database` directory:
    ```bash
    touch database/database.Mysql
    ```
    **For MySQL/PostgreSQL:**
    Update `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` accordingly.

6.  **Run Migrations and Seed Database:**
    This will create the necessary tables and populate them with some dummy posts and comments.
    ```bash
    php artisan migrate:fresh --seed
    ```
    A default user will be created:
    * **Email:** `test@example.com`
    * **Password:** `password`

## Running the Project

1.  **Start the Laravel Development Server:**
    ```bash
    php artisan serve
    ```
    The application will typically be available at `http://127.0.0.1:8000`.

2.  **Access the Application:**
    * Open your web browser and go to `http://127.0.0.1:8000`.
    * Log in using the seeded `test@example.com` credentials.
    * Navigate to `/posts` to see the list of posts. Click on any post to view its details and the commenting system.

## Usage Guidelines

* **View Posts:** Navigate to `/posts` to see a list of all seeded posts.
* **View Comments:** Click on any post title from the list to view its comments.
* **Add Comments:** Use the "Add a New Comment" form at the top of the comments section.
* **Reply to Comments:** Click the "Reply" button next to any comment that has not reached the maximum depth.
* **Depth Limit:** The system enforces a maximum depth of `3` levels (configurable in `app/Models/Comment.php` via `MAX_DEPTH`). Comments at this depth will not show a "Reply" option.

## Scheduled Command

The project includes an Artisan command `comments:delete-empty` that deletes comments with empty content.

* **Manual Trigger:** To test the scheduled command (as required by the assignment to avoid time dependency issues), simply run:
    ```bash
    php artisan schedule:run
    ```
    This will execute the `comments:delete-empty` command if it's scheduled to run (which it is, every minute). You can add comments with empty content (e.g., by temporarily removing validation in `CreateComment` for testing) and then run this command to see them removed.

## Notes on Implementation Approach

* **Recursive Comments:** Achieved using a single `CommentList` Livewire component that recursively renders itself for nested replies, passing the `parent_comment_id` and `depth` accordingly.
* **Comment Submission:** Handled by a `CreateComment` Livewire component that emits an event (`comment-added`) upon successful submission, triggering a refresh of the `CommentList` components.
* **Depth Enforcement:**
    * The `Comment::MAX_DEPTH` constant defines the limit.
    * The `Comment` model's `boot` method automatically calculates `depth` and throws an exception if the `MAX_DEPTH` would be exceeded, ensuring data integrity at the model level.
    * The `canHaveReplies()` method in the `Comment` model and checks within Livewire components disable the "Reply" button dynamically in the UI.
    * The `CreateComment` Livewire component also includes an early check to provide user feedback before attempting to save a comment that would exceed depth.
* **Livewire Keys (`wire:key`):** Crucial for proper Livewire rendering and state management, especially with recursive components and dynamic forms (like multiple reply forms).
* **UI/UX:** Simple Tailwind CSS styling is applied for a clean look, prioritizing functionality as requested. Authentication scaffolding is provided by the Livewire starter kit.

---

## Live Deployment (Placeholder)

[YOUR_LIVE_DEPLOYMENT_URL_HERE]

*(Example: https://your-multi-level-comments.railway.app)*

---