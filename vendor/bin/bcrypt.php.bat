@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../laminas-api-tools/api-tools-oauth2/bin/bcrypt.php
php "%BIN_TARGET%" %*
