# Script untuk Convert HTML ke PDF
# Paageming API Documentation

Write-Host "=== Paageming API Documentation Converter ===" -ForegroundColor Green

# Path files
$htmlFile = "c:\laragon\www\paageming\API_DOCUMENTATION_PDF_READY.html"
$pdfFile = "c:\laragon\www\paageming\API_DOCUMENTATION.pdf"

Write-Host "HTML Source: $htmlFile" -ForegroundColor Yellow
Write-Host "PDF Output: $pdfFile" -ForegroundColor Yellow

# Method 1: Menggunakan Chrome/Edge untuk convert ke PDF
Write-Host "`n=== Method 1: Using Chrome/Edge ===" -ForegroundColor Cyan

# Cek apakah Chrome tersedia
$chromeExists = Test-Path "C:\Program Files\Google\Chrome\Application\chrome.exe"
$edgeExists = Test-Path "C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"

if ($chromeExists) {
    Write-Host "Chrome detected. Converting to PDF..." -ForegroundColor Green
    
    $chromeArgs = @(
        "--headless",
        "--disable-gpu", 
        "--print-to-pdf=`"$pdfFile`"",
        "--print-to-pdf-no-header",
        "--no-margins",
        "file:///$htmlFile"
    )
    
    try {
        Start-Process "C:\Program Files\Google\Chrome\Application\chrome.exe" -ArgumentList $chromeArgs -Wait
        if (Test-Path $pdfFile) {
            Write-Host "✅ PDF berhasil dibuat dengan Chrome!" -ForegroundColor Green
            Write-Host "File location: $pdfFile" -ForegroundColor Yellow
        }
    }
    catch {
        Write-Host "❌ Error saat convert dengan Chrome: $($_.Exception.Message)" -ForegroundColor Red
    }
}
elseif ($edgeExists) {
    Write-Host "Edge detected. Converting to PDF..." -ForegroundColor Green
    
    $edgeArgs = @(
        "--headless",
        "--disable-gpu",
        "--print-to-pdf=`"$pdfFile`"", 
        "--print-to-pdf-no-header",
        "--no-margins",
        "file:///$htmlFile"
    )
    
    try {
        Start-Process "C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe" -ArgumentList $edgeArgs -Wait
        if (Test-Path $pdfFile) {
            Write-Host "✅ PDF berhasil dibuat dengan Edge!" -ForegroundColor Green
            Write-Host "File location: $pdfFile" -ForegroundColor Yellow
        }
    }
    catch {
        Write-Host "❌ Error saat convert dengan Edge: $($_.Exception.Message)" -ForegroundColor Red
    }
}
else {
    Write-Host "❌ Chrome atau Edge tidak ditemukan." -ForegroundColor Red
}

# Method 2: Manual instruction
Write-Host "`n=== Method 2: Manual Conversion ===" -ForegroundColor Cyan
Write-Host "Jika automatic conversion gagal, silakan:" -ForegroundColor Yellow
Write-Host "1. Buka file HTML di browser: $htmlFile" -ForegroundColor White
Write-Host "2. Tekan Ctrl+P (Print)" -ForegroundColor White
Write-Host "3. Pilih 'Save as PDF' atau 'Microsoft Print to PDF'" -ForegroundColor White
Write-Host "4. Save dengan nama: API_DOCUMENTATION.pdf" -ForegroundColor White

# Method 3: Word conversion instruction
Write-Host "`n=== Method 3: Convert to Word ===" -ForegroundColor Cyan
Write-Host "Untuk membuat file Word (.docx):" -ForegroundColor Yellow
Write-Host "1. Buka Microsoft Word" -ForegroundColor White
Write-Host "2. File -> Open -> Pilih file HTML: $htmlFile" -ForegroundColor White
Write-Host "3. File -> Save As -> Pilih format Word Document (.docx)" -ForegroundColor White
Write-Host "4. Save dengan nama: API_DOCUMENTATION.docx" -ForegroundColor White

# Alternative method using PowerShell and Word COM
Write-Host "`n=== Method 4: PowerShell + Word COM ===" -ForegroundColor Cyan

try {
    Write-Host "Mencoba menggunakan Microsoft Word COM object..." -ForegroundColor Yellow
    
    $word = New-Object -ComObject Word.Application
    $word.Visible = $false
    
    $doc = $word.Documents.Open($htmlFile)
    $docxFile = "c:\laragon\www\paageming\API_DOCUMENTATION.docx"
    
    $doc.SaveAs2($docxFile, 16) # 16 = wdFormatDocumentDefault (docx)
    $doc.Close()
    $word.Quit()
    
    Write-Host "✅ Word document berhasil dibuat!" -ForegroundColor Green
    Write-Host "File location: $docxFile" -ForegroundColor Yellow
    
    # Convert Word to PDF
    $word = New-Object -ComObject Word.Application
    $word.Visible = $false
    $doc = $word.Documents.Open($docxFile)
    $doc.SaveAs2($pdfFile, 17) # 17 = wdFormatPDF
    $doc.Close()
    $word.Quit()
    
    Write-Host "✅ PDF dari Word berhasil dibuat!" -ForegroundColor Green
}
catch {
    Write-Host "❌ Microsoft Word tidak tersedia atau error: $($_.Exception.Message)" -ForegroundColor Red
}

# Summary
Write-Host "`n=== Summary ===" -ForegroundColor Green
Write-Host "Files yang tersedia:" -ForegroundColor Yellow
Get-ChildItem "c:\laragon\www\paageming\API_DOCUMENTATION*" | ForEach-Object {
    Write-Host "📄 $($_.Name) - $('{0:N2}' -f ($_.Length/1KB)) KB" -ForegroundColor White
}

Write-Host "`n✅ Conversion selesai!" -ForegroundColor Green
Write-Host "Buka file untuk melihat hasil dokumentasi API." -ForegroundColor Cyan
