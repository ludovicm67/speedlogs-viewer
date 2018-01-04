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
$display_chart = false;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Speedlogs viewer</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: sans-serif;
      font-size: 16px;
      min-width: 800px;
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
      <?php
        if ($max_items > 0) {
          $file_parsed = array_slice($file_parsed, -$max_items);
        }
        $display_chart = true;
      ?>
      <canvas id="speedlogs-chart"></canvas>
    <?php endif; ?>
  <?php } ?>
  </div>
<?php if ($display_chart): ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
  <script>
    var ctx = document.getElementById('speedlogs-chart').getContext('2d');
    var chart = new Chart(ctx, {
      // The type of chart we want to create
      type: 'line',

      // The data for our dataset
      data: {
        labels: [
          '<?= implode("', '", array_column($file_parsed, 'time')); ?>'
        ],
        datasets: [{
          label: "Ping (ms)",
          fill: false,
          backgroundColor: 'rgb(255, 159, 64)',
          borderColor: 'rgb(255, 159, 64)',
          data: [
            <?= implode(", ", array_column($file_parsed, 'ping')); ?>
          ],
          yAxisID: 'y-ms',
        }, {
          label: "Download (Mbps)",
          fill: false,
          backgroundColor: 'rgb(54, 162, 235)',
          borderColor: 'rgb(54, 162, 235)',
          data: [
            <?= implode(", ", array_column($file_parsed, 'down')); ?>
          ],
          yAxisID: 'y-mbps',
        }, {
          label: "Upload (Mbps)",
          fill: false,
          backgroundColor: 'rgb(75, 192, 192)',
          borderColor: 'rgb(75, 192, 192)',
          data: [
            <?= implode(", ", array_column($file_parsed, 'up')); ?>
          ],
          yAxisID: 'y-mbps',
        }]
      },

      // Configuration options go here
      options: {
        responsive: true,
        title: {
          display: true,
          text: 'Speedlogs'
        }, scales: {
          yAxes: [{
            type: 'linear',
            display: true,
            position: 'left',
            ticks: {
              beginAtZero: true,
            },
            id: 'y-mbps'
          }, {
            type: 'linear',
            display: true,
            position: 'right',
            ticks: {
              min: -1,
            },
            id: 'y-ms',
            gridLines: {
              drawOnChartArea: false
            }
          }]
        }
      }
    });
  </script>
<?php endif; ?>
</body>
</html>
