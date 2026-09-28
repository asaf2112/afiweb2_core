@echo off
REM =====================================================
REM   AFI BİLİŞİM — Production Optimize Script
REM   Kullanım: deploy.bat (proje kök dizininde çalıştır)
REM =====================================================

echo.
echo [1/7] Composer autoload optimize...
call composer install --optimize-autoloader --no-dev

echo.
echo [2/7] .env cache temizleniyor (fresh config için)...
call php artisan config:clear
call php artisan route:clear
call php artisan view:clear
call php artisan cache:clear

echo.
echo [3/7] Config cache...
call php artisan config:cache

echo.
echo [4/7] Route cache...
call php artisan route:cache

echo.
echo [5/7] View cache (Blade template precompile)...
call php artisan view:cache

echo.
echo [6/7] Event discovery cache...
call php artisan event:cache

echo.
echo [7/7] Sitemap cache temizle (fresh sitemap üretimi için)...
call php artisan cache:forget sitemap_xml

echo.
echo ✅ Tüm optimize işlemleri tamamlandı!
echo    Site şu an production modda çalışmaya hazır.
echo.
echo   Not: APP_ENV=production ve APP_DEBUG=false olduğundan emin olun.
pause
