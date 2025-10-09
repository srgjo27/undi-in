# Property Status Automation

## Overview

Sistem otomatis untuk mengubah status properti berdasarkan tanggal penjualan (sale_start_date dan sale_end_date).

## Status Flow

1. **draft** → **active**: Ketika `sale_start_date` sudah tiba
2. **active** → **pending_draw**: Ketika `sale_end_date` sudah tiba

## Implementation

### 1. Automatic Updates (Recommended)

Status akan otomatis terupdate melalui:

#### Schedule (Hourly)

-   Command `properties:update-status` dijalankan setiap jam
-   Configured di `routes/console.php`
-   Untuk menjalankan scheduler: `php artisan schedule:work`

#### On-Demand Updates

Status juga terupdate saat user mengakses:

-   Halaman daftar properti (`/seller/properties` atau `/admin/properties`)
-   Halaman detail properti (`/seller/properties/{id}` atau `/admin/properties/{id}`)

### 2. Manual Command

```bash
# Update status semua properti
php artisan properties:update-status
```

### 3. Manual Update via Interface

Admin dan Seller masih bisa mengubah status secara manual melalui interface.

## Technical Details

### Files Modified/Created:

-   `app/Console/Commands/UpdatePropertyStatus.php` - Command untuk update otomatis
-   `app/Models/Property.php` - Method helper untuk status automation
-   `app/Http/Controllers/Be/Seller/PropertyController.php` - Auto-update on page load
-   `app/Http/Controllers/Be/Admin/PropertyController.php` - Auto-update on page load
-   `routes/console.php` - Task scheduler configuration

### Key Methods:

-   `Property::shouldBeActive()` - Check apakah property harus jadi active
-   `Property::shouldBePendingDraw()` - Check apakah property harus jadi pending_draw
-   `Property::updateStatusAutomatically()` - Update status otomatis
-   `Property::scopeNeedsStatusUpdate()` - Scope untuk query properties yang perlu update

## Benefits

1. **Otomatis**: Status berubah sesuai jadwal tanpa intervensi manual
2. **Real-time**: Update saat user mengakses halaman
3. **Fleksibel**: Admin/seller masih bisa override manual jika diperlukan
4. **Performance**: Menggunakan scope dan bulk update untuk efisiensi
5. **Logging**: Command menampilkan log perubahan status

## Setup for Production

1. Setup cron job untuk scheduler:

    ```bash
    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
    ```

2. Atau jalankan scheduler daemon:
    ```bash
    php artisan schedule:work
    ```

## Monitoring

-   Check log aplikasi untuk melihat perubahan status otomatis
-   Command `properties:update-status` menampilkan output yang informatif
-   Status perubahan tercatat di database dengan timestamp `updated_at`
