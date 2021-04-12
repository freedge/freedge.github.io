<?php

// generate an arbitrary header to try to mess up with Squid
if (isset($_GET['cus'])) {
    header(base64_decode($_GET['cus']));
}
?>
<html>
<meta charset="utf-8" />
<meta http-equiv="Content-Security-Policy" content="default-src 'none'" />
<title>🐟</title>
<body>
<?php

if (isset($_GET['content'])) {
    echo base64_decode($_GET['content']);
}

?>
</body>
</html>

