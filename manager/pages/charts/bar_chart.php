
    <div class="row">
	<textarea id="json" style="display:none;"><?php echo $variable;?></textarea>

        <!-- /.col (LEFT) -->
        <div class="col-md-12">
            <div class="chart">
                <canvas id="barChart" style="height:230px"></canvas>
            </div>
        </div>
    <!-- /.col (RIGHT) -->
    </div>
    
    <!-- ChartJS -->
    <script src="assets/plugins/chartjs/Chart.js"></script>


    <!-- page script -->
    <script type="text/javascript">
        $(function () {
    	
            var jresultd = new Array();
            <?php foreach($resltdo_xaxis as $key => $val){ ?>
                jresultd.push('<?php echo $val; ?>');
            <?php } ?>

            //--------------
            //- Assigning data for both Line chart and Bar chart
            //--------------

            var lineChartData = {
                labels  : jresultd,
                datasets: <?php echo $graph_lnb_data; ?>
            };
            //-------------
            //- BAR CHART -
            //-------------
      	
            var barChartCanvas                   = $('#barChart').get(0).getContext('2d');
            var barChart                         = new Chart(barChartCanvas);
            var barChartData                     = lineChartData;
            var barChartOptions                  = {
                //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                scaleBeginAtZero        : true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines      : true,
                //String - Colour of the grid lines
                scaleGridLineColor      : 'rgba(0,0,0,.05)',
                //Number - Width of the grid lines
                scaleGridLineWidth      : 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines  : true,
                //Boolean - If there is a stroke on each bar
                barShowStroke           : true,
                //Number - Pixel width of the bar stroke
                barStrokeWidth          : 2,
                //Number - Spacing between each of the X value sets
                barValueSpacing         : 5,
                //Number - Spacing between data sets within X values
                barDatasetSpacing       : 1,
                //String - A legend template
                legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
                //Boolean - whether to make the chart responsive
                responsive              : true,
                maintainAspectRatio     : true
            };

            barChartOptions.datasetFill = false;
            barChart.Bar(barChartData, barChartOptions);    
          
        });
    </script>

