# Backup and Recovery Procedures

This document outlines the procedures for backing up and recovering the HealthApp (ha-wfw) system, including the MySQL database and the uploaded file storage.

## 1. Database Backup (MySQL)

The database contains all patient records, medical history, and user accounts.

### 1.1 Manual Backup using mysqldump

Run the following command from the terminal:

```bash
mysqldump -u [username] -p[password] ha_wfw > backups/db_backup_$(date +%F).sql
```

### 1.2 Automated Backup (Laravel Task Scheduling)

The application can be configured to run daily backups using a package like `spatie/laravel-backup` (recommended for production).

Current setup suggests manual or script-based cron jobs:
1. Create a shell script `backup.sh`.
2. Add it to crontab: `0 2 * * * /path/to/backup.sh`

## 2. File Storage Backup

Uploaded files (patient profiles, medical reports) are stored in `storage/app/public`.

### 2.1 Backup command

```bash
zip -r backups/storage_backup_$(date +%F).zip storage/app/public
```

## 3. Recovery Procedures

### 3.1 Database Recovery

To restore the database from a SQL dump:

```bash
mysql -u [username] -p[password] ha_wfw < backups/db_backup_YYYY-MM-DD.sql
```

### 3.2 File Storage Recovery

Extract the backup zip into the root directory:

```bash
unzip backups/storage_backup_YYYY-MM-DD.zip -d .
```

Ensure the symbolic link for public storage is recreated if missing:

```bash
php artisan storage:link
```

## 4. Disaster Recovery strategy

- **Off-site Backups:** Ensure backups are moved to a remote server or cloud storage (e.g., AWS S3).
- **Retention Policy:** Keep daily backups for 7 days, weekly for 4 weeks, and monthly for 6 months.
- **Testing:** Periodically (at least once a quarter) perform a trial restoration to a staging environment to verify backup integrity.
