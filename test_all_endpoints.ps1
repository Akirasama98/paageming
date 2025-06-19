# Paageming API - Automated Endpoint Testing Script
# Jalankan di PowerShell (Windows) dari folder project

$baseUrl = "http://localhost:8000/api"

Write-Host "=== Paageming API Automated Testing ===" -ForegroundColor Cyan

# 1. Register User
Write-Host "\n[1] Register User" -ForegroundColor Yellow
$registerBody = @{ name = "Test User"; email = "testuser$(Get-Random)@example.com"; password = "password123" } | ConvertTo-Json
$register = Invoke-RestMethod -Uri "$baseUrl/register" -Method Post -ContentType "application/json" -Body $registerBody
$register | ConvertTo-Json -Depth 5

# 2. Login as Admin
Write-Host "\n[2] Login as Admin" -ForegroundColor Yellow
$loginBody = @{ email = "admin@paageming.com"; password = "admin123" } | ConvertTo-Json
$admin = Invoke-RestMethod -Uri "$baseUrl/login" -Method Post -ContentType "application/json" -Body $loginBody
$token = $admin.token
$admin | ConvertTo-Json -Depth 5

# 3. Categories CRUD
Write-Host "\n[3] Categories CRUD" -ForegroundColor Yellow
$headers = @{ Authorization = "Bearer $token" }
$cat = Invoke-RestMethod -Uri "$baseUrl/categories" -Method Post -ContentType "application/json" -Headers $headers -Body (@{ name = "TestCat"; description = "Desc" } | ConvertTo-Json)
$catId = $cat.data.id
$cat | ConvertTo-Json -Depth 5

$catList = Invoke-RestMethod -Uri "$baseUrl/categories" -Method Get
$catList | ConvertTo-Json -Depth 5

$catDetail = Invoke-RestMethod -Uri "$baseUrl/categories/$catId" -Method Get
$catDetail | ConvertTo-Json -Depth 5

$catUpdate = Invoke-RestMethod -Uri "$baseUrl/categories/$catId" -Method Put -ContentType "application/json" -Headers $headers -Body (@{ name = "TestCatUpdated"; description = "Updated" } | ConvertTo-Json)
$catUpdate | ConvertTo-Json -Depth 5

$catDelete = Invoke-RestMethod -Uri "$baseUrl/categories/$catId" -Method Delete -Headers $headers
$catDelete | ConvertTo-Json -Depth 5

# 4. Products CRUD
Write-Host "\n[4] Products CRUD" -ForegroundColor Yellow
$prod = Invoke-RestMethod -Uri "$baseUrl/products" -Method Post -ContentType "application/json" -Headers $headers -Body (@{ name = "TestProd"; description = "Desc"; price = 1000; category_id = $catId; stock = 10 } | ConvertTo-Json)
$prodId = $prod.data.id
$prod | ConvertTo-Json -Depth 5

$prodList = Invoke-RestMethod -Uri "$baseUrl/products" -Method Get
$prodList | ConvertTo-Json -Depth 5

$prodDetail = Invoke-RestMethod -Uri "$baseUrl/products/$prodId" -Method Get
$prodDetail | ConvertTo-Json -Depth 5

$prodUpdate = Invoke-RestMethod -Uri "$baseUrl/products/$prodId" -Method Put -ContentType "application/json" -Headers $headers -Body (@{ name = "TestProdUpdated"; description = "Updated"; price = 2000; category_id = $catId; stock = 5 } | ConvertTo-Json)
$prodUpdate | ConvertTo-Json -Depth 5

$prodDelete = Invoke-RestMethod -Uri "$baseUrl/products/$prodId" -Method Delete -Headers $headers
$prodDelete | ConvertTo-Json -Depth 5

# 5. Users (admin only)
Write-Host "\n[5] Get Users (admin)" -ForegroundColor Yellow
$users = Invoke-RestMethod -Uri "$baseUrl/users" -Method Get -Headers $headers
$users | ConvertTo-Json -Depth 5

# 6. Upload (dummy, skip actual file)
Write-Host "\n[6] Upload Image (SKIP: manual test required)" -ForegroundColor Yellow
Write-Host "(Upload image endpoint requires multipart/form-data, test manually)" -ForegroundColor DarkGray

# 7. Cart
Write-Host "\n[7] Cart Operations" -ForegroundColor Yellow
$cartAdd = Invoke-RestMethod -Uri "$baseUrl/cart/add" -Method Post -ContentType "application/json" -Headers $headers -Body (@{ product_id = $prodId; quantity = 2 } | ConvertTo-Json)
$cartAdd | ConvertTo-Json -Depth 5

$cart = Invoke-RestMethod -Uri "$baseUrl/cart" -Method Get -Headers $headers
$cart | ConvertTo-Json -Depth 5

# Planned endpoints (simulate, may fail if not implemented)
try { $cartUpdate = Invoke-RestMethod -Uri "$baseUrl/cart/update/$($cartAdd.data.id)" -Method Put -ContentType "application/json" -Headers $headers -Body (@{ quantity = 3 } | ConvertTo-Json); $cartUpdate | ConvertTo-Json -Depth 5 } catch { Write-Host "[PLANNED] Cart Update: $_" -ForegroundColor DarkGray }
try { $cartRemove = Invoke-RestMethod -Uri "$baseUrl/cart/remove/$($cartAdd.data.id)" -Method Delete -Headers $headers; $cartRemove | ConvertTo-Json -Depth 5 } catch { Write-Host "[PLANNED] Cart Remove: $_" -ForegroundColor DarkGray }
try { $cartCheckout = Invoke-RestMethod -Uri "$baseUrl/cart/checkout" -Method Post -ContentType "application/json" -Headers $headers -Body (@{ shipping_address = "Jl. Testing"; payment_method = "manual" } | ConvertTo-Json); $cartCheckout | ConvertTo-Json -Depth 5 } catch { Write-Host "[PLANNED] Cart Checkout: $_" -ForegroundColor DarkGray }

# 8. Orders
Write-Host "\n[8] Orders" -ForegroundColor Yellow
try { $orders = Invoke-RestMethod -Uri "$baseUrl/orders" -Method Get -Headers $headers; $orders | ConvertTo-Json -Depth 5 } catch { Write-Host "[PLANNED] Orders List: $_" -ForegroundColor DarkGray }

# Simulate order confirm/verify (dummy order id)
$orderId = "dummy_order_id"
try { $orderConfirm = Invoke-RestMethod -Uri "$baseUrl/orders/$orderId/confirm" -Method Post -ContentType "application/json" -Headers $headers -Body (@{ payment_proof = "dummy" } | ConvertTo-Json); $orderConfirm | ConvertTo-Json -Depth 5 } catch { Write-Host "Order Confirm: $_" -ForegroundColor DarkGray }
try { $orderVerify = Invoke-RestMethod -Uri "$baseUrl/orders/$orderId/verify" -Method Post -ContentType "application/json" -Headers $headers -Body (@{ status = "verified" } | ConvertTo-Json); $orderVerify | ConvertTo-Json -Depth 5 } catch { Write-Host "Order Verify: $_" -ForegroundColor DarkGray }

Write-Host "\n=== TESTING COMPLETE ===" -ForegroundColor Green
