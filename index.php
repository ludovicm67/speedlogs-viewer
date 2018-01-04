<?php
// get configuration
$conf = parse_ini_file("config.ini");
if (isset($conf['path_to_logfile'])
  && file_exists($conf['path_to_logfile'])
  && is_readable($conf['path_to_logfile'])) {
  $file_name = $conf['path_to_logfile'];
} else {
  $file_name = NULL;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Speedlogs viewer</title>
  <style>
    textarea {
      width: 650px;
      height: 350px;
    }
  </style>
</head>
<body>
  <div>
  <?php if (is_null($file_name)) { ?>
    <h1>No logs to display for the moment...</h1>
    <h2>The log file is not available for the moment.</h2>
    <p>
      We aren't able to read it.
      If the file specified in the configuration file really exists,
      please check the permissions.
    </p>
  <?php
  } else {
    $file_content = file_get_contents($file_name);
    $file_parsed  = json_decode($file_content);
    if (empty($file_parsed) || !is_array($file_parsed)):
  ?>
      <h1>No logs to display for the moment...</h1>
      <h2>The log file is empty.</h2>
      <p>Something went wrong. The file exists, but it is empty or bad-formed.</p>
    <?php else: ?>
      <p>Here will be the place of a nice graph!</p>
      <p>Content of the <em><?= $file_name; ?></em> file:</p>
      <textarea><?= $file_content; ?></textarea>
    <?php endif; ?>
  <?php } ?>
  </div>
</body>
</html>
