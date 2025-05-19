<?php
<?php
require_once('config/db.php');
if($conn) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed!";
}
?>