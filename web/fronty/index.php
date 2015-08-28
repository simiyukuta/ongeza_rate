<?php
//get the damn exchange rates
function getRates()
{
    $filename='http://ongeza.wenyeji.com/api/rates';
    return  file_get_contents($filename);
}
$data= json_decode(getRates());



?>


<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['annotationchart']}]}"></script>
    <script type='text/javascript'>
      google.load('visualization', '1', {'packages':['annotationchart']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        
        data.addColumn('date', 'Date');
        data.addColumn('number', 'Rate');
        data.addColumn('number', 'High');
        data.addColumn('number', 'Low');
        data.addRows([
                <?php
      foreach ($data as $value) {
          ?>
                      [new Date(<?=$value[0];?>, <?=$value[1];?>, <?=$value[2];?>),<?=$value[3]?>,<?=$value[4]?>,<?=$value[5]?>],
                      <?php
          
      }
                ?>
        ]);

        var chart = new google.visualization.AnnotationChart(document.getElementById('chart_div'));

        var options = {
          displayAnnotations: true
        };

        chart.draw(data, options);
      }
    </script>
  </head>

  <body>
    <div id='chart_div' style='width: 900px; height: 500px;'></div>
  </body>
</html>