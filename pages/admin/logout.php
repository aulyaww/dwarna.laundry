<?php
require __DIR__.'/../../includes/app.php';
if ($_SERVER['REQUEST_METHOD']!=='POST') {http_response_code(405);exit('Gunakan tombol Keluar dari halaman pengelola.');}
validate_csrf();
$_SESSION=[];
if (ini_get('session.use_cookies')) { $p=session_get_cookie_params(); setcookie(session_name(),'',time()-3600,$p['path'],$p['domain'],$p['secure'],$p['httponly']); }
session_destroy();
redirect_to('login.php');
