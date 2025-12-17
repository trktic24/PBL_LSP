# Deployment Guide (LSP Poly)

## 1. Storage Configuration

This application uses Laravel's `storage` system with distinct security zones.

### Directories

-   **Static Assets** (Logos, Backgrounds): `public/images/`
-   **Public Uploads** (Berita, Mitra, Skema): `storage/app/public/`
-   **Private Uploads** (Asesi Data, KTP, Tanda Tangan): `storage/app/private_uploads/`

### Permissions

Ensure the web server (www-data/nginx) has write access to:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Symlink

You **MUST** run this command to expose public storage:

```bash
php artisan storage:link
```

_This creates a symlink from `public/storage` to `storage/app/public`._

## 2. Migration Notes

If deploying this refactor for the first time on an existing server with data:

1.  **Backup Database & Files**.
2.  Run strict migration command:
    ```bash
    php artisan deployment:migrate-public-assets
    ```
    _This moves existing files from `public/images/berita` etc. to `storage/app/public/...` and updates database paths._

## 3. Environment

Ensure `.env` has correct URL:

```ini
APP_URL=https://lsp.polines.ac.id
FILESYSTEM_DISK=local
```

_(If using S3/MinIO, update `config/filesystems.php` accordingly, but the code is optimized for local disk driver currently)._
