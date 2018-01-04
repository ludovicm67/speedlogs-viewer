<?php
// get configuration
$conf = parse_ini_file("config.ini");
if (isset($conf['path_to_logfile'])
  && file_exists($conf['path_to_logfile'])
  && is_readable($conf['path_to_logfile'])) {
  $file_name = $conf['path_to_logfile'];
} else {
  $file_name = false;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Speedlogs viewer</title>
</head>
<body>
<?php if ($file_name === false): ?>
  <div>
    <h1>No logs to display for the moment...</h1>
    <h2>The log file is not available for the moment.</h2>
    <p>
      We aren't able to read it.
      If the file specified in the configuration file really exists,
      please check the permissions.
    </p>
  </div>
<?php else: ?>
  <div>
    Here will be the place of a nice graph!
    <?= $file_name; ?>
  </div>
<?php endif; ?>
</body>
</html>
