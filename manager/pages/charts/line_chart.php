
    <div class="row">
	<textarea id="json" style="display:none;"><?php echo $variable;?></textarea>
        <div class="col-md-12">
            <div class="chart">
                <canvas id="lineChart" style="height:250px"></canvas>
            </div>
        </div>
    </div>
    
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

            //Create the line chart

            //-------------
            //- LINE CHART -
            //--------------

            var lineChartOptions = {
                //Boolean - If we should show the scale at all
                showScale               : true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines      : false,
                //String - Colour of the grid lines
                scaleGridLineColor      : 'rgba(0,0,0,.05)',
                //Number - Width of the grid lines
                scaleGridLineWidth      : 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines  : true,
                //Boolean - Whether the line is curved between points
                bezierCurve             : true,
                //Number - Tension of the bezier curve between points
                bezierCurveTension      : 0.3,
                //Boolean - Whether to show a dot for each point
                pointDot                : false,
                //Number - Radius of each point dot in pixels
                pointDotRadius          : 4,
                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth     : 1,
                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius : 20,
                //Boolean - Whether to show a stroke for datasets
                datasetStroke           : true,
                //Number - Pixel width of dataset stroke
                datasetStrokeWidth      : 2,
                //Boolean - Whether to fill the dataset with a color
                datasetFill             : true,
                //String - A legend template
                legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
                //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio     : true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive              : true
            };
        
            var lineChartCanvas          = $('#lineChart').get(0).getContext('2d');
            var lineChart                = new Chart(lineChartCanvas);
            lineChartOptions.datasetFill = false;
            lineChart.Line(lineChartData, lineChartOptions);
        });
    </script>
