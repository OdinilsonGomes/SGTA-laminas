@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../laminas-api-tools/api-tools-admin/bin/api-tools-upgrade-to-1.5
php "%BIN_TARGET%" %*
