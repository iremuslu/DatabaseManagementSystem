<?php
require_once "BasicDB.php";
/** Veritabanı Sunucusu */
define('DB_server', 'localhost');
/** Veritabanı Adı */
define('DB_name', 'chil_db');
/** Veritabanı Kullanıcı Adı */
define('DB_user', 'chil_db');
/** Veritabanı Şifresi */
define('DB_password', 'EJqMAlm%*4akObzn');
/** Bot dosyası */
define('FileName', 'dataset.csv');
// Veritabanına Bağlan
$db = new BasicDB(DB_server,DB_name, DB_user, DB_password);
// Hata Yazdır
if(@$db->connect_errno) {
    echo 'Veritabanına bağlanırken hata oluştu ' . $db->connect_errno;
    exit;
}
?>