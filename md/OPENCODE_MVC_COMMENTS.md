# OpenCode Task — Tambahkan Komentar Pembelajaran MVC Laravel

## Tujuan

Saya sedang belajar Laravel dan project ini adalah project PKL saya.

Saya ingin menambahkan komentar-komentar pembelajaran ke kode yang SUDAH ADA supaya saya bisa memahami:

- bagaimana Laravel bekerja
- konsep MVC
- hubungan Route → Controller → Model → Database → View
- bagaimana request user diproses
- bagaimana data diambil dari database
- bagaimana data dikirim ke Blade View
- bagaimana data disimpan/update ke database
- bagaimana authentication dan authorization bekerja

Komentar ini ditujukan untuk SAYA sebagai pelajar yang masih belajar Laravel.

Komentar harus menjelaskan "kode ini ngapain dan kenapa dipakai", bukan cuma menerjemahkan nama fungsi.

---

# ATURAN PALING PENTING

## 1. JANGAN MENGUBAH LOGIC PROGRAM

Tugas utama hanya menambahkan atau memperbaiki komentar.

JANGAN:
- mengubah logic
- mengubah query
- mengubah nama variable
- mengubah nama function
- mengubah route
- mengubah database structure
- mengubah authorization
- mengubah UI
- mengubah behavior aplikasi
- melakukan refactor
- memperbaiki bug yang tidak berhubungan dengan komentar
- menambahkan fitur baru

Kalau menemukan bug atau sesuatu yang menurutmu kurang ideal, JANGAN memperbaikinya.

Cukup beri tahu saya setelah proses commenting selesai.

---

# 2. JANGAN COMMENT SETIAP BARIS

Saya TIDAK mau kode dipenuhi komentar seperti:

```php
// membuat variable user
$user = $request->user();

// mengambil id user
$userId = $user->id;
```

Komentar seperti itu tidak membantu saya memahami program.

Komentar hanya ditambahkan pada bagian yang penting untuk memahami:

- alur program
- konsep Laravel
- MVC
- database
- authentication
- authorization
- request/response
- relationship
- Blade
- routing
- validation
- Eloquent

---

# 3. BAHASA KOMENTAR

Semua komentar pembelajaran harus menggunakan:

## Bahasa Indonesia yang santai / gaul

Jangan menggunakan bahasa dokumentasi formal atau bahasa textbook.

Gunakan gaya seperti:

```php
// Request dari user masuk ke sini lewat route /tickets.
// Jadi controller ini yang mulai ngurus apa yang user minta.
```

atau:

```php
// Di sini kita ambil ticket lewat Model.
// Jadi controller nggak langsung ngobrol sama database,
// tapi minta bantuan Eloquent lewat Model Ticket.
```

atau:

```php
// Karena yang login teknisi, dia boleh lihat semua ticket.
// Kalau bukan teknisi, query-nya dibatasi ke ticket miliknya sendiri.
```

atau:

```php
// Data dari form dicek dulu sebelum masuk database.
// Kalau ada yang nggak sesuai aturan, Laravel bakal balikin error ke form.
```

Target gayanya: anak RPL lagi ngejelasin kode ke temennya.

Jangan terlalu formal, tapi jangan terlalu alay atau bercanda berlebihan.

---

# 4. FOKUS UTAMA: MVC

Komentar harus membantu saya melihat bagian mana yang termasuk:

- ROUTE
- CONTROLLER
- MODEL
- DATABASE
- VIEW

Gunakan prefix komentar yang konsisten.

Contoh:

```php
// MVC — ROUTE:
// Request GET /tickets diarahkan ke TicketController@index.
```

```php
// MVC — CONTROLLER:
// Di sini controller mulai ngatur alur request dari user.
```

```php
// MVC — MODEL:
// Ticket::query() berarti kita mulai berinteraksi dengan data ticket
// lewat Eloquent Model Ticket.
```

```php
// MVC — DATABASE:
// Query ini nantinya dijalankan ke database untuk mengambil data ticket.
```

```blade
{{-- MVC — VIEW:
     Data dari controller akhirnya dipakai di Blade ini
     buat ditampilkan ke user. --}}
```

Jangan menambahkan prefix MVC ke setiap baris. Gunakan hanya ketika membantu menunjukkan konsep.

---

# 5. ALUR MVC YANG HARUS BISA SAYA IKUTI

Pastikan komentar membantu saya memahami alur:

User
↓
Route
↓
Controller
↓
Model
↓
Database
↓
Model
↓
Controller
↓
View
↓
User

Saya ingin bisa membuka beberapa file dan mengikuti satu request dari awal sampai akhir.

---

# FILE YANG HARUS DIPRIORITASKAN

## 1. routes/web.php

File ini sangat penting karena menjadi pintu masuk request web.

Tambahkan komentar pada bagian:

- route dashboard
- middleware auth
- route resource tickets
- route create ticket
- route store ticket
- route show ticket
- route cancel ticket
- route profile
- hubungan route dengan controller

Contoh gaya komentar:

```php
// MVC — ROUTE:
// User buka /tickets lewat browser.
// Route ini meneruskan request tersebut ke TicketController@index.
//
// Jadi route belum mengambil data ticket.
// Dia cuma menentukan: "request ini harus ditangani siapa?"
Route::resource('tickets', TicketController::class)
    ->only(['index', 'create', 'store', 'show']);
```

Jelaskan juga secara singkat kenapa:

```php
->middleware('auth')
```

dipakai.

---

# 2. app/Http/Controllers/TicketController.php

INI SALAH SATU FILE TERPENTING.

Berikan komentar pembelajaran pada:

## index()

Saya harus bisa memahami:

- request masuk ke controller
- mengambil user yang sedang login
- mengecek apakah user teknisi
- teknisi melihat semua ticket
- karyawan hanya melihat ticket miliknya
- query Eloquent
- eager loading `with()`
- pagination
- mengambil category
- mengirim data ke View

Contoh:

```php
// MVC — CONTROLLER:
// Method ini dijalankan ketika user membuka halaman daftar ticket.
//
// Controller tugasnya bukan menampilkan HTML langsung.
// Dia ngatur data apa yang dibutuhkan View.
```

Untuk:

```php
$user = $request->user();
```

jelaskan bahwa ini mengambil user yang sedang login.

Untuk:

```php
if ($user->isTeknisi())
```

jelaskan kenapa role mempengaruhi query.

Untuk:

```php
$query = Ticket::query();
```

jelaskan bahwa ini memulai query menggunakan Eloquent Model.

Untuk:

```php
$user->tickets();
```

jelaskan hubungannya dengan relationship di User Model.

Untuk:

```php
$query->with(['user', 'category'])
```

jelaskan secara sederhana apa itu eager loading dan kenapa relationship tersebut ikut diambil.

Untuk:

```php
return view('tickets.index', compact('tickets', 'categories'));
```

jelaskan bahwa:

Controller
→ mengirim data
→ ke Blade View

Ini harus menjadi salah satu contoh utama MVC di project.

---

## create()

Jelaskan:

- authorization lewat Gate
- mengambil category
- mengirim category ke View
- kenapa create hanya menampilkan form dan belum menyimpan data

---

## store()

INI SALAH SATU CONTOH MVC TERPENTING.

Komentari alur:

User submit form
→ POST /tickets
→ Route
→ TicketController@store
→ validasi
→ Ticket::create()
→ Database
→ redirect
→ Ticket list

Jelaskan:

### Request

```php
Request $request
```

sebagai data/request yang dikirim browser.

### Authorization

```php
Gate::authorize('create', Ticket::class);
```

jelaskan bahwa ini mengecek apakah user boleh membuat ticket.

### Validation

```php
$request->validate(...)
```

jelaskan kenapa data form harus divalidasi sebelum masuk database.

### Ticket::create()

Jelaskan:

```php
Ticket::create(...)
```

berarti data dibuat lewat Model Ticket dan akhirnya disimpan ke database.

Jelaskan juga fungsi:

```php
'user_id' => $request->user()->id
```

karena ticket perlu tahu siapa pembuatnya.

Jelaskan:

```php
'status' => 'open'
```

karena ticket baru otomatis dimulai dengan status Open.

### redirect()

Jelaskan bahwa setelah berhasil disimpan, Controller mengarahkan user kembali ke route tertentu.

---

## show()

Jelaskan:

- Route Model Binding pada `Ticket $ticket`
- bagaimana Laravel tahu ticket mana yang diminta
- authorization
- eager loading relationship
- mengirim ticket ke Blade View

Khusus `Ticket $ticket`, beri komentar yang menjelaskan konsep:

```text
/tickets/5
↓
Laravel melihat ID 5
↓
Laravel mencari Ticket dengan ID 5
↓
hasilnya otomatis masuk ke variable $ticket
```

Gunakan bahasa sederhana.

---

## cancel()

Jelaskan:

- authorization
- update status
- database update
- redirect
- flash session `success`

Jelaskan bahwa:

```php
$ticket->update(...)
```

adalah operasi update terhadap record database melalui Eloquent Model.

---

# 3. app/Http/Controllers/DashboardController.php

Ini juga penting untuk memahami MVC.

## index()

Jelaskan:

- user yang sedang login
- pengecekan role teknisi
- kenapa teknisi menggunakan `Ticket::query()`
- kenapa karyawan menggunakan `$user->tickets()`
- grouped query
- menghitung jumlah ticket berdasarkan status
- mengambil ticket terbaru
- `with(['user', 'category'])`
- `view('dashboard', [...])`

Khusus bagian:

```php
$scope = fn () => $isTeknisi ? Ticket::query() : $user->tickets();
```

jelaskan secara santai bahwa:

"Di sini dibuat function kecil supaya kita bisa pakai aturan query yang sama beberapa kali tanpa nulis ulang."

Untuk bagian `$counts`, jelaskan bahwa hasilnya dipakai dashboard untuk angka:

- Total
- Terbuka
- Diproses
- Selesai

Untuk:

```php
return view('dashboard', [...]);
```

jelaskan bahwa data statistik dan ticket terbaru dikirim ke View.

---

# 4. app/Models/User.php

Fokus pada:

- class User
- authentication
- HasFactory
- Notifiable
- Fillable
- Hidden
- casts
- relationship `tickets()`
- `isTeknisi()`

Untuk:

```php
public function tickets()
{
    return $this->hasMany(Ticket::class);
}
```

jelaskan:

```text
1 User
↓
punya banyak Ticket
```

dan bagaimana relationship ini nanti dipakai oleh:

```php
$user->tickets()
```

di TicketController dan DashboardController.

Untuk:

```php
public function isTeknisi()
```

jelaskan bahwa ini helper sederhana untuk mengecek role user.

---

# 5. app/Models/Ticket.php

INI WAJIB DI-COMMENT DENGAN BAIK.

## $fillable

Jelaskan kenapa field-field tertentu boleh diisi ketika menggunakan:

```php
Ticket::create(...)
```

Jelaskan konsep mass assignment secara sederhana.

## user()

```php
return $this->belongsTo(User::class);
```

Jelaskan:

```text
Banyak Ticket
↓
masing-masing dimiliki oleh 1 User
```

dan bagaimana relationship ini dipakai:

```php
$ticket->user
```

## category()

Jelaskan relationship:

```text
1 Category
↓
banyak Ticket
```

dan bagaimana:

```php
$ticket->category
```

bisa mengambil data kategori.

## priorityLabel()

Jelaskan bahwa database menyimpan value seperti:

```text
low
medium
high
```

sedangkan method ini mengubahnya menjadi label yang lebih enak ditampilkan:

```text
Rendah
Menengah
Tinggi
```

## statusLabel()

Jelaskan konsep yang sama untuk status.

---

# 6. app/Models/Category.php

Fokus pada:

```php
$fillable
```

dan:

```php
tickets()
```

Jelaskan relationship:

```text
Category
   |
   └── banyak Ticket
```

Hubungkan dengan penggunaan nyata di project.

---

# 7. database/migrations/

Prioritaskan migration yang berhubungan dengan:

## users

Fokus pada:
- kenapa table dibuat
- primary key
- role
- timestamps

Tidak perlu komentar setiap field.

## tickets

Jika file ticket migration tersedia, jelaskan:
- primary key
- user_id
- category_id
- title
- description
- priority
- status
- foreign key / relationship jika ada
- timestamps

Hubungkan:

```text
tickets.user_id
↓
users.id

tickets.category_id
↓
categories.id
```

Ini penting untuk memahami hubungan Model ↔ Database.

## categories

Jelaskan struktur table kategori dan bagaimana ticket menggunakannya.

Migration bawaan Laravel seperti cache, jobs, password reset, dan sessions tidak perlu diberi komentar panjang.

---

# 8. resources/views/dashboard.blade.php

Ini bagian VIEW.

Jelaskan konsep Blade dan bagaimana data dari Controller digunakan.

Fokus pada:

```blade
{{ $total }}
```

```blade
{{ $counts->get(...) }}
```

```blade
@foreach
```

```blade
@forelse
```

```blade
route(...)
```

```blade
$ticket->title
```

```blade
$ticket->user->name
```

```blade
$ticket->category->name
```

Jelaskan bahwa:

```text
Controller
↓
ngasih $tickets / $counts / $recent
↓
Blade
↓
menampilkan data ke browser
```

Jangan menjelaskan setiap class Tailwind satu per satu.

---

# 9. resources/views/tickets/index.blade.php

Fokus pada:

- role user
- loop ticket
- `@forelse`
- route menuju detail ticket
- penggunaan data relationship
- pagination
- session success message
- conditional UI berdasarkan role

Jelaskan bahwa View hanya bertugas menampilkan data dan UI.

Kalau ada:

```blade
{{ route('tickets.show', $ticket) }}
```

jelaskan hubungan:

View
→ named route
→ Controller
→ detail ticket.

---

# 10. resources/views/tickets/create.blade.php

INI WAJIB DI-COMMENT KARENA BAGUS UNTUK PRESENTASI MVC.

Jelaskan:

```blade
<form method="POST" action="{{ route('tickets.store') }}">
```

Alurnya:

```text
Form
↓
POST
↓
tickets.store
↓
TicketController@store
↓
validasi
↓
Model
↓
Database
```

Jelaskan:

```blade
@csrf
```

secara sederhana bahwa Laravel memakai token ini untuk membantu memastikan request POST berasal dari form aplikasi yang valid dan membantu mencegah CSRF.

Jelaskan:

```blade
old(...)
```

sebagai cara Laravel mempertahankan input sebelumnya ketika validasi gagal.

Jelaskan:

```blade
$categories
```

berasal dari Controller.

---

# 11. resources/views/tickets/show.blade.php

Fokus pada:

- route detail
- `$ticket`
- relationship user
- relationship category
- status/priority display
- authorization `can(...)`
- form cancel
- `@csrf`
- `@method('PATCH')`

Khusus:

```blade
@method('PATCH')
```

jelaskan kenapa form HTML yang sebenarnya hanya mendukung GET/POST bisa digunakan untuk request PATCH melalui Laravel method spoofing.

---

# 12. AUTHENTICATION

Cari file authentication Laravel Breeze yang memang dipakai project.

Jangan comment semua file auth.

Cari bagian penting yang membantu saya memahami:

```text
Login
↓
Authentication
↓
User berhasil login
↓
auth()->user()
↓
Controller / View bisa tahu siapa user yang sedang login
```

Fokus hanya pada file yang memang relevan dengan flow tersebut.

---

# 13. AUTHORIZATION / GATE / POLICY

Cari file Policy/Gate yang digunakan oleh:

```php
Gate::authorize(...)
```

dan:

```php
auth()->user()->can(...)
```

Jelaskan:

- authentication = "siapa yang login?"
- authorization = "user ini boleh melakukan apa?"

Ini penting karena project punya role:
- karyawan
- teknisi

Buat komentar yang gampang dipahami.

---

# 14. BLADE COMPONENT

Kalau ada component di:

```text
resources/views/components/
```

jangan comment semua component.

Prioritaskan component yang berhubungan dengan:
- ticket badge
- button
- layout
- form
- status

Jelaskan bagaimana component dipanggil dari Blade lain.

---

# 15. JANGAN TERLALU COMMENT TAILWIND

Jangan memberi komentar seperti:

```blade
{{-- flex = display flex --}}
{{-- px-5 = padding horizontal --}}
{{-- text-sm = font kecil --}}
```

Ini TIDAK diperlukan.

Tailwind bukan fokus tugas saya.

Fokusnya Laravel MVC.

---

# 16. COMMENT HARUS MEMBANTU PRESENTASI

Setiap bagian penting harus membuat saya bisa menjawab pertanyaan seperti:

### "Request ini masuk dari mana?"
→ Route

### "Siapa yang memproses request?"
→ Controller

### "Data diambil lewat apa?"
→ Model / Eloquent

### "Data sebenarnya disimpan di mana?"
→ Database

### "Data dikirim ke mana?"
→ View

### "View-nya ada di mana?"
→ resources/views/...

### "Kenapa karyawan cuma bisa lihat ticket sendiri?"
→ Query + authorization

### "Kenapa teknisi bisa lihat semua ticket?"
→ Role + query/authorization

### "Kenapa ticket baru statusnya Open?"
→ Controller saat create ticket

### "Gimana ticket tahu siapa pembuatnya?"
→ user_id + User/Ticket relationship

### "Gimana ticket tahu kategorinya?"
→ category_id + Category/Ticket relationship

---

# 17. BUAT ALUR UTAMA SEBAGAI KOMENTAR

Untuk flow penting, boleh gunakan blok komentar seperti:

```php
/*
 * ALUR MVC:
 *
 * User submit form
 *      ↓
 * Route tickets.store
 *      ↓
 * TicketController@store
 *      ↓
 * Validasi request
 *      ↓
 * Ticket Model
 *      ↓
 * Database
 *      ↓
 * Redirect ke daftar ticket
 */
```

Gunakan hanya di bagian flow utama.

Jangan membuat diagram seperti ini di setiap function.

---

# 18. PRIORITAS FILE

## PRIORITAS 1 — WAJIB

1. `routes/web.php`
2. `app/Http/Controllers/TicketController.php`
3. `app/Http/Controllers/DashboardController.php`
4. `app/Models/Ticket.php`
5. `app/Models/User.php`
6. `app/Models/Category.php`
7. ticket migrations
8. `resources/views/tickets/index.blade.php`
9. `resources/views/tickets/create.blade.php`
10. `resources/views/tickets/show.blade.php`
11. `resources/views/dashboard.blade.php`

## PRIORITAS 2

12. Policy/Gate authorization
13. authentication-related files
14. relevant Blade components

## PRIORITAS 3

File lain yang tidak berhubungan langsung dengan MVC tidak perlu diberi komentar tambahan.

---

# 19. JANGAN MEMBUAT KOMENTAR PALSU

Komentar HARUS berdasarkan kode yang benar-benar ada.

Jika kode menggunakan:

```php
Gate::authorize()
```

jelaskan Gate.

Jangan bilang project menggunakan middleware role tertentu kalau memang tidak ada.

Jika tidak yakin fungsi sebuah bagian, baca file terkait terlebih dahulu.

Jangan menebak.

---

# 20. SETELAH SELESAI

Setelah semua komentar ditambahkan:

1. Pastikan logic tidak berubah.
2. Pastikan tidak ada syntax error.
3. Pastikan tidak ada variable yang berubah.
4. Pastikan route tetap sama.
5. Pastikan database behavior tetap sama.
6. Pastikan UI tidak berubah.

Kemudian buat file:

`MVC-GUIDE.md`

Isi file tersebut dengan:

# IT Ticketing — Panduan MVC

## 1. Gambaran Besar

Jelaskan project secara singkat.

## 2. Struktur MVC

```text
Route
  ↓
Controller
  ↓
Model
  ↓
Database
  ↓
Controller
  ↓
View
```

## 3. Route

Jelaskan route utama project.

## 4. Controller

Jelaskan controller yang digunakan.

## 5. Model

Jelaskan:
- User
- Ticket
- Category

## 6. Database

Jelaskan table dan relationship.

## 7. View

Jelaskan Blade views utama.

## 8. Flow Membuat Ticket

Buat flow lengkap:

```text
Karyawan buka form
↓
GET /tickets/create
↓
TicketController@create
↓
categories diambil
↓
View menampilkan form
↓
Karyawan submit form
↓
POST /tickets
↓
TicketController@store
↓
Validation
↓
Ticket::create()
↓
MySQL
↓
Redirect
↓
tickets.index
```

## 9. Flow Melihat Ticket

Jelaskan alurnya.

## 10. Flow Teknisi

Jelaskan bagaimana role teknisi mempengaruhi data yang bisa dilihat.

## 11. Authentication vs Authorization

Jelaskan perbedaannya dengan contoh project ini.

## 12. File yang Perlu Saya Ingat untuk Presentasi

Buat tabel:

| File | Fungsi |
|---|---|
| routes/web.php | Menentukan request masuk ke mana |
| TicketController.php | Mengatur alur ticket |
| Ticket.php | Mengurus data ticket |
| User.php | Data user + relationship |
| Category.php | Data kategori |
| migrations | Struktur database |
| tickets/*.blade.php | Tampilan ticket |
| dashboard.blade.php | Tampilan dashboard |

---

# 21. HASIL YANG SAYA INGINKAN

Saya tidak mencari codebase yang penuh komentar.

Saya mencari codebase yang kalau saya buka, saya bisa melihat:

"oh, request-nya mulai dari sini → masuk controller → controller pakai model → model ambil database → hasilnya dilempar ke view."

Komentar harus membuat saya BISA MENJELASKAN PROJECT INI SENDIRI saat presentasi.

Prioritas utama:

PEMAHAMAN > JUMLAH KOMENTAR

Jadi lebih baik 20 komentar yang benar-benar berguna daripada 200 komentar yang cuma menjelaskan syntax.

---

# FINAL CHECK

Sebelum selesai, lakukan pengecekan:

- [ ] Tidak ada logic yang berubah
- [ ] Tidak ada fitur yang berubah
- [ ] Tidak ada variable yang diganti
- [ ] Tidak ada route yang diganti
- [ ] Tidak ada database structure yang diganti
- [ ] Komentar menggunakan Bahasa Indonesia santai
- [ ] Komentar tidak terlalu formal
- [ ] Komentar tidak alay
- [ ] Komentar fokus ke konsep Laravel
- [ ] MVC flow terlihat jelas
- [ ] Route → Controller → Model → Database → View bisa diikuti
- [ ] `MVC-GUIDE.md` dibuat
- [ ] `MVC-GUIDE.md` berdasarkan kode aktual project, bukan teori generik

Jangan melakukan perubahan lain di luar scope ini.
