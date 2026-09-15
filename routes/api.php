<?php

use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CompanyProfileController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\GalleryCategoryController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\Api\JobOpeningController;
use App\Http\Controllers\Api\NewsCategoryController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC ROUTES (Tanpa Autentikasi)
// ==========================================
Route::post('/login', [AuthController::class, 'login']);

// Public Endpoint Profil Perusahaan
Route::get('/company-profile', [CompanyProfileController::class, 'show']);

// Public Endpoints Berita
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{id}', [NewsController::class, 'show']);

// Public Endpoints Kategori Berita
Route::get('/news-categories', [NewsCategoryController::class, 'index']);
Route::get('/news-categories/{id}', [NewsCategoryController::class, 'show']);

// Public Endpoints Kategori Produk
Route::get('/product-categories', [ProductCategoryController::class, 'index']);
Route::get('/product-categories/{id}', [ProductCategoryController::class, 'show']);

// Public Endpoints Client
Route::get('/clients', [ClientController::class, 'index']);
Route::get('/clients/{id}', [ClientController::class, 'show']);

// Public Endpoints Produk
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Public Endpoints Kategori Galeri
Route::get('/gallery-categories', [GalleryCategoryController::class, 'index']);
Route::get('/gallery-categories/{id}', [GalleryCategoryController::class, 'show']);

// Public Endpoints Galeri
Route::get('/galleries', [GalleryController::class, 'index']);
Route::get('/galleries/{id}', [GalleryController::class, 'show']);

// Public Endpoints Lowongan Kerja
Route::get('/job-openings', [JobOpeningController::class, 'index']);
Route::get('/job-openings/{id}', [JobOpeningController::class, 'show']);

// Public Endpoint Submit Lamaran Kerja
Route::post('/job-applications', [JobApplicationController::class, 'store']);

// Public Endpoints Jabatan (Positions)
Route::get('/positions', [PositionController::class, 'index']);
Route::get('/positions/{position}', [PositionController::class, 'show']);

// Public Endpoints Anggota Tim (Team Members)
Route::get('/team-members', [TeamMemberController::class, 'index']);
Route::get('/team-members/{team_member}', [TeamMemberController::class, 'show']);

// Public Endpoints Layanan (Services)
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{service}', [ServiceController::class, 'show']);

// Public Endpoints Settings
Route::get('/settings', [SettingController::class, 'index']);
Route::get('/settings/{key}', [SettingController::class, 'show']);

// Public Endpoint Submit Pesan Kontak
Route::post('/contacts', [ContactController::class, 'store']);


// ==========================================
// 2. PROTECTED ROUTES (Wajib Auth Sanctum)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // Auth & Profile Endpoints
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin Endpoints (Khusus Kelola Staf & Permissions)
    Route::middleware('permission:manage-users')->group(function () {
        Route::get('/admin/staf', [UserController::class, 'index']);
        Route::post('/admin/staf', [UserController::class, 'storeStaf']);
        Route::put('/admin/staf/{id}/permissions', [UserController::class, 'updateStafPermissions']);
        
        // Activity Logs Management
        Route::get('/activity-logs', [ActivityLogController::class, 'index']);
        Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show']);
        Route::delete('/activity-logs/{id}', [ActivityLogController::class, 'destroy']);
    });

    // Content Management Endpoints
    Route::middleware('permission:manage-news')->group(function () {

        // Company Profile Management
        Route::post('/company-profile', [CompanyProfileController::class, 'updateOrCreate']);
        Route::put('/company-profile', [CompanyProfileController::class, 'updateOrCreate']);

        // News Management
        Route::post('/news', [NewsController::class, 'store']);
        Route::post('/news/{id}', [NewsController::class, 'update']); 
        Route::put('/news/{id}', [NewsController::class, 'update']);
        Route::delete('/news/{id}', [NewsController::class, 'destroy']);

        // News Categories Management
        Route::post('/news-categories', [NewsCategoryController::class, 'store']);
        Route::put('/news-categories/{id}', [NewsCategoryController::class, 'update']);
        Route::delete('/news-categories/{id}', [NewsCategoryController::class, 'destroy']);

        // Product Categories Management
        Route::post('/product-categories', [ProductCategoryController::class, 'store']);
        Route::put('/product-categories/{id}', [ProductCategoryController::class, 'update']);
        Route::delete('/product-categories/{id}', [ProductCategoryController::class, 'destroy']);

        // Client Management
        Route::post('/clients', [ClientController::class, 'store']);
        Route::post('/clients/{id}', [ClientController::class, 'update']);
        Route::put('/clients/{id}', [ClientController::class, 'update']);
        Route::delete('/clients/{id}', [ClientController::class, 'destroy']);

        // Product Management
        Route::post('/products', [ProductController::class, 'store']);
        Route::post('/products/{id}', [ProductController::class, 'update']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Gallery Categories Management
        Route::post('/gallery-categories', [GalleryCategoryController::class, 'store']);
        Route::put('/gallery-categories/{id}', [GalleryCategoryController::class, 'update']);
        Route::delete('/gallery-categories/{id}', [GalleryCategoryController::class, 'destroy']);

        // Gallery Management
        Route::post('/galleries', [GalleryController::class, 'store']);
        Route::post('/galleries/{id}', [GalleryController::class, 'update']);
        Route::put('/galleries/{id}', [GalleryController::class, 'update']);
        Route::delete('/galleries/{id}', [GalleryController::class, 'destroy']);

        // Job Openings Management
        Route::post('/job-openings', [JobOpeningController::class, 'store']);
        Route::put('/job-openings/{id}', [JobOpeningController::class, 'update']);
        Route::delete('/job-openings/{id}', [JobOpeningController::class, 'destroy']);

        // Job Applications Management (Khusus Admin / HR)
        Route::get('/job-applications', [JobApplicationController::class, 'index']);
        Route::get('/job-applications/{id}', [JobApplicationController::class, 'show']);
        Route::patch('/job-applications/{id}/status', [JobApplicationController::class, 'updateStatus']);
        Route::delete('/job-applications/{id}', [JobApplicationController::class, 'destroy']);

        // Positions Management (Create, Update, Delete)
        Route::post('/positions', [PositionController::class, 'store']);
        Route::put('/positions/{position}', [PositionController::class, 'update']);
        Route::delete('/positions/{position}', [PositionController::class, 'destroy']);

        // Team Members Management (Create, Update, Delete)
        Route::post('/team-members', [TeamMemberController::class, 'store']);
        Route::post('/team-members/{team_member}', [TeamMemberController::class, 'update']); 
        Route::put('/team-members/{team_member}', [TeamMemberController::class, 'update']);
        Route::delete('/team-members/{team_member}', [TeamMemberController::class, 'destroy']);

        // Services Management (Create, Update, Delete)
        Route::post('/services', [ServiceController::class, 'store']);
        Route::post('/services/{service}', [ServiceController::class, 'update']);
        Route::put('/services/{service}', [ServiceController::class, 'update']);
        Route::delete('/services/{service}', [ServiceController::class, 'destroy']);

        // Settings Management (Create, Update, Delete)
        Route::post('/settings', [SettingController::class, 'updateOrCreate']);
        Route::put('/settings', [SettingController::class, 'updateOrCreate']);
        Route::delete('/settings/{key}', [SettingController::class, 'destroy']);

        // Contacts Management (Kelola Pesan Masuk)
        Route::get('/contacts', [ContactController::class, 'index']);
        Route::get('/contacts/{contact}', [ContactController::class, 'show']);
        Route::patch('/contacts/{contact}/status', [ContactController::class, 'updateStatus']);
        Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);
    });

});