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
exit 1
