<?php
class Utils{
    public static function isAdmin(){
        if(isset($_SESSION['no_admin'])){ ?>
            <script>
                window.location.href='index.php';
            </script>
<?php        }else{
            return true;
        }
    }
    
} ?>