# Maintenance Mode

This document explains how to use the maintenance mode feature in the application.

## Enabling Maintenance Mode

### Using the Web Interface (Admin Only)

1. Log in as an administrator
2. Navigate to `/admin/maintenance`
3. Click "Enable Maintenance Mode"
4. Optionally, add a custom message
5. Click "Save"

### Using the Command Line

```bash
# Enable maintenance mode
php bin/console app:maintenance --on --message="Scheduled maintenance in progress" --allow=192.168.1.1 --allow=10.0.0.1

# Disable maintenance mode
php bin/console app:maintenance --off

# Check maintenance status
php bin/console app:maintenance --status
```

## Allowed IPs

You can specify IP addresses that are allowed to access the site during maintenance:

```bash
# Allow specific IPs
php bin/console app:maintenance --on --allow=192.168.1.1 --allow=10.0.0.1
```

## Customizing the Maintenance Page

The maintenance page template is located at `templates/maintenance.html.twig`. You can customize it to match your application's design.

## Configuration

You can configure maintenance mode settings in `config/services.yaml`:

```yaml
parameters:
    maintenance.allowed_ips: ['192.168.1.1']
    maintenance.message: 'We are performing scheduled maintenance. Please check back soon.'
```

## API Endpoints

- `GET /maintenance/status` - Check if maintenance mode is enabled
- `POST /maintenance/enable` - Enable maintenance mode (requires authentication)
- `POST /maintenance/disable` - Disable maintenance mode (requires authentication)

## Security

- Only users with `ROLE_ADMIN` can enable/disable maintenance mode
- The maintenance page is served with a 503 Service Unavailable status code
- Search engines are instructed not to index the maintenance page
- The maintenance page includes a `Retry-After` header to indicate when the site will be back online

## Troubleshooting

### Maintenance mode won't enable/disable

1. Check file permissions on `var/maintenance.lock`
2. Verify that the web server has write access to the `var/` directory
3. Check the application logs for any errors

### Maintenance page isn't showing up

1. Clear the cache: `php bin/console cache:clear`
2. Verify that the maintenance subscriber is registered in `config/services.yaml`
3. Check that the template exists at `templates/maintenance.html.twig`

## Best Practices

- Always notify users in advance of planned maintenance
- Keep maintenance windows as short as possible
- Provide an estimated downtime window
- Consider using a load balancer or proxy to serve a static maintenance page for better performance
