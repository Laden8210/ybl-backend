@echo off
call pull.bat
call composer_install.bat
call migrate.bat
call dev.bat
