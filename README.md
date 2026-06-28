# Leatherismus

WordPress e-commerce site (Elementor + WooCommerce). Developed locally with XAMPP; hosted on Namecheap EasyWP.

## Stack

- WordPress
- Theme: Hello Elementor (child theme: `hello-elementor-child`)
- Page builder: Elementor
- Hosting: Namecheap EasyWP

## Local setup

1. Clone the repo into your web root (e.g. `htdocs/leatherisem`).
2. Copy `wp-config-sample.php` to `wp-config.php` and set database credentials.
3. Import the database (phpMyAdmin or All-in-One WP Migration).
4. On XAMPP only: keep EasyWP mu-plugin and Redis object cache **disabled** (see project notes).

## Deploy (GitHub → EasyWP)

Pushing to the `main` branch deploys files to EasyWP via SFTP.

### One-time GitHub setup

In the repo: **Settings → Secrets and variables → Actions → New repository secret**

| Secret | Value |
|--------|--------|
| `SFTP_SERVER` | Hostname from EasyWP → Files & Database → Access Files |
| `SFTP_USERNAME` | SFTP username |
| `SFTP_PASSWORD` | SFTP password |
| `SFTP_PORT` | `22` |

**Note:** Only **files** are deployed. Database and media in `wp-content/uploads/` are not synced by Git — update those on the server separately.

## Workflow

```text
Edit locally (XAMPP) → git commit → git push → GitHub Actions deploys to EasyWP
```
