<?php
// get configuration
$conf = parse_ini_file("config.ini");
if (isset($conf['path_to_logfile'])
  && file_exists($conf['path_to_logfile'])
  && is_readable($conf['path_to_logfile'])) {
  $file_name = $conf['path_to_logfile'];
  if (isset($conf['max_items']) && is_numeric($conf['max_items'])) {
    $max_items = $conf['max_items'];
  } else {
    $max_items = 0; // it will display all items
  }
  if (isset($_GET['max_items']) && is_numeric($_GET['max_items'])) {
    $max_items = $_GET['max_items'];
  }
} else {
  $file_name = NULL;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Speedlogs viewer</title>
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
    $file_parsed  = json_decode($file_content, true);
    if (empty($file_parsed) || !is_array($file_parsed)):
  ?>
      <h1>No logs to display for the moment...</h1>
      <h2>The log file is empty.</h2>
      <p>
        Something went wrong.
        The file exists, but it is empty or bad-formed.
      </p>
    <?php else: ?>
      <ul>
      <?php
        if ($max_items > 0) {
          $file_parsed = array_slice($file_parsed, -$max_items);
        }
      ?>

      <?php foreach ($file_parsed as $item): ?>
        <li>
          <ul>
            <li>Ping: <?= $item['ping']; ?> ms</li>
            <li>Download: <?= $item['down']; ?> Mbps</li>
            <li>Upload: <?= $item['up']; ?> Mbps</li>
            <li>Time: <?= $item['time']; ?></li>
          </ul>
        </li>
      <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  <?php } ?>
  </div>
</body>
</html>
