-- Jalankan hanya jika kolom `hero_image` belum ada di tabel `settingss`.
-- Jangan jalankan ulang setelah kolom berhasil dibuat.
ALTER TABLE `settingss`
ADD COLUMN `hero_image` VARCHAR(255) NULL AFTER `app_icon`;
