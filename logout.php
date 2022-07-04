<?php
session_start();
unset($_SESSION['PHP_PACKAGE_ADMIN']);
unset($_SESSION['PHP_PACKAGE_TOKEN']);
if (isset($_SERVER['HTTP_REFERER'])) {
    ?>
<script type="text/javascript">
window.location.href = "index.html";
</script>
<?php
} else {
    ?>
<script type="text/javascript">
window.location.href = "index.html";
</script>
<?php
}
exit;
?>