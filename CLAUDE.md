# Claude Instructions

## Docker Commands

All commands in this project should be run through Docker. Use the following pattern:

```bash
docker exec -it <container_name> <command>
```

### Common Commands

- **Install dependencies**: `docker exec -it app composer install && npm install`
- **Run migrations**: `docker exec -it app php artisan migrate`
- **Run tests**: `docker exec -it app php artisan test`
- **Build assets**: `docker exec -it app npm run build`
- **Development server**: `docker exec -it app npm run dev`
- **Linting**: `docker exec -it app npm run lint`
- **Type checking**: `docker exec -it app npm run typecheck`

### Laravel Artisan Commands

All artisan commands should be prefixed with Docker:

```bash
docker exec -it app php artisan <command>
```

### NPM Commands

All npm commands should be run inside the container:

```bash
docker exec -it app npm <command>
```

### Composer Commands

All composer commands should be run inside the container:

```bash
docker exec -it app composer <command>
```

## Container Names

- Main application container: `app`
- Database container: `db`
- Redis container (if applicable): `redis`

Note: Replace `app` with your actual container name if different.