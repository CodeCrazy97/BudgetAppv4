REM Starts mysql and then closes the command prompt.
@echo off 
C:\xampp\apache\bin\pv.exe mysqld.exe %1 >nul 
if ERRORLEVEL 1 goto Process_NotFound 
echo MySQL is running 
goto END 
:Process_NotFound 

echo Process %1 is not running 
@start /b C:\xampp\mysql\bin\mysqld.exe --defaults-file=c:\xampp\mysql\bin\my.ini --standalone --console=false
goto finish 
:finish
timeout 2

mysqldump.exe --user=root --host=localhost --port=3306 --result-file="C:\Users\Ethan\Documents\Projects\SQL\Database Backups\budget_backup.%date:~10,4%%date:~7,2%%date:~4,2%.sql" --default-character-set=utf8 --single-transaction=TRUE --databases "budget"