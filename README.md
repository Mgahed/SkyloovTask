# Simple Task Management API

This project is a **Task Management API** built using **Laravel**. The API allows users to create, update, delete, and view tasks. It includes features such as task filtering by status and due date, pagination, and search functionality. The code follows best practices and uses clean, maintainable patterns like **Repository Pattern**, **Service Container**, and **Laravel Resources**.

## Features

- **Create Tasks**: Add tasks with a title, description, status, and due date.
- **View Tasks**: View all tasks with optional filtering by status and due date.
- **Update Tasks**: Update the title, description, status, and due date of a task.
- **Delete Tasks**: Remove tasks by ID.
- **Pagination**: Paginate the list of tasks.
- **Search**: Search tasks by title.
- **Validation**: All fields are validated before creating or updating tasks.
- **Error Handling**: Graceful handling of validation errors, missing resources, etc.

## Installation

1. Clone the repository
   ```bash
    git clone https://github.com/Mgahed/SkyloovTask mgahed
    ```
2. install the dependencies
   ```bash
   composer install
   ```
3. Create a new database and copy the `.env.example` file to `.env`. Update the database configuration in the `.env` file.
   ```bash
    cp .env.example .env
    ```
4. Generate a new application key
   ```bash
    php artisan key:generate
    ```
5. Run the database migrations (tables and seeders)
   ```bash
    php artisan migrate --seed
    ```
6. Start the Laravel development server
   ```bash
    php artisan serve
    ```
7. The API will be available at `http://localhost:8000`
8. You can test the API using tools like **Postman** or **cURL
9. You can run the tests using the following command
   ```bash
    php artisan test
    ```
10. Find postman collection in the root directory of the project named `Skyloov_task.postman_collection.json`

## API Endpoints

### Create Task
- **Endpoint**: `POST /api/tasks`
- **Request Parameters**:
    - `title`: string (required, max 255 characters)
    - `description`: text
    - `status`: enum ('pending', 'in_progress', 'completed')
    - `due_date`: date (must be in the future)
- **Example**:
```bash
curl -X POST http://localhost:8000/api/tasks \
    -d "title=New Task" \
    -d "description=This is a new task" \
    -d "status=pending" \
    -d "due_date=2024-12-01"
```

### View Tasks
- **Endpoint**: `GET /api/tasks`
- **Request Parameters**:
    - `status`: enum ('pending', 'in_progress', 'completed')
    - `due_date`: date (format: 'Y-m-d')
    - `search`: string (search by title)
    - `page`: integer (default: 1)
- **Example**:
```bash
curl http://localhost:8000/api/tasks?status=pending&due_date=2024-12-01
```

### Update Task
- **Endpoint**: `PUT /api/tasks}`
- **Request Parameters**:
    - `id`: task id (required)
    - `title`: string (max 255 characters)
    - `description`: text
    - `status`: enum ('pending', 'in_progress', 'completed')
    - `due_date`: date (must be in the future)
- **Example**:
```bash
curl -X PUT http://localhost:8000/api/tasks?id=1 \
    -d "title=Updated Task" \
    -d "status=in_progress"
```

### Delete Task
- **Endpoint**: `DELETE /api/tasks`
- **Request Parameters**:
    - `id`: task id (required)
- **Example**:
```bash
curl -X DELETE http://localhost:8000/api/tasks?id=1
```
