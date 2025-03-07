<?php 

$calculateFestivos = ($arrDataFiltros_t["dataFiltros"][5]["valor_6"] == "SI") ? true : false;

if($configValid_erlang) : ?>

    <!-- DataTables -->
    <link rel="stylesheet" href="<?=base_url?>assets/plugins/datatables/dataTables.bootstrap.css">

    <div class="row">
        <textarea id="json" style="display:none;"><?php echo $strJsonDatos_t;?></textarea>
        <textarea id="arr_festivos" style="display:none;"><?php echo json_encode($arr_festivos);?></textarea>
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body col-md-12 col-xs-12 table-responsive">

                <label>E = Entrantes, PL =  Promedio de duración al aire, AR = Agentes requeridos, LR = Lineas requeridas, EP = Espera promedio </label>
                </br>
                </br>

                    <table id="grid" class="table table-bordered table-striped text-center" style="white-space: nowrap;">
                        <thead>
                            <tr>
                                <th>Intervalo</th>
                                <th>Lunes</th>
                                <th>Martes</th>
                                <th>Miércoles</th>
                                <th>Jueves</th>
                                <th>Viernes</th>
                                <th>Sábado</th>
                                <th>Domingo</th>
                                <?php if($calculateFestivos): ?>
                                    <th>Festivos</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $days = ["Monday" , "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

                            if ($calculateFestivos)  array_push($days, "Holiday") ;

                            for ($hours= $hora_inicial_erlang; $hours < $hora_final_erlang+1 ; $hours++) { ?>
                            <tr>
                                <td class="text-bold"><?=$hours.":00 - ".$hours.":59"?></td>
                                <?php 
                                $numbers_day = ($calculateFestivos) ? 8 : 7;
                                for ($i=0; $i < $numbers_day ; $i++) { ?>
                                    <td>
                                        <table class="table table-bordered-dark table-condensed">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>E</th>
                                                    <th>PL</th>
                                                    <th>AR</th>
                                                    <th>LR</th>
                                                    <th>EP</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td type="e" day="<?=$days[$i]?>" interval="<?=$hours?>">0</td>
                                                    <td type="pl" day="<?=$days[$i]?>" interval="<?=$hours?>">0</td>
                                                    <td type="ar" day="<?=$days[$i]?>" interval="<?=$hours?>">0</td>
                                                    <td type="lr" day="<?=$days[$i]?>" interval="<?=$hours?>">0</td>
                                                    <td type="ep" day="<?=$days[$i]?>" interval="<?=$hours?>">0</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                <?php } ?>

                            </tr>
                            
                            <?php } ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Intervalo</th>
                                <th>Lunes</th>
                                <th>Martes</th>
                                <th>Miércoles</th>
                                <th>Jueves</th>
                                <th>Viernes</th>
                                <th>Sábado</th>
                                <th>Domingo</th>
                                <?php if($calculateFestivos): ?>
                                    <th>Festivos</th>
                                <?php endif; ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- DataTables -->
    <script src="<?=base_url?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?=base_url?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- page script -->
    <script type="text/javascript">
        

    // ESTA CLASE NOS AYUDA A HACER LOS CALCULOS DE ERLANG
        class Erlang{

            setInCallAvg(inCallAvg){
                this.inCallAvg = inCallAvg;
            }

            setTimeAirAvg(timeAirAvg){
                this.timeAirAvg = timeAirAvg;
            }

            setLevelServiceRequired(LevelServiceRequired){
                this.LevelServiceRequired = LevelServiceRequired;
            }

            setTargetAnswerTime(targetAnswerTime){
                this.targetAnswerTime = targetAnswerTime;
            }


            getNumberAgents(){

                // Aqui calculamos el numero de agentes requeridos

                this.#calcTrafficIntensity();

                if(this.inCallAvg < 10 && this.inCallAvg != 0){
                    this.numberAgents = 1;
                    return 1;
                }
                
                var estimateAgents = parseInt(this.trafficIntensity+1);
                let actualLevelService = 0;
                let valid = false;

                do {

                    this.#calcErlangC(estimateAgents);
                    actualLevelService = this.#calcServiceLevel(estimateAgents)*100;

                    // Validamos si el nivel de servicio es mayor al requerido y si lo es entonces hemos encontrado el numero de agentes
                    if(actualLevelService >= this.LevelServiceRequired){
                        valid = true;
                    }else{
                        estimateAgents++;
                    }

                } while (!valid);

                this.numberAgents = estimateAgents;
                return estimateAgents;

            }


            getASA(){
                // Calculamos el tiempo promedio de espera de un cliente 
                // Formula (Pw*AHT)/(N-A)

                let asa;

                if(this.inCallAvg < 10 && this.inCallAvg != 0 || this.timeAirAvg == 0){
                    asa = 0
                }else{
                    asa = Math.round(this.probabilityCallWait*this.timeAirAvg/(this.numberAgents-this.trafficIntensity));

                }
                return asa;
            }

            getNumberLines(){

                // Aqui calculamos el numero de lineas requeridas

                this.#calcTrafficIntensity();

                if(this.inCallAvg < 10 && this.inCallAvg != 0 || this.timeAirAvg == 0){
                    this.numberLines = 2;
                    return 2;
                }

                let estimateLines = 1;
                let valid = false;

                do {
                    this.#calcErlangB(estimateLines);

                    // Validamos si la probabilidad de perde una llamadas de menos o igual a  1 sobre 100, si lo es hemos obtenido la cantidad de lineas necesarias
                    if(this.probabilityCallDrop <= 0.01){
                        valid = true;
                    }else{
                        estimateLines++;
                    }

                } while (!valid);

                this.numberLines = estimateLines;
                return this.numberLines;

            }

            #calcTrafficIntensity(){
                this.trafficIntensity = ((this.inCallAvg*this.timeAirAvg)/3600);
            }

            #calcErlangC(agentsNumber){
                this.#calcErlangCPartA(agentsNumber);
                this.#calcErlangCPartB(agentsNumber);
                // aqui sumo las tres partes de la formula que seria partA/(partB+partA)
                this.probabilityCallWait = this.erlangCPartA/(this.erlangCPartB+this.erlangCPartA);
            }

            #calcErlangCPartA(agentsNumber){
                
                // aqui calculo la parte superios de la formula de erlang C la cual es (A^N/N!)*(N/(N-A))
                // Esta se usa otra vez por lo cual guardo el resultado

                let fistPart = Math.pow(this.trafficIntensity, agentsNumber)/this.#calcFactorial(agentsNumber);
                let secondPart = (agentsNumber/(agentsNumber-this.trafficIntensity));
                this.erlangCPartA = fistPart*secondPart;

            }

            #calcErlangCPartB(agentsNumber){
                
                // Aqui calculo la sumatoria de A^i/i! hasta que sea igual a N-1

                let sumPartB = 0;

                for (let i = 0; i <= agentsNumber-1  ; i++) {
                    sumPartB += Math.pow(this.trafficIntensity, i)/this.#calcFactorial(i);
                }

                this.erlangCPartB = sumPartB;


            }

            #calcServiceLevel(agentsNumber) {
                
                // Aqui calculo el nivel de servicio, se debe ya saber el resultado de erlang C
                let potencial = (agentsNumber - this.trafficIntensity) * (this.targetAnswerTime/this.timeAirAvg);
                let serviceLevel = 1 - (this.probabilityCallWait * Math.exp(-potencial));

                return serviceLevel;
            }


            #calcErlangB(lines){

                this.probabilityCallDrop = this.#calcErlangBpartA(lines)/this.#calcErlangBpartB(lines);

            }

            #calcErlangBpartA(lines){

                // Se calcula la primera parte de la formula de Erlang B = A^M/M! donde M es el numero de lineas
                let erlangBpartB = (Math.pow(this.trafficIntensity, lines))/(this.#calcFactorial(lines));
                return erlangBpartB;
            }

            #calcErlangBpartB(lines){
                // Se calcula la segunda parte de la formula de Erlang B = la sumatoria de A^i/i! hasta que i se igual al numero de lineas

                let sumPartB = 0;

                for (let i = 0; i <= lines  ; i++) {
                    sumPartB += (Math.pow(this.trafficIntensity, i))/(this.#calcFactorial(i));
                }

                return sumPartB;
            }

            // Esta funcion me ayuda a calcular el factorial de algun numero
            #calcFactorial(num){
                let rval=1;
                for (let i = 2; i <= num; i++)
                    rval = rval * i;
                return rval;
            }

        }

        function getDayName (dateDay) { 
            // Aqui es necesario especificar el la zona horario va a ser la de Bogota
            const options = { timeZone: 'America/Bogota', weekday: 'long' };
            const date = new Date(dateDay + 'T00:00:00');
            return date.toLocaleDateString('en-US', options);
        }


        $("#export-errores").click(function(){
            window.location.href = '<?=base_url?>exportar.php';             
        });


        // SE UBICAN LOS DATOS QUE LLEGAN
        const strJsonDatos_t = JSON.parse($("#json").html());
        const strJsonFestivos_t = JSON.parse($("#arr_festivos").html());
        const erlantObj = new Erlang();
        let numSemanas = <?=$num_semanas?>;
        const calculateHoliday= <?=($calculateFestivos)?"true":"false"?>;
        let promediosData = {};
        let arrJsonFestivos_t = [];
        let arrPromedios = [];
    

        // Necesito sacar el promedio teniendo en cuenta los festivos y el numero de semanas seleccionado

        let days = ["Monday" , "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

        //Si calculamos los festivos entonces tengo que validar que dia del festivo es cada uno
        if(calculateHoliday){
            days.push("Holiday");

            if(strJsonFestivos_t.length > 0){
                arrJsonFestivos_t = strJsonFestivos_t.map(holiday => {
                    return {"fecha": holiday, "dayname": getDayName(holiday)};
                });
            }
        }


        days.forEach(day => {
            for (let hora = <?=$hora_inicial_erlang?>; hora < <?=$hora_final_erlang+1?>; hora++) {
                //filtramos los datos que concuerden con el dia y la hora correspondiente

                let datosFiltados = [];
                if(day == "Holiday"){
                    datosFiltados = $(strJsonDatos_t).filter((i,e) => {
                        return parseInt(e.hora) === hora && arrJsonFestivos_t.find(d => d.fecha == e.fecha);
                    })
                }else{
                    datosFiltados = $(strJsonDatos_t).filter((i,e) => {
                        return (calculateHoliday) 
                        ? getDayName(e.fecha) == day && parseInt(e.hora) === hora && !arrJsonFestivos_t.find(d => d.fecha == e.fecha) 
                        : getDayName(e.fecha) == day && parseInt(e.hora) === hora ;
                    })
                }
                
                
                // Si obtenemos informacion del filtro debemos de sacar los promedios en base a las semanas que se escogieron

                if(datosFiltados.length > 0){
                    let recibidasSum = 0;
                    let contestadasSum = 0;
                    let tiempoConversacionTotalSum = 0;
                    let countFestivos = 0;

                    datosFiltados.each((i,e) => {
                        recibidasSum += parseInt(e.recibidas);
                        contestadasSum += parseInt(e.contestadas);
                        tiempoConversacionTotalSum += parseInt(e.tiempo_conversacion_total);
                    });


                    if(calculateHoliday){
                        // Si se tiene que validar los dias festivos entonces toca revisar que dias son,para descontarlo de las entrantes promedio
                        countFestivos = arrJsonFestivos_t.filter((e) => e.dayname == day).length;
                    }

                    let semanasPromedio = (numSemanas-countFestivos < 1) ? 1 : numSemanas-countFestivos;

                    if(calculateHoliday && day == "Holiday"){
                        semanasPromedio = arrJsonFestivos_t.length;
                    }

                    arrPromedios.push({
                        "dia": day,
                        hora,
                        "entrantes_promedio": Math.trunc(recibidasSum/semanasPromedio),
                        "tiempo_aire_promedio": Math.round(tiempoConversacionTotalSum/contestadasSum)
                    });

                }

            }
        });


        arrPromedios.forEach(data => {
            entrantes = (data.entrantes_promedio != null || data.entrantes_promedio != undefined || data.entrantes_promedio == "") ? data.entrantes_promedio : 0;
            tiempoPromedio = (data.tiempo_aire_promedio != null || data.tiempo_aire_promedio != undefined || data.tiempo_aire_promedio == "") ? data.tiempo_aire_promedio : 0;

            //CALCULAMOS ERLANG EN TODOS LOS CASOS QUE HAYAMOS RECIBIDO DATOS

            if(entrantes != 0){
                erlantObj.setInCallAvg(entrantes);
                erlantObj.setLevelServiceRequired(<?=$meta_tsf_erlang?>);
                erlantObj.setTargetAnswerTime(<?=$tiempo_tsf_erlang?>);
                erlantObj.setTimeAirAvg(tiempoPromedio);

                $('td[type="e"][interval="'+parseInt(data.hora)+'"][day="'+data.dia+'"]').html(entrantes);
                $('td[type="pl"][interval="'+parseInt(data.hora)+'"][day="'+data.dia+'"]').html(tiempoPromedio);
                $('td[type="ar"][interval="'+parseInt(data.hora)+'"][day="'+data.dia+'"]').html(erlantObj.getNumberAgents());
                $('td[type="lr"][interval="'+parseInt(data.hora)+'"][day="'+data.dia+'"]').html(erlantObj.getNumberLines());
                $('td[type="ep"][interval="'+parseInt(data.hora)+'"][day="'+data.dia+'"]').html(erlantObj.getASA());
            }else{
                $('td[type="e"][interval="'+parseInt(data.hora)+'"][day="'+data.dia+'"]').html(0);
                $('td[type="pl"][interval="'+parseInt(data.hora)+'"][day="'+data.dia+'"]').html(0);
                $('td[type="ar"][interval="'+parseInt(data.hora)+'"][day="'+data.dia+'"]').html(0);
                $('td[type="lr"][interval="'+parseInt(data.hora)+'"][day="'+data.dia+'"]').html(0);
                $('td[type="ep"][interval="'+parseInt(data.hora)+'"][day="'+data.dia+'"]').html(0);
            }

        });

    </script>

    <style>
        .thead-dark tr {
            background-color: #3c8dbc;
            color: #ffffff;
        }

        .table-bordered-dark {
            border: 1px solid #3c8dbc;
            border-radius: 3px;
            border-collapse:separate;
        }

        .table-bordered-dark tbody td {
            padding: 2px;
        }
        table.table-bordered tbody td{
            vertical-align: middle;
        }
    </style>

<?php else: ?>
    <p>No hay datos para mostrar!</p>
    <script>
        alertify.error("La condiguracion de las metas deben de ser iguales en las campañas seleccionadas.");
    </script>
<?php endif; ?>


