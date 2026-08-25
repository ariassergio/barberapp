<?php
require 'admin/config/db.php';
$res = mysqli_query($conn, 'DESCRIBE configuracion_admin');
while ($r = mysqli_fetch_assoc($res)) {
    print_r($r);
}
