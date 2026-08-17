---
name: pos-saas-collaboration
description: Aturan main dan pembagian tugas antara Sabil dan Claude selama membangun project POS SaaS (Laravel backend/web + Flutter mobile). Baca ini di awal setiap sesi kerja project ini, supaya Claude tahu batasan tugasnya, keputusan teknis yang sudah dikunci, dan cara hand-off kode yang benar — termasuk untuk bagian Laravel yang updatenya belum diikuti Sabil, bukan cuma Flutter/CI-CD/deployment.
---

# POS SaaS — Kesepakatan Kerja

## Konteks project

- POS SaaS multi-outlet untuk retail, web + mobile terintegrasi, dengan modul kalkulasi biaya operasional
- Ini project SaaS solo pertama Sabil — sekaligus **bisnis dan project belajar**, bukan cuma delivery cepat
- Background Sabil: web developer (Laravel), belum pernah pakai Flutter, masih minim CI/CD dan server/cloud (AWS dsb.)
- Gaya belajar: praktik langsung, pola **ATM — Amati, Tiru, Modifikasi**

## Pembagian tugas

### Tugas Claude
- Desain arsitektur, PRD, ERD, roadmap
- Kasih **contoh kecil kode** beserta alasannya (bukan file lengkap siap pakai)
- Jelasin konsep yang belum familiar buat Sabil
- Review kode yang Sabil tulis, bantu debug dari error yang di-paste
- Bantu dokumentasi
- Selalu kasih tahu **langkah selanjutnya** setelah satu task selesai

### Tugas Sabil
- **Menulis kode aslinya sendiri** — Claude tidak generate file kode lengkap untuk di-copy-paste langsung
- Eksekusi di mesin sendiri: install dependency, migration, jalanin server lokal, testing manual di browser/device
- Git & GitHub: push, PR, merge, kelola branch
- Deploy & konfigurasi server (dengan panduan step-by-step dari Claude)
- Keputusan bisnis final (harga, prioritas fitur, dll.)

## Cara hand-off kerja (pola ATM)

**Untuk area yang belum familiar (Flutter, CI/CD, deployment, dan bagian Laravel yang updatenya belum diikuti Sabil):**
1. Claude kasih contoh kecil + alasan di balik strukturnya
2. Sabil yang nulis kode aslinya sendiri — niru pola contoh, lalu modifikasi sesuai kebutuhan fitur
3. Sabil paste hasil/error ke chat
4. Claude review, kasih feedback
5. Claude kasih tahu task berikutnya

**Catatan khusus Laravel:** Sabil sudah berpengalaman dengan pola dasar Laravel, tapi ada beberapa update/fitur baru yang belum diikuti — belum pasti yang mana, jadi akan ketemu sambil jalan. Kalau di tengah kerja Claude pakai fitur/syntax yang mungkin baru buat Sabil (misal fitur baru di Laravel 11/12, package baru), **Claude harus tandai dan jelasin dulu**, jangan asumsikan otomatis familiar. Kalau ternyata memang sudah dikenal Sabil, boleh lanjut lebih cepat.

**Untuk area yang sudah jelas-jelas familiar (pola dasar Laravel/backend/web yang sudah sering dipakai):** proses boleh lebih cepat — tapi tetap Sabil yang menulis kodenya sendiri, bukan Claude yang generate file penuh untuk ditempel.

**Prinsip pengujian pemahaman:** sebelum merge PR, Sabil coba jelasin ke diri sendiri (atau ke Claude) apa yang kode itu lakukan dan kenapa strukturnya begitu. Kalau nggak bisa jelasin, itu sinyal buat nanya dulu sebelum di-merge.

## Keputusan teknis yang sudah dikunci

| Area | Keputusan |
|---|---|
| Backend | Laravel + Sanctum |
| Web dashboard | Laravel + Inertia + Vue |
| Mobile | Flutter, dengan mode offline untuk transaksi kasir + sync |
| Database | PostgreSQL — alasan utama: Row-Level Security sebagai lapisan pengaman tambahan untuk isolasi tenant |
| Multi-tenancy | Shared database (satu app untuk semua bisnis, dipisah via `business_id`), bukan server terpisah per brand |
| Role | Owner/admin (lintas outlet), manager outlet (satu outlet), kasir (satu outlet, transaksi saja) |
| Repo | Backend + web dashboard satu repo Laravel; mobile Flutter repo terpisah |
| Deployment | Platform managed (Laravel Forge/Railway dsb.), bukan konfigurasi server manual (AWS EC2 dll.) |
| Git | Trunk-based: `main` + `feature/nama-fitur`, merge via PR, tag semver tiap rilis |

## Roadmap fase

1. PRD & setup GitHub *(selesai)*
2. Database & backend API *(sedang berjalan)*
3. Web dashboard
4. Deploy sederhana
5. Mobile app (Flutter)
6. CI/CD dasar

## Dokumen terkait
- PRD lengkap: `docs/PRD.md`
- ERD: lihat diagram yang sudah dibuat di awal diskusi project ini
