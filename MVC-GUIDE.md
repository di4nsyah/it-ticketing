# IT Ticketing — Panduan MVC

panduan ini buat aku sendiri biar pas presentasi ke mentor PKL, aku bisa
nunjukin alur kodenya tanpa mati di tengah jalan.

semua isi file ini berdasarkan kode yang benar-benar ada di project, bukan
teori umum.

---

## 1. Gambaran Besar

IT Ticketing itu aplikasi internal buat ngumpulin laporan masalah IT dari
karyawan. karyawan bikin ticket, teknisi yang nanganin.

ada dua role, dan itu bedanya paling penting di project ini:

| role | apa yang boleh |
|---|---|
| karyawan | bikin ticket, lihat ticket sendiri, batalin ticket sendiri |
| teknisi | lihat semua ticket, nggak bisa bikin ticket |

data ticket nyimpen 5 hal: judul, deskripsi, kategori, prioritas, sama status.

---

## 2. Struktur MVC

```text
User buka browser
  ↓
Route          -> nentuin request ini ditangani siapa
  ↓
Middleware     -> penyaring: udah login belum? (opsional)
  ↓
Controller     -> ngatur alur, nyiapin data
  ↓
Model          -> ngobrol sama database
  ↓
Database       -> MySQL, nyimpen datanya
  ↓
Model          -> data dikembalikin ke controller
  ↓
Controller     -> lempar data ke view
  ↓
View (Blade)   -> ubah data jadi HTML
  ↓
User           -> lihat di browser
```

arahnya turun ke database, terus naik balik lagi ke user. database ada di
tengah, dan cuma Model yang boleh nyentuh.

---

## 3. Route

semua route ada di `routes/web.php` (route auth dipisah di `routes/auth.php`).

| route | method | tujuan |
|---|---|---|
| `/` | GET | landing page, belum butuh login |
| `/dashboard` | GET | `DashboardController@index` |
| `/tickets` | GET | `TicketController@index` |
| `/tickets/create` | GET | `TicketController@create` |
| `/tickets` | POST | `TicketController@store` |
| `/tickets/{ticket}` | GET | `TicketController@show` |
| `/tickets/{ticket}/cancel` | PATCH | `TicketController@cancel` |
| `/profile` | GET/PATCH/DELETE | `ProfileController` |

yang perlu dijelasin kalo ditanya:

**`Route::resource(...)->only([...])`**
membikin 7 route sekaligus, tapi project ini cuma butuh 4, jadi dibatasi
pakai `only()`.

**`->middleware('auth')`**
berarti user wajib login dulu. kalo belum, Laravel lempar ke halaman login
otomatis. dipasang di `group()` Supaya semua route di dalemnya ikut terlindungi
tanpa nulis ulang.

**`->name('tickets.show')`**
nama route. gunanya biar Blade bisa minta URL tanpa nulis manual:

```blade
{{ route('tickets.show', $ticket) }}   →  /tickets/5
```

kalo hardcode `/tickets/5`, kalo ada perubahan URL nanti semua view harus diubah.

**`->middleware(['auth', 'verified'])`** di `/dashboard`
`auth` = harus login, `verified` = email harus udah dikonfirmasi.

---

## 4. Controller

ada 3 controller di project ini.

**`TicketController`** — ngurus semua alur ticket (index, create, store, show, cancel)
**`DashboardController`** — nyiapin angka-angka buat halaman dashboard
**`ProfileController`** — atur profil user

yang paling penting buat dijelasin: **controller nggak nulis HTML, dan nggak
nulis SQL.** dia cuma nyambungin Model sama View.

contoh paling gampang, `TicketController@index`:

```php
$user = $request->user();                    // siapa yang login
$query = $user->isTeknisi()                  // cek role
    ? Ticket::query()                        // teknisi: semua ticket
    : $user->tickets();                      // karyawan: ticket sendiri

$tickets = $query->with(['user', 'category'])->paginate(10);

return view('tickets.index', compact('tickets', 'categories'));
//                                              ↑ data dikirim ke view
```

---

## 5. Model

| file | tabel | isi |
|---|---|---|
| `app/Models/User.php` | `users` | data user, punya role, bisa login |
| `app/Models/Ticket.php` | `tickets` | data ticket, punya status & prioritas |
| `app/Models/Category.php` | `categories` | daftar kategori (Hardware, Software, ...) |

### Relationship

dua relationship yang saling nyambung:

```text
User     --hasMany-->     Ticket
Category --hasMany-->     Ticket

Ticket   --belongsTo-->   User
Ticket   --belongsTo-->   Category
```

cara bacanya: `hasMany` dipasang di sisi yang punya banyak data, `belongsTo`
dipasang di sisi yang punya foreign key.

kenapa `belongsTo` ada di Ticket? karena kolom `user_id` dan `category_id`
ada di tabel `tickets`, bukan di tabel `users`.

dipakai di view kayak gini:

```blade
{{ $ticket->user->name }}        {{-- data ticket -> user -> nama --}}
{{ $ticket->category->name }}    {{-- data ticket -> kategori --}}
```

### Hal lain di model

**`$fillable`** — daftar kolom yang boleh diisi pakai `::create()`.
ini proteksi mass assignment. kalo semua kolom boleh diisi, user bisa
ngirim `role=teknisi` diam-diam lewat form.

**`isTeknisi()`** di User — helper buat ngecek role, dipanggil di controller
dan di `TicketPolicy`.

**`statusLabel()` dan `priorityLabel()`** di Ticket — database nyimpen
`open`, `high` (bahasa inggris, ringkas), tapi yang diliat user
"Terbuka", "Tinggi" (bahasa manusia). method ini jembatannya.

---

## 6. Database

migration ada di `database/migrations/`.

```text
users
  id, name, email, password, role, timestamps

tickets
  id, user_id, category_id, title, description,
  priority, status, timestamps

categories
  id, name, timestamps
```

### Relasi di database

```text
tickets.user_id      →  users.id
tickets.category_id  →  categories.id
```

dua kolom itu namanya **foreign key**. inilah yang bikin relationship di
Model bisa jalan — Model cuma "./foreignkey" buat cari baris di tabel tujuan.

`user_id` punya `cascadeOnDelete()`, artinya kalo user dihapus, semua
ticket-nya ikut terhapus. kalo nggak, ticket-nya jadi nyasar (nunjuk user
yang udah nggak ada).

---

## 7. View

semua view ada di `resources/views/`.

| file | dipanggil dari |
|---|---|
| `dashboard.blade.php` | `DashboardController@index` |
| `tickets/index.blade.php` | `TicketController@index` |
| `tickets/create.blade.php` | `TicketController@create` |
| `tickets/show.blade.php` | `TicketController@show` |
| `layouts/app.blade.php` | dipanggil `<x-app-layout>` |

### Istilah blade yang sering ditanya

**`{{ $variabel }}`**
nampilin data. `{{ }}` otomatis di-escape, jadi aman dari XSS.

**`{{-- komentar --}}`**
komentar di blade. nongol di kode, nggak muncul di halaman.

**`@php ... @endphp`**
bisa nulis PHP di dalam view. di project ini dipakai buat nyiapin
variabel kecil biar view-nya nggak terlalu ramai.

**`@foreach` / `@forelse`**
`@forelse` = `@foreach` + ada bagian `@empty` kalau datanya kosong.
dipakai di daftar ticket.

**`@if` / `@unless`**
menentukan bagian mana yang ditampilin. di daftar ticket dipakai buat
menyesuaikan tampilan sesuai role.

**`old('nama_field')`**
ngambil input user yang tadi, dipake kalau validasi gagal. jadi user nggak
harus ngetik ulang.

**`@csrf`**
token keamanan. buat bukti request POST itu beneran dari form aplikasi kita,
bukan dari program lain. mencegah serangan CSRF.

**`@method('PATCH')`**
form HTML cuma bisa GET dan POST. jadi kalo mau update, form tetep POST
tapi kita sisipin field `_method` = PATCH. Laravel baca itu dan anggap
request-nya PATCH. namanya method spoofing.

### Blade Component

di `resources/views/components/`. dipanggil dengan `<x-nama>`, bukan
`@include`.

yang dipakai di project ini:

- `x-app-layout` — kerangka halaman
- `x-ticket-badges` — badge status + prioritas
- `x-primary-button`, `x-danger-button` — tombol
- `x-input-label`, `x-text-input`, `x-input-error` — bagian form
- `x-nav-link`, `x-dropdown` — navigasi
- `x-state-empty`, `x-skeleton` — tampilan saat kosong / loading

contoh: `<x-ticket-badges :status="$ticket" />` berarti file
`ticket-badges.blade.php` dipanggil dengan variabel `$status` berisi objek
Ticket.

### Kenapa view nggak boleh query database

kalo view ikut query, aturan MVC jadi kabur dan tanggung jawabnya pecah.
kode campur. dengan aturan ini, kalau ada bug "data nggak muncul", tinggal
cek satu tempat: controller.ozone

---

## 8. Flow Membuat Ticket

contoh flow yang paling bagus buat ditanyain, karena ngeejek semua layer:

```text
Karyawan buka /tickets/create
  ↓
Route tickets.create
  ↓
TicketController@create
  ↓
Gate::authorize('create', Ticket::class)   → cek boleh nggak
  ↓
Category::orderBy('name')->get()           → ambil daftar kategori
  ↓
view('tickets.create', compact('categories'))
  ↓
Blade nampilin form, user ngisi, klik "Kirim Ticket"
  ↓
POST /tickets  (+ @csrf)
  ↓
Route tickets.store
  ↓
TicketController@store
  ↓
Gate::authorize('create', ...)             → cek boleh nggak (lagi)
  ↓
$request->validate([...])                  → data form dicek dulu
  ↓
Ticket::create([...])                      → simpan lewat Model
  ↓
Database tersimpan (status = 'open')
  ↓
redirect ke /tickets + pesan flash
  ↓
TicketController@index
  ↓
view('tickets.index') → ticket baru keliatan di daftar
```

### Kenapa ada authorize di create DAN store?

karena `create` cuma nampilin form, `store` yang beneran nulis database.
cek dua-duanya itu lebih aman, dan kode create jadi nggak bocor walaupun
ada user yang nyelundup buka URL /tickets/create.

### Kenapa `user_id` diambil dari `$request->user()`, bukan dari form?

karena kalo `user_id` dikirim dari form, user bisa ngubah nilainya dan bikin
ticket atas nama orang lain. jadi server yang nentuin, bukan user.

### Kenapa `status` diisi `'open'` di controller?

karena status ticket baru selalu 'Terbuka'. dengan begitu user nggak
perlu pilih status pas bikin ticket, dan logika bisnisnya ngumpet di
controller, bukan di form.

---

## 9. Flow Melihat Ticket

```text
User buka /tickets
  ↓
Route tickets.index
  ↓
TicketController@index
  ↓
$user = $request->user()          → user yang login
  ↓
$user->isTeknisi() ?              → cek role
  ├─ teknisi  → Ticket::query()   → semua ticket
  └─ karyawan → $user->tickets() → WHERE user_id = dia
  ↓
->with(['user', 'category'])      → eager loading
  ↓
->paginate(10)                    → 10 per halaman
  ↓
view('tickets.index', compact('tickets', 'categories'))
  ↓
Blade nampilin, Pagination di bawah
```

### Yang menarik buat dijelasin: eager loading

kalo nggak pakai `with()`, tiap baris ticket di halaman itu bakal nanya
"siapa pembuatnya?" dan "kategori apa?" satu-satu ke database.

10 baris = 1 query + 10 + 10 = 21 query.

dengan `with(['user', 'category'])`, Laravel ambilin semua sekaligus:
1 query untuk ticket, 1 untuk user, 1 untuk kategori.

itu gunanya `with()`.

---

## 10. Flow Teknisi

bedanya teknisi sama karyawan cuma di tiga tempat:

**1. Melihat data (query)**
di `TicketController@index` dan `DashboardController@index`:

```php
$isTeknisi ? Ticket::query() : $user->tickets()
```

`Ticket::query()` = semua ticket. `$user->tickets()` = otomatis difilter
`where user_id = ...`.

**2. Boleh atau nggak (policy)**
di `TicketPolicy`:

```php
create() → return ! $user->isTeknisi();        // teknisi DITOLAK
view()   → return $user->isTeknisi() || $ticket->user_id === $user->id;
cancel() → return $ticket->user_id === $user->id && $ticket->status === 'open';
```

**3. Tampilan (view)**
`@unless ($isTeknisi)` nyembunyiin tombol "Buat Ticket" dari teknisi, dan
mengubah judul halaman jadi "Semua Ticket".

### Catatan penting soal middleware

di `bootstrap/app.php` ada middleware `teknisi` yang daftarnya ke
`EnsureUserIsTeknisi`. **tapi middleware itu belum dipakai di route
manapun.** jadi penjagaan role di project ini beneran yang jalan adalah
`TicketPolicy`, bukan middleware itu.

kalo ditanya "kenapa nggak pakai middleware?", jawabannya: aturan "boleh
bikin" dan "boleh lihat ticket ini" itu beda-beda per aksi, jadi lebih
cocok ditulis di Policy yang terpusat. middleware baru kepake kalo
sekarang ada halaman yang **seluruhnya** cuma untuk teknisi.

---

## 11. Authentication vs Authorization

keduanya soal "user nggak boleh akses sesuatu", tapi levelnya beda.

**Authentication = "siapa yang lagi login?"**

diurus sama:
- middleware `auth` di route → kalau belum login, lempar ke halaman login
- `AuthenticatedSessionController@store` → proses login

file terkait:
```text
app/Http/Controllers/Auth/AuthenticatedSessionController.php
app/Http/Requests/Auth/LoginRequest.php
app/Http/Middleware/Authenticate.php  (dari Laravel)
```

alur login:
```text
POST /login
  ↓
LoginRequest::rules()      → validasi format email & password
  ↓
LoginRequest::authenticate()
  ├─ cek rate limit (max 5x percobaan)
  ├─ Auth::attempt()       → cari user di tabel users, bandingin password
  └─ gagal? lempar error
  ↓
session::regenerate()      → ganti id session (cegah session fixation)
  ↓
sekarang user dianggap login
  ↓
auth()->user()             → ngasih data user ini ke controller & view
```

kuncinya: `auth()->user()` ngambil user yang lagi aktif dari session.
seluruh project bergantung pada itu — `$request->user()` di controller,
`auth()->user()->isTeknisi()` di blade.

**Authorization = "dia boleh ngapain?"**

diurus sama `TicketPolicy`. dipanggil dari controller dengan
`Gate::authorize()` dan dari view dengan `@can()`.

perbedaan cara manggilnya:

| tempat | kalau nggak boleh |
|---|---|
| `Gate::authorize()` di controller | lempar error 403, halaman nggak muncul |
| `@can()` di view | tombol disembunyiin, nggak keliatan |

### Kenapa password aman?

kolom password di tabel users nyimpen **hash**, bukan teks asli.
`User.php` nge-cast kolom itu jadi `hashed`, jadi password otomatis di-hash
pas disimpan. makanya `Auth::attempt()` yang dibandingkan adalah hash-nya,
bukan teksnya.

---

## 12. File yang Perlu Saya Ingat untuk Presentasi

| file | fungsi | poin yang bisa dijelasin |
|---|---|---|
| `routes/web.php` | nentuin request masuk ke mana | route nggak nyentuh data, cuma nunjukin controller |
| `app/Http/Controllers/TicketController.php` | ngatur alur ticket |role → query, `with()`, `view()` kirim ke blade |
| `app/Http/Controllers/DashboardController.php` | statistik dashboard | satu query `groupBy` buat semua angka |
| `app/Models/Ticket.php` | data ticket | `$fillable`, `belongsTo`, `statusLabel()` |
| `app/Models/User.php` | data user | `hasMany`, `isTeknisi()`, `casts` |
| `app/Models/Category.php` | data kategori | `hasMany` |
| `app/Policies/TicketPolicy.php` | aturan boleh atau nggak | authN vs authZ |
| `database/migrations/…_create_tickets_table.php` | struktur tabel | foreign key |
| `resources/views/tickets/index.blade.php` | tampilan daftar | `@forelse`, `route()`, `pagination` |
| `resources/views/tickets/create.blade.php` | form | `@csrf`, `old()`, alur submit |
| `resources/views/tickets/show.blade.php` | detail | `@can()`, `@method('PATCH')` |
| `resources/views/dashboard.blade.php` | angka dashboard | `$counts->get()` |
| `app/Http/Requests/Auth/LoginRequest.php` | validasi + cek password | rate limit |

### Cara cepat jawab kalau ditanya

| pertanyaan | jawabnya |
|---|---|
| "request ini masuk dari mana?" | Route |
| "siapa yang proses request?" | Controller |
| "data diambil lewat apa?" | Model / Eloquent |
| "data disimpan di mana?" | Database (tabel `tickets`, `users`, `categories`) |
| "data dikirim ke mana?" | View (`resources/views/`) |
| "kenapa karyawan cuma lihat ticket sendiri?" | query `$user->tickets()` + dicek `TicketPolicy` |
| "kenapa teknisi lihat semua?" | role `teknisi` → `Ticket::query()` tanpa filter |
| "kenapa ticket baru statusnya open?" | `TicketController@store` nulis `'status' => 'open'` |
| "gimana ticket tau pembuatnya?" | kolom `user_id` + relationship `belongsTo` di Ticket |
| "gimana ticket tau kategorinya?" | kolom `category_id` + relationship `belongsTo` di Ticket |
| "gimana tau udah login?" | `auth()->user()` ngambil dari session |
| "gimana tau boleh akses?" | `Gate::authorize()` → `TicketPolicy` |

---

## 13. Hal yang Tertinggal (bukan untuk presentasi)

ini hasil pengamatan pas baca kodenya, **belum diperbaiki**:

1. **route `register` nggak ada.** tapi `tests/Feature/Auth/RegistrationTest.php`
   masih ada, jadi 2 test itu gagal. testnya bawaan Breeze, dan emang
   nggak kepakai karena user dibuat lewat seeder.

2. **middleware `teknisi` belum dipakai** di route manapun. sudah
   dijelaskan di bagian 10.

3. **teknisi belum bisa ubah status ticket.** label `in_progress` dan
   `done` udah ada di `statusLabel()`, tapi belum ada route/controller
   untuk mengubahnya. jadi status praktisnya cuma `open` → `cancelled`.

4. **kolom `remember_token` dan middleware `verified` nggak kepakai.**
   `User` nggak implementasikan `MustVerifyEmail` (import-nya masih
   di-comment di `User.php`), jadi `->middleware('verified')` di
   `/dashboard` selalu lolos.

5. **`closed` belum pernah dipakai.** status ini ada di `statusLabel()`
   tapi nggak ada kode yang nulis nilai itu.
