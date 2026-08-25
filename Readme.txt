1. XAMPP telepítése
2. Apache indítása
3. MySQL/MariaDB indítása
4. ProjectBoard másolása a htdocs könyvtárba
5. phpMyAdmin megnyitása
6. projectboard adatbázis létrehozása
7. projectboard.sql importálása
8. Adatbázis-beállítások ellenőrzése
9. ProjectBoard megnyitása böngészőben
10. Bejelentkezés és működés ellenőrzése

PROJECTBOARD
│
├── config
│   └── db.php
│
├── controllers
│   ├── AuthController.php
│   └── ProjectController.php
│
├── models
│   ├── Auth.php
│   ├── Project.php
│   └── ProjectVersion.php
│
├── public
│   ├── api
│   │   └── stats.php
│   ├── css
│   │   └── style.css
│   └── index.php
│
├── views
│   ├── auth
│   │   └── login.php
│   ├── projects
│   │   ├── create.php
│   │   ├── edit.php
│   │   ├── index.php
│   │   └── show.php
│   └── versions
│       └── create.php
│
├── .gitignore
├── local-notes.md
├── projectboard.sql
├── Readme.md
└── Readme.txt

http://localhost/ProjectBoard/public/
