# Eheca API Documentation

## Base URL

```
https://api.eheca.example.com/api/v1
```

## Authentication

### Login

```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "your_secure_password"
}
```

**Response:**
```json
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600,
    "refresh_token": "def50200debc71d4eb..."
}
```

### Using the Token

Include the token in the `Authorization` header:
```
Authorization: Bearer your_access_token_here
```

### Refresh Token

```http
POST /api/auth/refresh
Authorization: Bearer your_refresh_token_here
```

## Response Format

### Success
```json
{
    "success": true,
    "data": {},
    "message": "Operation successful"
}
```

### Error
```json
{
    "success": false,
    "error": {
        "code": "error_code",
        "message": "Error description"
    }
}
```

## Endpoints

### Users

#### Get Current User
```http
GET /api/users/me
Authorization: Bearer your_token_here
```

#### Update User
```http
PUT /api/users/me
Authorization: Bearer your_token_here
Content-Type: application/json

{
    "first_name": "John",
    "last_name": "Doe",
    "email": "new.email@example.com"
}
```

#### Change Password
```http
POST /api/account/change-password
Authorization: Bearer your_token_here
Content-Type: application/json

{
    "current_password": "current_password",
    "new_password": "new_secure_password",
    "new_password_confirmation": "new_secure_password"
}
```

### Admin

#### List Users (Admin Only)
```http
GET /api/admin/users?page=1&limit=10
Authorization: Bearer admin_token_here
```

## Pagination

- `page` - Page number (default: 1)
- `limit` - Items per page (default: 10, max: 100)

## Filtering

Use `filter=field:operator:value`:
- Operators: `eq`, `neq`, `gt`, `gte`, `lt`, `lte`, `like`
- Example: `filter=email:like:%@example.com`

## Sorting

Use `sort=field:direction`:
- Directions: `asc`, `desc`
- Example: `sort=created_at:desc`

## Rate Limits

- 60 requests/minute (authenticated)
- 30 requests/minute (unauthenticated)
- 100 requests/minute (admin endpoints)

## Support

Contact: support@eheca.example.com
    "error": {
        "code": "error_code",
        "message": "Human-readable error message"
    }
}
```

## Common HTTP Status Codes
- `200 OK` - Successful request
- `201 Created` - Resource created successfully
- `204 No Content` - Resource deleted successfully
- `400 Bad Request` - Invalid request data
- `401 Unauthorized` - Authentication required
- `403 Forbidden` - Insufficient permissions
- `404 Not Found` - Resource not found
- `422 Unprocessable Entity` - Validation errors
- `429 Too Many Requests` - Rate limit exceeded
- `500 Internal Server Error` - Server error

## Available Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - Register new user
- `POST /api/auth/logout` - Invalidate token
- `POST /api/auth/refresh` - Refresh access token
- `POST /api/auth/forgot-password` - Request password reset
- `POST /api/auth/reset-password` - Reset password

### Users
- `GET /api/users` - List users (admin only)
- `POST /api/users` - Create user (admin only)
- `GET /api/users/{id}` - Get user profile
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user (admin only)

## Pagination
All list endpoints support pagination:

### Query Parameters
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 15, max: 100)

### Response Headers
- `X-Pagination-Current-Page`
- `X-Pagination-Per-Page`
- `X-Pagination-Total-Count`
- `X-Pagination-Page-Count`

## Filtering and Sorting
Most list endpoints support filtering and sorting:

### Filtering
```
GET /api/users?filter[status]=active&filter[role]=admin
```

### Sorting
```
GET /api/users?sort=-created_at,name
```

## Webhooks
Coming soon...

## API Versioning
API versioning is handled through the URL path. The current version is `v1`.

## Changelog
- **v1.0.0** (2025-06-01): Initial API release
