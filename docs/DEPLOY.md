# Connect GitHub to EasyWP hosting

## Step 1 — Get SFTP details from EasyWP

1. Go to [EasyWP Dashboard](https://dashboard.easywp.com)
2. Click **Manage** on your site
3. Open **Files & Database**
4. Set access time (e.g. 1 hour or Forever)
5. Click **Access Files**
6. Copy: **Hostname**, **Username**, **Password** (port is **22**)

## Step 2 — Add secrets on GitHub

1. Open: https://github.com/zeeshanfarooq786/leatherisem/settings/secrets/actions
2. Click **New repository secret** for each row:

| Name | Value |
|------|--------|
| `SFTP_SERVER` | Hostname from EasyWP (no `sftp://` prefix) |
| `SFTP_USERNAME` | SFTP username |
| `SFTP_PASSWORD` | SFTP password |
| `SFTP_PORT` | `22` |

## Step 3 — Run deploy

1. Open: https://github.com/zeeshanfarooq786/leatherisem/actions
2. Click **Deploy to EasyWP** → **Run workflow** → **Run workflow**

Or push any change to `main`:

```bash
git add .
git commit -m "Update site"
git push
```

## What gets deployed

- Themes, plugins, WordPress core files
- **Not** deployed: `wp-config.php`, uploads, backups, EasyWP mu-plugins (hosting stays intact)

## If deploy fails

- Check all 4 secrets are set correctly
- Confirm SFTP access has not expired in EasyWP
- Open the failed run in Actions and read the red error line
