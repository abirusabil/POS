# Rencana Implementasi: CRUD Modul Business

Menyediakan fitur CRUD (Create, Read, Update, Delete) lengkap untuk modul **Business** berdasarkan skema di [`POS.dbml`](file:///Users/abirusabil/Documents/Work/POS/POS.dbml).

---

## User Review Required

> [!NOTE]
> Modul **Business** menggunakan **UUID** sebagai Primary Key sesuai spesifikasi DBML (`id uuid [pk]`).
> Tampilan frontend akan dibangun menggunakan Vue 3 + Inertia.js dengan UI komponen modern (Tailwind CSS, shadcn-vue / reka-ui) sesuai standar desain visual aplikasi.

---

## Proposed Changes

### Database Layer

#### [NEW] Migration: `database/migrations/2026_08_15_000000_create_businesses_table.php`
- Membuat tabel `businesses` dengan kolom:
  - `id` (uuid, primary key)
  - `name` (string)
  - `owner_name` (string)
  - `timestamps` (`created_at`, `updated_at`)

---

### Backend Layer (Laravel)

#### [NEW] [`Business.php`](file:///Users/abirusabil/Documents/Work/POS/app/Models/Business.php)
- Eloquent Model `App\Models\Business` menggunakan trait `HasUuids`.
- `fillable`: `['name', 'owner_name']`.

#### [NEW] Form Requests:
- `App\Http\Requests\StoreBusinessRequest`: Validasi `name` (required, string, max:255), `owner_name` (required, string, max:255).
- `App\Http\Requests\UpdateBusinessRequest`: Validasi untuk update business.

#### [NEW] [`BusinessController.php`](file:///Users/abirusabil/Documents/Work/POS/app/Http/Controllers/BusinessController.php)
- `index()`: Menampilkan daftar business (dengan pagination & search).
- `create()`: Menampilkan form tambah business.
- `store(StoreBusinessRequest $request)`: Menyimpan data business baru.
- `edit(Business $business)`: Menampilkan form edit business.
- `update(UpdateBusinessRequest $request, Business $business)`: Memperbarui data business.
- `destroy(Business $business)`: Menghapus data business.

#### [MODIFY] [`routes/web.php`](file:///Users/abirusabil/Documents/Work/POS/routes/web.php)
- Menambahkan route resource `businesses` dalam grup middleware `auth`.

---

### Frontend Layer (Vue 3 + Inertia.js)

#### [NEW] [`resources/js/pages/businesses/Index.vue`](file:///Users/abirusabil/Documents/Work/POS/resources/js/pages/businesses/Index.vue)
- Halaman daftar bisnis dengan tabel data, fitur pencarian, tombol aksi edit & hapus, serta pagination.

#### [NEW] [`resources/js/pages/businesses/Create.vue`](file:///Users/abirusabil/Documents/Work/POS/resources/js/pages/businesses/Create.vue)
- Form pembuatan bisnis baru lengkap dengan pesan validasi error.

#### [NEW] [`resources/js/pages/businesses/Edit.vue`](file:///Users/abirusabil/Documents/Work/POS/resources/js/pages/businesses/Edit.vue)
- Form edit bisnis dengan pre-filled data.

#### [MODIFY] [`resources/js/components/AppSidebar.vue`](file:///Users/abirusabil/Documents/Work/POS/resources/js/components/AppSidebar.vue)
- Menambahkan menu **Businesses** di navigasi utama sidebar dengan icon `Building2`.

---

## Verification Plan

### Automated Tests
- Menjalankan `php artisan test --filter=Business` (jika membuat feature test).
- Menjalankan `npm run types:check` untuk verifikasi TypeScript di frontend.
- Menjalankan `npm run lint:check` dan `vendor/bin/pint` untuk format kode.

### Manual Verification
- Pengujian flow pembuatan, edit, hapus, dan pembacaan daftar bisnis melalui antarmuka web.
