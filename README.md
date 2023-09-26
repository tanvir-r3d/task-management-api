# TASK MANAGEMENT

Api of Task Management Backend

## Requirements

* Docker - *
* docker-compose - *

## Installation

```bash
  cp .env.example .env
```

##### Write your NGINX_PORT and PMA_PORT in .env and setup mail config and database configs.

* Set QUEUE_CONNECTION to database

```bash 
    sudo docker-compose up -d 
    sudo docker exec -it task_management_app bash
    php artisan key:generate
    php artisan jwt:secret
    php artisan migrate
    php artisan db:seed
```

* Default credentials are ```admin@admin.com``` - ```123456```

### API Endpoints

```bash
   -Auth
        1. [POST] 
            'api/v1/users'
        2. [POST] 
            'api/v1/auth/login' 
        3. [GET]  
            'api/v1/auth/me'
        4. [POST]  
            'api/v1/auth/send-verification-mail'
        5. [POST]  
            'api/v1/auth/check-verification-code'
        6. [POST]  
            'api/v1/auth/logout'
   -Task
        1. [GET] 
            'api/v1/tasks'
        2. [POST] 
            'api/v1/tasks'
        3. [GET]  
            'api/v1/tasks/{id}'
        4. [PUT]
            'api/v1/tasks/{id}'
        5. [DELETE]
            'api/v1/tasks/{id}'
            
        -Task Comment
             6. [GET]
                'api/v1/tasks/{id}/comments'
             7. [POST]
                'api/v1/tasks/{id}/comments'        
  -Task Status
        1. [GET] 
            'api/v1/task-statuses'
        2. [POST] 
            'api/v1/task-statuses'
        3. [GET]  
            'api/v1/task-statuses/{id}'
        5. [PUT]  
            'api/v1/task-statuses/{id}'
        6. [DELETE]  
            'api/v1/task-statuses/{id}'
  -Commons
        1. [GET] 
            'api/v1/commons/users'
        2. [GET] 
            'api/v1/commons/task-statuses'
        3. [GET]  
            'api/v1/commons/total-task'
```
