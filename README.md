#### Machine parking simulation

#### With livechart

https://github.com/codesyariah122/parking-machine/assets/13291805/64ec1667-c098-4711-be01-52c55e58c16b

https://github.com/codesyariah122/parking-machine/assets/13291805/df2c6bf9-b023-466a-bf4c-d7535f7454cb

#### Report Data

https://github.com/codesyariah122/parking-machine/assets/13291805/19813f34-b15e-4fa1-9737-5a3895af90c3

https://github.com/codesyariah122/parking-machine/assets/13291805/da69e630-c061-485f-8e2a-484e959fa25e

https://github.com/codesyariah122/parking-machine/assets/13291805/2b515025-177c-46f7-8b28-0a82f78f7ff8

#### Print struk

https://github.com/codesyariah122/parking-machine/assets/13291805/3bf06301-4831-4ca2-8aea-265f2d7070bf

#### Preview Web App

https://github.com/codesyariah122/parking-machine/assets/13291805/281e36e6-386b-4645-95f7-40694ff958ac

#### Showing toast for new payment event

https://github.com/codesyariah122/parking-machine/assets/13291805/864c5de9-4958-436f-9782-1f74c58143ff

#### Fixing duration over 1day

https://github.com/codesyariah122/parking-machine/assets/13291805/df82bcbb-25af-4bef-835e-c6f9dc30c9e6

### With Long Polling Native Javascript Watch

**Payment realtime**

https://github.com/codesyariah122/parking-machine/assets/13291805/0a6cac28-273f-4725-9daa-702b737f6655

**tickets realtime**

https://github.com/codesyariah122/parking-machine/assets/13291805/3a0c09f5-9db9-4fd7-bd54-cc8204fb5dec

https://github.com/codesyariah122/parking-machine/assets/13291805/b3938829-774d-4f73-a07b-200a3dc5f356

https://github.com/codesyariah122/parking-machine/assets/13291805/28fec415-733e-499c-8054-0f31f394bb99

https://github.com/codesyariah122/parking-machine/assets/13291805/155cc082-a5e7-4d6c-924c-cbe9c47dc4f7

#### For windows user

run the command here :

```
powershell.exe -ExecutionPolicy Bypass -File start.ps1

```

> Dibuat dengan bahasa pemrogramman PHP versi 8.2, dengan mengusung konsep MVC sebagai alur utama programm ini.

### Tech Stack

1. PHP Native with mvc concept (PHP 8.2)
2. PHP::PDO Extention for database cumunication
3. MariaDB database server
4. Javascript, JQuery, Select2 , JQuery.Ajax, Sweetalert, Lodash
5. Tailwindcss with plugins flowbite

Link documentasi

1. Lodash = https://lodash.com/docs/4.17.15
2. ChartJS = https://www.chartjs.org/docs/latest/
3. sweetalert = https://sweetalert2.github.io/
4. flowbite tailwind plugins = https://flowbite.com/docs/getting-started/quickstart/
5. tailwindcss = https://tailwindcss.com/docs/installation
6. fontawesome = https://fontawesome.com/search?o=r&m=free
7. PHP :: PDO =
    - https://www.php.net/manual/en/book.pdo.php
    - https://www.phptutorial.net/php-pdo/
8. Javascript = https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Functions

##### Database Design

![Screenshot from 2023-08-02 19-18-26](https://github.com/codesyariah122/parking-machine/assets/13291805/a5962848-9901-474a-b76d-574cc0627d2d)

##### Run in dev

> Clone repository ini terlebih dahulu, selanjutnya akses melalui terminal / cmd(di windows), buka terminal dan akses direktori hasil clone repository ini kemudian jalankan baris perintah dibawah ini.

```bash
# chmod +x start.sh
# ./start.sh
```

> Selanjutnya setelah server running dengan informasi seperti ini :

```bash
# [Mon Jul  3 23:34:34 2023] PHP 8.2.7 Development Server (http://localhost:4041) started
```

> Silahkan buka web browser : Firefox, google chrome atau yang lainnya. akses url sesuai dengan informasi running server yang terdapat di layar terminal / cmd(di windows), yaitu `http://localhost:4041`. Jika terdapat error database tidak ditemukan :

---

![db_error](https://github.com/codesyariah122/skripsi/assets/13291805/0cd005de-2db2-4eab-aacf-99225d1725a7)

---

> Silahkan import terlebih dahulu file database yang tersedia di dalam direktori repository, file database dengan nama `parkings.sql` , akses database service yang biasa di gunakan, dalam kasus ini saya menggunakan phpmyadmin, kemudian buat database baru lalu import table baru dari file `parkings.sql`.

> Selanjutnya setup file .env, buka kembali terminal akses direktori repository hasil clone, kemudian jalankan baris perintah berikut ini :

```bash
# cp .env.example .env
```

> Setelah file .env terbuat , buka file .env dan sesuaikan konfigurasi database dengan database yang telah di siapkan sebelumnya.

> Selanjutnya buka kembali web browser, arahkan url browser ke halaman create user yaitu `http://localhost:4041/users/add`, maka akan dibuatkan struktur data baru untuk data user di table users. Boleh cek ke phpmyadmin di table users.

> Selanjutnya silahkan login dengan mengetikan url di address bar : `http://localhost:4041/login` untuk mengakses halaman dashboard. Untuk autentikasi login silah check di file : https://github.com/codesyariah122/parking-machine/blob/main/App/Data/UserData.php
