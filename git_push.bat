@echo off
chcp 65001 > nul
echo =====================================================
echo   AFI BILISIM - GitHub Senkronizasyon Scripti
echo =====================================================
echo.
set /p commit_msg="Commit aciklamasi girin (Enter'a basarsaniz otomatik tarih atanir): "
if "%commit_msg%"=="" (
    set commit_msg=Guncelleme - %date% %time%
)

echo.
echo [1/3] Degisiklikler ekleniyor (git add .)...
git add .

echo.
echo [2/3] Commit olusturuluyor: "%commit_msg%"
git commit -m "%commit_msg%"

echo.
echo [3/3] GitHub'a gonderiliyor (git push origin main)...
git push origin main

echo.
if %errorlevel% equ 0 (
    echo [OK] Basariyla GitHub'a yuklendi!
) else (
    echo [HATA] Bir sorun olustu, lutfen kontrol edin.
)
echo.
pause
