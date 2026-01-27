# Quick Fix Script
# Delete hot file to force Laravel to use built assets

Remove-Item -Path "C:\laragon\www\Reefy\public\hot" -Force -ErrorAction SilentlyContinue
Write-Host "Deleted public/hot file"
Write-Host "Now run: npm run build"
