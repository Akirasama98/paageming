# Test Cart API - PowerShell Script
# Paageming E-commerce API Testing

Write-Host "=== PAAGEMING CART API TEST ===" -ForegroundColor Yellow
Write-Host ""

$baseUrl = "http://127.0.0.1:8000/api"

# 1. Login untuk mendapatkan token
Write-Host "1. Login untuk mendapatkan token..." -ForegroundColor Green
try {
    $loginResponse = Invoke-RestMethod -Uri "$baseUrl/login" -Method POST -Headers @{"Content-Type"="application/json"} -Body '{"email":"user@paageming.com","password":"user123"}'
    $token = $loginResponse.access_token
    $userId = $loginResponse.user.id
    Write-Host "✓ Login berhasil. User ID: $userId" -ForegroundColor Green
    Write-Host "Token: $token" -ForegroundColor Gray
} catch {
    Write-Host "✗ Login gagal: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

Write-Host ""

# Headers untuk authenticated requests
$headers = @{
    "Content-Type" = "application/json"
    "Authorization" = "Bearer ${userId}:token123"
}

# 2. Lihat cart (seharusnya kosong)
Write-Host "2. Melihat cart (seharusnya kosong)..." -ForegroundColor Green
try {
    $cartResponse = Invoke-RestMethod -Uri "$baseUrl/cart" -Method GET -Headers $headers
    Write-Host "✓ Cart berhasil diambil:" -ForegroundColor Green
    $cartResponse | ConvertTo-Json -Depth 3
} catch {
    Write-Host "✗ Gagal mengambil cart: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 3. Add product ke cart
Write-Host "3. Menambahkan produk ke cart..." -ForegroundColor Green
try {
    $addResponse = Invoke-RestMethod -Uri "$baseUrl/cart/add" -Method POST -Headers $headers -Body '{"product_id":"prod1","quantity":2}'
    Write-Host "✓ Produk berhasil ditambahkan ke cart:" -ForegroundColor Green
    $addResponse | ConvertTo-Json -Depth 2
} catch {
    Write-Host "✗ Gagal menambahkan produk: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 4. Add product lain ke cart
Write-Host "4. Menambahkan produk lain ke cart..." -ForegroundColor Green
try {
    $addResponse2 = Invoke-RestMethod -Uri "$baseUrl/cart/add" -Method POST -Headers $headers -Body '{"product_id":"prod2","quantity":1}'
    Write-Host "✓ Produk kedua berhasil ditambahkan:" -ForegroundColor Green
    $addResponse2 | ConvertTo-Json -Depth 2
} catch {
    Write-Host "✗ Gagal menambahkan produk kedua: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 5. Lihat cart dengan isi
Write-Host "5. Melihat cart dengan isi..." -ForegroundColor Green
try {
    $cartWithItems = Invoke-RestMethod -Uri "$baseUrl/cart" -Method GET -Headers $headers
    Write-Host "✓ Cart dengan isi:" -ForegroundColor Green
    $cartWithItems | ConvertTo-Json -Depth 4
} catch {
    Write-Host "✗ Gagal mengambil cart: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 6. Update quantity item di cart
Write-Host "6. Update quantity item pertama..." -ForegroundColor Green
try {
    $updateResponse = Invoke-RestMethod -Uri "$baseUrl/cart/update/prod1" -Method PUT -Headers $headers -Body '{"quantity":3}'
    Write-Host "✓ Quantity berhasil diupdate:" -ForegroundColor Green
    $updateResponse | ConvertTo-Json -Depth 2
} catch {
    Write-Host "✗ Gagal update quantity: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 7. Lihat cart setelah update
Write-Host "7. Melihat cart setelah update..." -ForegroundColor Green
try {
    $cartAfterUpdate = Invoke-RestMethod -Uri "$baseUrl/cart" -Method GET -Headers $headers
    Write-Host "✓ Cart setelah update:" -ForegroundColor Green
    $cartAfterUpdate | ConvertTo-Json -Depth 4
} catch {
    Write-Host "✗ Gagal mengambil cart: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 8. Remove item dari cart
Write-Host "8. Menghapus item dari cart..." -ForegroundColor Green
try {
    $removeResponse = Invoke-RestMethod -Uri "$baseUrl/cart/remove/prod2" -Method DELETE -Headers $headers
    Write-Host "✓ Item berhasil dihapus:" -ForegroundColor Green
    $removeResponse | ConvertTo-Json -Depth 2
} catch {
    Write-Host "✗ Gagal menghapus item: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 9. Lihat cart setelah remove
Write-Host "9. Melihat cart setelah remove..." -ForegroundColor Green
try {
    $cartAfterRemove = Invoke-RestMethod -Uri "$baseUrl/cart" -Method GET -Headers $headers
    Write-Host "✓ Cart setelah remove:" -ForegroundColor Green
    $cartAfterRemove | ConvertTo-Json -Depth 4
} catch {
    Write-Host "✗ Gagal mengambil cart: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 10. Checkout cart
Write-Host "10. Checkout cart..." -ForegroundColor Green
try {
    $checkoutResponse = Invoke-RestMethod -Uri "$baseUrl/cart/checkout" -Method POST -Headers $headers
    Write-Host "✓ Checkout berhasil:" -ForegroundColor Green
    $checkoutResponse | ConvertTo-Json -Depth 2
} catch {
    Write-Host "✗ Gagal checkout: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# 11. Lihat cart setelah checkout (seharusnya kosong)
Write-Host "11. Melihat cart setelah checkout (seharusnya kosong)..." -ForegroundColor Green
try {
    $cartAfterCheckout = Invoke-RestMethod -Uri "$baseUrl/cart" -Method GET -Headers $headers
    Write-Host "✓ Cart setelah checkout:" -ForegroundColor Green
    $cartAfterCheckout | ConvertTo-Json -Depth 4
} catch {
    Write-Host "✗ Gagal mengambil cart: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "=== TEST CART API SELESAI ===" -ForegroundColor Yellow
