<?php 

require __DIR__ ."/admin/config/function.php";

if (isset($_SESSION['loggedIn'])) {
    
    logoutSession();
    redirect('login.php', 'Logout success');
} else {
    # code...
}

?>