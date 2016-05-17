<<<<<<< HEAD
<?php
session_start();
session_unset();
session_destroy(); 
setcookie("id_extreme","x",time()-3600,"/");
header("Location: index.php?logout");
=======
<?php
session_start();
session_unset();
session_destroy(); 
setcookie("id_extreme","x",time()-3600,"/");
header("Location: index.php?logout");
>>>>>>> 7368f1fa91c06f74b61fa0d88eff70f899e21054
?>