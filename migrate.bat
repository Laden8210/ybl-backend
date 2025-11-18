@echo off
echo Migrating database using SQL dump file...

php import-dump.php

echo Database migration completed!
pause
