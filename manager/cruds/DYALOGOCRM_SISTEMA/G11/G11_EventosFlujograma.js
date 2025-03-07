
// Configuracion para inicializar el flujograma
function initIvr(){
    var $ = go.GraphObject.make;

    myDiagramIVR = $(go.Diagram, "SeccionGrafico",  // must name or refer to the DIV HTML element
        {
            initialContentAlignment: go.Spot.Center,
            allowDrop: true,  // must be true to accept drops from the Palette
            "LinkDrawn": showLinkLabel,  // this DiagramEvent listener is defined below
            "LinkRelinked": showLinkLabel,
            "animationManager.duration": 800, // slightly longer than default (600ms) animation
            "undoManager.isEnabled": true  // enable undo & redo
        }
    );

    // when the document is modified, add a "*" to the title and enable the "Save" button
    myDiagramIVR.addDiagramListener("Modified", function(e) {
        var button = document.getElementById("SaveButton");
        if (button) button.disabled = !myDiagram.isModified;
        var idx = document.title.indexOf("*");

        if (myDiagramIVR.isModified) {
            if (idx < 0) document.title += "*";
        } else {
            if (idx >= 0) document.title = document.title.substr(0, idx);
        }

        //console.log(e);
        if (e.change === go.ChangedEvent.Remove) {
            alert(evt.propertyName + " removed a node with key: " + e.oldValue.key);
        }
    });

    myDiagramIVR.addModelChangedListener(function(evt) {
        // ignore unimportant Transaction events
        if (!evt.isTransactionFinished) return;
        var txn = evt.object;  // a Transaction
        if (txn === null) return;

        // iterate over all of the actual ChangedEvents of the Transaction
        txn.changes.each(function(e) {
            // ignore any kind of change other than adding/removing a node
            if (e.modelChange !== "nodeDataArray") return;
            // record node insertions and removals
            if (e.change === go.ChangedEvent.Insert) { 
                // alert(evt.propertyName + " added node with key: " + e.newValue.key);
                crearSeccion();
            } else if (e.change === go.ChangedEvent.Remove) {
                if(e.oldValue.tipoSeccion == '3' || e.oldValue.tipoSeccion == '4'){
                    alertify.warning("No se puede eliminar la seccion "+ e.oldValue.nombrePaso + " ya que es una seccion por defecto");
                    load();
                    return;
                }
                eliminarSeccion(e.oldValue.key);
            }
        });
    });

    myDiagramIVR.addModelChangedListener(function(evt) {
        // ignore unimportant Transaction events
        if (!evt.isTransactionFinished) return;
        var txn = evt.object;  // a Transaction
        if (txn === null) return;

        // iterate over all of the actual ChangedEvents of the Transaction
        txn.changes.each(function(e) {
            // record node insertions and removals
            if (e.change === go.ChangedEvent.Property) {
                if (e.modelChange === "linkFromKey") {
                    alert(evt.propertyName + " changed From key of link: " + e.object + " from: " + e.oldValue + " to: " + e.newValue);
                    cambiarConexionToFlecha(e.oldValue, e.object.to, e.newValue, 'from', e.object.fromPort, e.object.toPort);
                } else if (e.modelChange === "linkToKey") {
                    // alert(evt.propertyName + " changed To key of link: " + e.object + " from: " + e.oldValue + " to: " + e.newValue);
                    // console.log(e);
                    cambiarConexionToFlecha(e.object.from, e.oldValue, e.newValue, 'to', e.object.fromPort, e.object.toPort);
                }
            } else if (e.change === go.ChangedEvent.Insert && e.modelChange === "linkDataArray") {
                // alert(e.newValue);
                // alert(e.newValue.from + " added link: " + e.newValue.to);
                // Creo una nueva flecha
                guardarFlujoBot('creacionFlecha');                
            
            } else if (e.change === go.ChangedEvent.Remove && e.modelChange === "linkDataArray") {
                // alert(evt.propertyName + " removed link: " + e.oldValue.from+ " removed link: " + e.oldValue.to);
                if(e.oldValue.generadoPorSistema != 1){
                    removerFlecha(e.oldValue.from, e.oldValue.to);
                }else{

                    let nodeDataArray = e.model.nodeDataArray;
                    let pasoDesde = nodeDataArray.find(element => element.key == e.oldValue.from);

                    alertify.warning("Solo se puede eliminar la flecha desde la seccion "+ pasoDesde.nombrePaso);
                    load();
                }
            }
        });
    });

    myDiagramIVR.addDiagramListener("ObjectDoubleClicked", function (e) {
        // console.log(e.subject.part.data);
        // console.log(e.subject.part.actualBounds.toString());
        if (e.subject.part instanceof go.Link) {
            // alert("doubleClicked");
            let link = e.subject.part;
            guardarFlujoBot('abrirFlecha');
            // mostrarContenidoFlecha(link.data.from , link.data.to, 'show');
            validarConexionesFlecha(link.data.from , link.data.to);            
        }
    });

    function showLinkLabel(e) {
        var label = e.subject.findObject("LABEL");
        if (label !== null) label.visible = (e.subject.fromNode.data.figure === "Circle");
    }

    // helper definitions for node templates
    function nodeStyle() {
        return [
            // The Node.location comes from the "loc" property of the node data,
            // converted by the Point.parse static method.
            // If the Node.location is changed, it updates the "loc" property of the node data,
            // converting back using the Point.stringify static method.
            new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
            {
                selectionObjectName: "BODY",
                // the Node.location is at the center of each node
                locationSpot: go.Spot.Center,
                locationObjectName: "BODY",
                //isShadowed: true,
                //shadowColor: "#888",
                // handle mouse enter/leave events to show/hide the ports
                mouseEnter: function (e, obj) { showPorts(obj.part, true); },
                mouseLeave: function (e, obj) { showPorts(obj.part, false); },
                doubleClick:function(e, obj){
                    if(obj.je){
                        console.log('28', obj.je.tipoPaso, obj.je.key, obj.je.category );
                        DesplegarModal(obj.je.tipoPaso, obj.je.key, obj.je.category);
                        /*if(obj.je.tipoSeccion == "25"){
                            abrirPasoExterno('bot', obj.je.nombrePaso, obj.je.key);
                        }else if(obj.je.tipoSeccion == "24"){
                            abrirPasoExterno('campana', obj.je.nombrePaso, obj.je.key);
                        }else{
                            editarSeccion(obj.je.key, obj.je.autoresId);
                        }*/
                    }else{
                        DesplegarModal(obj.mb.tipoPaso, obj.mb.key, obj.mb.category);
                        //editarSeccion(obj.mb.key, obj.mb.autoresId);
                    }
                }
            }
        ];
    }

    // Define a function for creating a "port" that is normally transparent.
    // The "name" is used as the GraphObject.portId, the "spot" is used to control how links connect
    // and where the port is positioned on the node, and the boolean "output" and "input" arguments
    // control whether the user can draw links from or to the port.
    function makePort(name, spot, output, input) {
        // the port is basically just a small circle that has a white stroke when it is made visible
        return $(go.Shape, "Rectangle", {
            fill: "transparent",
            stroke: null,  // this is changed to "white" in the showPorts function
            desiredSize: new go.Size(8, 8),
            alignment: spot,
            alignmentFocus: spot,  // align the port on the main Shape
            portId: name,  // declare this object to be a "port"
            fromSpot: spot,
            toSpot: spot,  // declare where links may connect at this port
            fromLinkable: output,
            toLinkable: input,  // declare whether the user may draw links to/from here
            cursor: "pointer" // show a different cursor to indicate potential link point
        });
    }

    // Esta configuracion muestra el nombre del paso
    function nombrePaso(nombre){
        // Este bloque muestra el nombre del paso
        return $(go.TextBlock,
            {
                text: nombre,
                alignment: new go.Spot(0.5, 0.9, 0, 15), alignmentFocus: go.Spot.Center,
                stroke: "black", font: "9pt Segoe UI, sans-serif",
                overflow: go.TextBlock.OverflowEllipsis,
                maxLines: 1,
                
            },
            new go.Binding("text", "nombrePaso")
        );
    }

    function generadoPorSistema(){
        // return new go.Binding("stroke", "active", function(v) { return null });
        return new go.Binding("stroke", "generadoPorSistema", function(v) { return v == '1' ? '#000' : '#009fe3'; });
    }

    myDiagramIVR.nodeTemplateMap.add("",  // the default category
        $(go.Node, "Spot", nodeStyle(),
            // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
            $(go.Panel, "Auto",
                $(go.Shape, "Circle",
                    {
                        fill: "#C8DA2B",
                        stroke: null
                    },
                    new go.Binding("figure", "figure")
                ),
                $(go.TextBlock,
                    {
                        font: "18px FontAwesome",
                        stroke: "whitesmoke",
                        margin: 8,
                        maxSize: new go.Size(170, NaN),
                        wrap: go.TextBlock.WrapFit,
                        editable: false
                    },
                    new go.Binding("text").makeTwoWay()
                )
            ),
            // four named ports, one on each side:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, false)
        )
    );

    myDiagramIVR.nodeTemplateMap.add("bienvenida",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Terminator",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.green, 
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosBot.bienvenida,
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesBot.bienvenida),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );

    myDiagramIVR.nodeTemplateMap.add("despedida",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Terminator",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.red, 
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosBot.despedida,
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesBot.despedida),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );
    
    /*myDiagramIVR.nodeTemplateMap.add("InicioIVR",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Terminator",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.green, 
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosBot.bienvenida,
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesIVR.InicioIVR),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );*/
        
    myDiagramIVR.nodeTemplateMap.add("TomaDigitos",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Terminator",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.disabled, 
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosIVR.Digitos,
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesIVR.TomaDigitos),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );
    
    myDiagramIVR.nodeTemplateMap.add("NumeroExterno",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Circle",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.active, 
                        strokeWidth: 4,
                        stroke: null
                    },
                    new go.Binding("figure", "figure"),
                    generadoPorSistema()
                ),
                $(go.TextBlock,
                    {
                        margin: 8,
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosIVR.NumeroExterno,
                        wrap: go.TextBlock.WrapFit,
                        editable: false
                    }
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesIVR.NumeroExterno),
            // three named ports, one on each side except the bottom, all input only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );

    myDiagramIVR.nodeTemplateMap.add("PasarCampana",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Circle",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.yellow,
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosIVR.Campana,
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesIVR.Campana),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );

    myDiagramIVR.nodeTemplateMap.add("ReproducirGrabacion",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Circle",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.red, 
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosIVR.Grabacion,
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesIVR.Grabacion),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );

    myDiagramIVR.nodeTemplateMap.add("OtroIVR",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Circle",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.blue,
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosIVR.OtroIVR,
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesIVR.OtroIVR),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );

    myDiagramIVR.nodeTemplateMap.add("PasarEncuesta",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Circle",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.orange, 
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosIVR.Encuesta,
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesIVR.Encuesta),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );

    myDiagramIVR.nodeTemplateMap.add("Avanzado",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Circle",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.purple, 
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosIVR.Avanzado,
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesIVR.Avanzado),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );
    
    
    // Replace the default Link template in the linkTemplateMap
    myDiagramIVR.linkTemplate =
        $(go.Link,  // the whole link panel
        {
            routing: go.Link.AvoidsNodes,
            curve: go.Link.JumpOver,
            corner: 5,
            toShortLength: 4,
            relinkableFrom: true,
            relinkableTo: true,
            reshapable: true,
            resegmentable: true,
            // mouse-overs subtly highlight links:
            mouseEnter: function(e, link) {
                link.findObject("HIGHLIGHT").stroke = "rgba(30,144,255,0.2)";
            },
            mouseLeave: function(e, link) {
                link.findObject("HIGHLIGHT").stroke = "transparent";
            }
        },
        new go.Binding("points").makeTwoWay(),
        $(go.Shape,  // the highlight shape, normally transparent
            {
                isPanelMain: true,
                strokeWidth: 8,
                stroke: "transparent",
                name: "HIGHLIGHT"
            }
        ),
        $(go.Shape,  // the link path shape
            {
                isPanelMain: true,
                stroke: "#009fe3",
                strokeWidth: 2
            },
            generadoPorSistema()
        ),
        $(go.Shape,  // the arrowhead
            {
                toArrow: "standard",
                stroke: null,
                fill: "gray"
            },
            generadoPorSistema()
        ),
        $(go.Panel, "Auto",  // the link label, normally not visible
            {
                visible: false,
                name: "LABEL",
                segmentIndex: 2,
                segmentFraction: 0.5
            },
            new go.Binding("visible", "visible").makeTwoWay(),
            $(go.Shape, "Rectangle",  // the label shape
            {
                fill: "#F8F8F8",
                stroke: null
            }),
            $(go.TextBlock, "??",  // the label
            {
                textAlign: "center",
                font: "8pt helvetica, arial, sans-serif",
                stroke: "#333333",
                editable: true
            },
            new go.Binding("text").makeTwoWay())
        )
    );

    // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
    myDiagramIVR.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
    myDiagramIVR.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;

    load();  // load an initial diagram from some JSON text

    // initialize the Palette that is on the left side of the page
    myPaletteIVR = $(go.Palette, "ListaSecciones",  // must name or refer to the DIV HTML element
        {
            "animationManager.duration": 800, // slightly longer than default (600ms) animation
            nodeTemplateMap: myDiagramIVR.nodeTemplateMap,  // share the templates used by myDiagram
            model: new go.GraphLinksModel([  // specify the contents of the Palette
                
                { category: "TomaDigitos", text: "Toma Dígitos", tipoPaso : 7, figure : "Circle"},
                { category: "NumeroExterno", text: "Numero Externo", tipoPaso : 1, figure : "Circle"},
                { category: "PasarCampana", text: "Pasar a Una Campaña", tipoPaso : 2, figure : "Circle"},
                { category: "ReproducirGrabacion", text: "Reproducir Grabación", tipoPaso : 3, figure : "Circle"},
                { category: "OtroIVR", text: "Pasar a Otro IVR", tipoPaso : 4, figure : "Circle"},
                { category: "PasarEncuesta", text: "Pasar a Encuesta", tipoPaso : 5, figure : "Circle"},
                { category: "Avanzado", text: "Avanzado", tipoPaso : 6, figure : "Circle"},
                { category: "InicioIVR", text: "Inicio IVR", tipoPaso : 8, figure : "Circle"}

            ])
        }
    );

    // The following code overrides GoJS focus to stop the browser from scrolling
    // the page when either the Diagram or Palette are clicked or dragged onto.
    function customFocus() {
        var x = window.scrollX || window.pageXOffset;
        var y = window.scrollY || window.pageYOffset;
        go.Diagram.prototype.doFocus.call(this);
        window.scrollTo(x, y);
    }

    myDiagramIVR.doFocus = customFocus;
    myPaletteIVR.doFocus = customFocus;

}
// Make all ports on a node visible when the mouse is over the node
function showPorts(node, show) {
    var diagram = node.diagram;
    if (!diagram || diagram.isReadOnly || !diagram.allowLink) return;
    node.ports.each(function(port) {
        port.stroke = (show ? "white" : null);
    });
}

// Show the diagram's model in JSON format that the user may edit
function save() {
    document.getElementById("SavedModelIVR").value = myDiagramIVR.model.toJson();
    myDiagramIVR.isModified = false;
}
function load() {
    myDiagramIVR.model = go.Model.fromJson(document.getElementById("SavedModelIVR").value);
}

// Funciones del paso
function guardarFlujoBot(iniciador){

    save();

    let valido = true;

    quitarCampoError();

    if($("#nombre_bot").val() == ''){
        campoError("nombre_bot");
        valido = false;
    }

    if($("#textoNoRespuesta").val() == ''){
        campoError("textoNoRespuesta");
        valido = false;
    }        

    if($("#timeoutBot").val() == ""){
        campoError("timeoutBot");
        valido = false;
    }

    if(valido){

        tinymce.triggerSave();

        // Formateamos el texto enriquecido
        formatearTextoEnriquecido('#timeoutBotFrase');
        formatearTextoEnriquecido('#textoNoRespuesta');

        //Se crean un array con los datos a enviar, apartir del formulario 
        var formData = new FormData($("#FormularioDatos")[0]);
        
        /*
        $.ajax({
            url: '<?=$url_crud;?>?guardar=si&id_paso=<?=$_GET['id_paso']?>&huesped=<?=$_SESSION['HUESPED']?>&iniciador='+iniciador,  
            type: 'POST',
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                $.unblockUI();
            },
            //una vez finalizado correctamente
            success: function(data){

                if(iniciador == 'botonSave'){
                    alertify.success("Información guardada con &eacute;xito");
                    location.reload();
                }else if(iniciador == 'creacionFlecha'){
                    alertify.success("Flecha creada");
                }
                let paso = <?=$_GET['id_paso']?>;
                recargarFlujograma(paso);
                
            },
            error: function(data){
                if(data.responseText){
                    alertify.error("Hubo un error al guardar la información" + data.responseText);
                }else{
                    alertify.error("Hubo un error al guardar la información");
                }
            }
        })
        */
    }

}


function cargarDatosDelPaso(){
    var Url= CapturarDireccionIVR(Dirreccion);
    let FormPaso= new FormData();
    let IdPaso= $("#IdEstrategia").val();

    //console.log("IdHuesped: ", IdHuesped);
    console.log("IdPaso: ", IdPaso);

    FormPaso.append("IdPaso", IdPaso);
    $.ajax({
        url: Url+"Controller/ConsultasFlujograma.php",
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: FormPaso,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        success: function(data){

            console.log("data: ", data);

            if(data > 0){
                data.forEach( (item, index )=> {
                    
                    $("#textoNoRespuesta").val(item.frase_no_encuentra_respuesta);
                    $("#timeoutBot").val(item.timeout_cliente);
                    $("#timeoutBotFrase").val(item.frase_timeout);

                    if(index != 0){
                        estructuraSeccionesBot += ',';
                    }

                    let categoria = 'conversacional';
                    switch (item.tipo_seccion) {
                        case "1":
                            categoria = 'conversacional';
                            break;
                        case "2":
                            categoria = 'transaccional';
                            break;
                        case "3":
                            categoria = 'bienvenida';
                            break;
                        case "4":
                            categoria = 'despedida';
                            break;
                        default:
                            break;
                    }

                    let newLoc = '';

                    if(item.loc){
                        newLoc = item.loc.replace(/"/g, "");
                    }else{
                        newLoc = item.loc;
                    }
                    estructuraSeccionesBot += `{"category": "${categoria}", "nombrePaso": "${item.nombre}", "active": -1, "figure":"Circle", "key": ${item.id}, "autoresId": ${item.autorespuestaId}, "loc":"${newLoc}", "tipoSeccion": "${item.tipo_seccion}"}`;
                });
            }
            

        },
        complete : function(){
            $.unblockUI();
        }
    });



    /*
    $.ajax({
        url: '<?=$url_crud;?>?getDatosPaso=true&id_paso=<?=$_GET['id_paso']?>&huesped=<?=$_SESSION['HUESPED']?>',  
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        success: function(data){

            // Traigo los datos del paso
            if(data.paso){

                $("#pasoId").val(data.paso.id);

                if(data.paso.nombre != ''){
                    $("#nombre_bot").val(data.paso.nombre);
                }else{
                    $("#nombre_bot").val('BOT_'+data.paso.id);
                }
                
                if(data.paso.activo != '-1'){
                    $('#pasoActivo').prop('checked',false);
                }
            }

            let estructuraSeccionesBot = '';
            let estructuraSeccionesBotFlechas = '';

            // Destruimos la instancia tinymce
            tinymce.remove("textarea#timeoutBotFrase");
            tinymce.remove("textarea#textoNoRespuesta");

            // Aqui traigo la lista de secciones
            if(data.secciones.length > 0){
                data.secciones.forEach( (item, index )=> {
                    
                    $("#textoNoRespuesta").val(item.frase_no_encuentra_respuesta);
                    $("#timeoutBot").val(item.timeout_cliente);
                    $("#timeoutBotFrase").val(item.frase_timeout);

                    if(index != 0){
                        estructuraSeccionesBot += ',';
                    }

                    let categoria = 'conversacional';
                    switch (item.tipo_seccion) {
                        case "1":
                            categoria = 'conversacional';
                            break;
                        case "2":
                            categoria = 'transaccional';
                            break;
                        case "3":
                            categoria = 'bienvenida';
                            break;
                        case "4":
                            categoria = 'despedida';
                            break;
                        default:
                            break;
                    }

                    let newLoc = '';

                    if(item.loc){
                        newLoc = item.loc.replace(/"/g, "");
                    }else{
                        newLoc = item.loc;
                    }
                    estructuraSeccionesBot += `{"category": "${categoria}", "nombrePaso": "${item.nombre}", "active": -1, "figure":"Circle", "key": ${item.id}, "autoresId": ${item.autorespuestaId}, "loc":"${newLoc}", "tipoSeccion": "${item.tipo_seccion}"}`;
                });
            }

            if(data.pasosExternos.length > 0){
                data.pasosExternos.forEach( (item, index )=> {

                    estructuraSeccionesBot += ',';

                    let categoria = 'conversacional';
                    switch (item.tipo_seccion) {
                        case "1":
                            categoria = 'conversacional';
                            break;
                        case "2":
                            categoria = 'transaccional';
                            break;
                        case "3":
                            categoria = 'bienvenida';
                            break;
                        case "4":
                            categoria = 'despedida';
                            break;
                        case "24":
                            categoria = 'campana';
                            break;
                        case "25":
                            categoria = 'bot';
                            break;
                        default:
                            break;
                    }

                    let newLoc = '';

                    if(item.loc){
                        newLoc = item.loc.replace(/"/g, "");
                    }else{
                        newLoc = item.loc;
                    }
                    estructuraSeccionesBot += `{"category": "${categoria}", "nombrePaso": "${item.nombre}", "active": -1, "figure":"Circle", "key": ${item.id}, "autoresId": ${item.id_paso_externo}, "loc":"${newLoc}", "tipoSeccion": "${item.tipo_seccion}"}`;
                });
            }

            if(data.conexiones.length > 0){
                data.conexiones.forEach((item, index) => {
                    
                    if(index != 0){
                        estructuraSeccionesBotFlechas += ',';
                    }

                    let newCoordenada = '';

                    if(item.coordenadas){
                        newCoordenada = item.coordenadas.replace(/"/g, "");
                    }else{
                        newCoordenada = item.coordenadas;
                    }
                    estructuraSeccionesBotFlechas += `{"from": ${item.desde}, "to": ${item.hasta}, "fromPort": "${item.from_port}", "toPort": "${item.to_port}", "visible": true, "points": "${newCoordenada}", "text": "${item.nombre}", "generadoPorSistema": "${item.generado_por_sistema}"}`;

                });
            }

            // Creamos la estructura del bot
            let estructura = `
                {
                    "class": "go.GraphLinksModel",
                    "linkFromPortIdProperty": "fromPort",
                    "linkToPortIdProperty": "toPort",
                    "nodeDataArray": [
                        ${estructuraSeccionesBot}
                    ],
                    "linkDataArray": [
                        ${estructuraSeccionesBotFlechas}
                    ]
                }
            `;

            $("#mySavedModelBot").val(estructura);

            // Creamos una instancia para la frase no encuentra respuesta y la frase de timeout
            tinymce.init({
                selector: 'textarea#timeoutBotFrase, textarea#textoNoRespuesta',
                height : 140,
                encoding: 'UTF-8',
                entity_encoding: 'raw',
                skin: 'small',
                menubar: false,
                plugins: 'emoticons paste code',
                toolbar: 'bold italic strikethrough | emoticons | code',
                language: 'es',
                branding: false,
                paste_as_text: true,
                content_style: '.mce-content-body p { padding: 0; margin: 2px 0;}'
            });

            // Agregamos los pasos externos ejecutables
            if(data.pasosEjecutables && data.pasosEjecutables.length > 0){
                
                let opciones = '';

                data.pasosEjecutables.forEach(element => {
                    opciones += `<option value="${element.id}">${element.nombre}</option>`;
                });

                $("#actionAfterTimeout").html(opciones);

                // Validamos si hay pasos guardamos para agregarlos
                if(data.pasosTimeout && data.pasosTimeout.length > 0){
                    $("#actionAfterTimeout").val(data.pasosTimeout).trigger('change');
                }
            }

            // Agrego las variables para el noRespuesta

            // Traigo la lista de bots de la estrategia
            opcionesBots = '<option value="0">Seleccione</option>';
            if(data.listaBots){
                $.each(data.listaBots, function(i, item){
                    opcionesBots += `<option value="${item.id}">${item.nombre}</option>`;
                });
            }
            $("#bot_NoRes").html(opcionesBots);

            // Traigo la lista de campanas de la estrategia
            opcionesCampana = '<option value="0">Seleccione</option>';
            if(data.listaCampanas){
                $.each(data.listaCampanas, function(i, item){
                    opcionesCampana += `<option value="${item.dy_campan}">${item.nombre}</option>`;
                });
            }
            $("#campan_NoRes").html(opcionesCampana);

            // Traigo la lista de secciones del bot
            opcionesSecciones = '<option value="0">Seleccione</option>';
            if(data.listaSecciones){
                $.each(data.listaSecciones, function(i, item){
                    opcionesSecciones += `<option value="${item.base_autorespuesta_id}">${item.nombre}</option>`;
                });
            }
            $("#bot_seccion_NoRes").html(opcionesSecciones);

            // Valido si hay configuración de la accion del NoRespuesta 
            if(data.configuracionAccionNoRespuesta){

                $("#accion_NoRes").val(data.configuracionAccionNoRespuesta.accion).trigger('change');

                // Cuando la accion es pasar a campaña
                if(data.configuracionAccionNoRespuesta.accion == '1'){
                    $("#campan_NoRes").val(data.configuracionAccionNoRespuesta.id_campana);
                }

                // Cuando la accion es transferir a otra seccion
                if(data.configuracionAccionNoRespuesta.accion == '2'){

                    // Pasar a otro bot
                    if(data.configuracionAccionNoRespuesta.id_bot_transferido && data.configuracionAccionNoRespuesta.id_bot_transferido != 0){
                        $("#accion_NoRes").val("2_2").change();
                        $("#bot_NoRes").val(data.configuracionAccionNoRespuesta.id_bot_transferido).trigger('change');
                        $("#bot_NoRes").attr('data-basetransferencia', data.configuracionAccionNoRespuesta.id_base_transferencia);

                    }else{
                        $("#accion_NoRes").val("2_1").change();
                        $("#bot_seccion_NoRes").val(data.configuracionAccionNoRespuesta.id_base_transferencia).trigger('change');
                    }
                }
            }else{
                $("#accion_NoRes").val(0).trigger('change');
            }

            init();
        },
        complete : function(){
            $.unblockUI();
        }
    });
    */

    // Destruyo todas las instancias del editor de texto enriquecido de accion_inicial cuando abrimos el bot
    $("#grupTextAreaAccionInicial div.tox.tox-tinymce").remove();
    $("#rpta_accion_inicial").show();
}

function crearSeccion(){

    save();

    let formData = new FormData($("#FormularioDatos")[0]);
    
    let pasoId = $("#IdEstrategia").val();
    console.log("pasoId: ", pasoId);
    

    /*
    $.ajax({
        url: '<?=$url_crud;?>?crearNuevaSeccion=si&id_paso='+pasoId+'&huesped=<?=$_SESSION['HUESPED']?>',  
        type: 'POST',
        dataType: 'json',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        //una vez finalizado correctamente
        success: function(data){
            console.log(data);
            if(data.res1 && data.res2){
                recargarFlujograma(pasoId);
                // editarSeccion(data.seccionId, data.baseAutorespuestaId);
                alertify.success("Seccion creada");
            }else{
                alertify.error("Hubo un error al guardar la información de la seccion");
            }
        },
        error: function(data){
            if(data.responseText){
                alertify.error("Hubo un error al guardar la información" + data.responseText);
            }else{
                alertify.error("Hubo un error al guardar la información");
            }
        }
    });
    */
}


function DesplegarModal(seccionId, autorespuestaId){

    let IdPaso = $("#IdEstrategia").val();
    var IdSeccion= seccionId;
    console.log("IdSeccion: ", IdSeccion);
                
    if(IdSeccion == 1){
        var Accion= "Numero Externo";
        $("#TltOpcion_2").text(Accion+"': ");
        $("#divNumeroExterno_2").show();
        $("#divCampana_2").hide();
        $("#divGraba_2").hide();
        $("#divListaIVR_2").hide();
        $("#divEncuesta_2").hide();
        $("#divAvanzadoIVR_2").hide();
    }else if(IdSeccion == 2){
        var Accion= "Pasar a Una Campaña";
        $("#TltOpcion_2").text(Accion+"': ");
        $("#divNumeroExterno_2").hide();
        $("#divCampana_2").show();
        $("#divGraba_2").hide();
        $("#divListaIVR_2").hide();
        $("#divEncuesta_2").hide();
        $("#divAvanzadoIVR_2").hide();
    }else if(IdSeccion == 3){
        var Accion= "Reproducir Grabacion";
        $("#TltOpcion_2").text(Accion+"': ");
        $("#divNumeroExterno_2").hide();
        $("#divCampana_2").hide();
        $("#divGraba_2").show();
        $("#divListaIVR_2").hide();
        $("#divEncuesta_2").hide();
        $("#divAvanzadoIVR_2").hide();
    }else if(IdSeccion == 4){
        var Accion= "Pasar a Otro IVR";
        $("#TltOpcion_2").text(Accion+"': ");
        $("#divNumeroExterno_2").hide();
        $("#divCampana_2").hide();
        $("#divGraba_2").hide();
        $("#divListaIVR_2").show();
        $("#divEncuesta_2").hide();
        $("#divAvanzadoIVR_2").hide();
    }else if(IdSeccion == 5){
        var Accion= "Pasar a Encuesta";
        $("#TltOpcion_2").text(Accion+"': ");
        $("#divNumeroExterno_2").hide();
        $("#divCampana_2").hide();
        $("#divGraba_2").hide();
        $("#divListaIVR_2").hide();
        $("#divEncuesta_2").show();
        $("#divAvanzadoIVR_2").hide();
    }else if(IdSeccion == 6){
        var Accion= "Avanzado";
        $("#TltOpcion_2").text(Accion+"': ");
        $("#divNumeroExterno_2").hide();
        $("#divCampana_2").hide();
        $("#divGraba_2").hide();
        $("#divListaIVR_2").hide();
        $("#divEncuesta_2").hide();
        $("#divAvanzadoIVR_2").show();
    }else if(IdSeccion == 7){
        var Accion= "Toma De Digitos";
        $("#TltOpcion_2").text(Accion+"': ");
        $("#divNumeroExterno_2").hide();
        $("#divCampana_2").hide();
        $("#divGraba_2").hide();
        $("#divListaIVR_2").hide();
        $("#divEncuesta_2").hide();
        $("#divAvanzadoIVR_2").hide();

        $("#SelectOpcionNumero_2").show();

    }else if(IdSeccion == 8){
        var Accion= "Inicio IVR";
        $("#title_cargue").text(Accion+"': ");
        $("#ModalInicioFlujogramaIVR").modal('show');

    }
    $("#ModalSeccionFlujogramaIVR").modal('show');
    
    
    cargarDatosDelPaso();

    /*
    obtenerTagsComunes();

    // Obtener variables
    obtenerVariables(pasoId);

    // Limpio los botones de la accion inicial
    $("#listaBotonesa_inicial tbody").html('');

    // traigo los datos de la seccion
    $.ajax({
        url: '<?=$url_crud;?>?getdatosSeccion=si&id_paso='+pasoId+'&huesped=<?=$_SESSION['HUESPED']?>&seccionId='+seccionId+'&autorespuestaId='+autorespuestaId,  
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        success: function(data){
            if(data){
                
                if(data.seccion){
                    $("#seccionNombre").val(data.seccion.nombre);
                    
                    if(data.seccion.tipo_seccion == 1 || data.seccion.tipo_seccion == 3 || data.seccion.tipo_seccion == 4){
                        $("#tipoSeccion").val(1);
                    }else{
                        $("#tipoSeccion").val(2);
                    }

                    $("#seccionBotId").val(seccionId);
                    $("#autorespuestaId").val(autorespuestaId);

                    cambiarLabelInformativo(data.seccion.tipo_seccion);   

                    if(data.seccion.tipo_seccion == 3){
                        $("#acciones_accion_inicial").show();
                    }else{
                        $("#acciones_accion_inicial").hide();
                    }
                }
                
                inicializarTinymce();
                
                inicializarSelectizeTags('local');
                $("#tag_local")[0].selectize.destroy();
                $("#tag_local").val('');

                // lleno el campo destino de almacenamiento del dato
                if(data.listaBd.length > 0){
                    let listaBd = '';

                    $.each(data.listaBd, function(i, item){
                        listaBd += `<option value="${item.id}">${item.nombre}</option>`;
                    });

                    $("#seccionBd").html(listaBd);

                    $("#seccionBd").val(data.baseAutorespuesta.id_base);
                    $('#seccionBd').select2();
                }

                // Ahora lleno los campos de la bd
                if(data.camposBdActual){
                    let listaCampos = '';
                    $.each(data.camposBdActual, function(i, item){
                        listaCampos += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                    camposBd = data.campos;
                    opcionesCamposBd = listaCampos;

                    $("#llavePrincipal").html('<option value="">Seleccione</option>'+opcionesCamposBd);
                }

                

                // Ahora lleno los campos de la gestion
                if(data.camposGestion){
                    let listaCampos = '';
                    $.each(data.camposGestion, function(i, item){
                        listaCampos += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                    opcionesCamposGestion = listaCampos;
                }

                // Ahora lleno los pasos a ejecutar
                if(data.pasoEjecutables){
                    let listaPasos = '';
                    $.each(data.pasoEjecutables, function(i, item){
                        listaPasos += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                    opcionesPasosEjecutables = listaPasos;
                }

                // Valido si la base de la seccion es la misma que la base de la estrategia
                if(data.baseAutorespuesta.id_base == data.baseAutorespuesta.baseOrigen){
                    $("#llavePrincipal").prop('disabled', true);
                }else{
                    $("#llavePrincipal").prop('disabled', false);
                    $("#llavePrincipal").val(data.baseAutorespuesta.id_base_llave_principal);
                }

                baseOrigen = data.baseAutorespuesta.baseOrigen;

                // Lleno listaG
                if(data.listaG && data.listaG.length > 0){
                    let listaCamposG = '';

                    $.each(data.listaG, function(i, item){
                        listaCamposG += `<option value="${item.id}">${item.nombre}</option>`;
                    });

                    opcionesCamposG = listaCamposG;
                }

                switch (data.seccion.tipo_seccion) {
                    case "1":
                    case "3":
                    case "4":
                        
                        $("#agregarFila").show();

                        // Inicialize local tags
                        inicializarSelectizeTags('local');
                        tinymce.get("rpta_accion_inicial").setContent('');

                        // muestro la accion inicial
                        $("#row_accion_inicial").show();

                        if(data.campos.length > 0){
                            data.campos.forEach( (item, i) => {

                                // Traigo la accion inicial
                                if(item.orden == 1){

                                    let myTag = item.pregunta;

                                    if(data.seccion.tipo_seccion == 3){
                                        myTag = myTag.replace('DY_SALUDO,', '');
                                        myTag = myTag.replace('DY_SALUDO', '');
                                    }

                                    if(data.seccion.tipo_seccion == 4){
                                        myTag = myTag.replace('DY_EXIT,', '');
                                        myTag = myTag.replace('DY_EXIT', '');
                                    }
                                    
                                    $("#tag_local")[0].selectize.destroy();
                                    $("#tag_local").val(myTag);
                                    inicializarSelectizeTags('local');

                                    $("#rpta_accion_inicial").val(item.respuesta); // Lo inicializo por aca
                                    tinymce.get("rpta_accion_inicial").setContent(item.respuesta);

                                    $("#accion_inicial_accion").val(item.accion).change();

                                    if(item.accion == 1){
                                        $("#accion_inicial_seccion").html(opcionesCampana);
                                        $("#accion_inicial_seccion").val(item.id_campana);
                                    }
                                    if(item.accion == 2){
                                        $("#accion_inicial_seccion").html(opcionesSecciones);
                                        $("#accion_inicial_seccion").val(item.id_base_transferencia);
                                    }

                                    // tipo de respuesta
                                    if(item.tipo_media == 'TEXT' || item.tipo_media == '' || item.tipo_media === null){
                                        $("#tipoTexto_accion_inicial").click();
                                        $("#seccionMediaActualaccion_inicial").hide();
                                        $("#mediaActualaccion_inicial").html('');
                                    }else{

                                        if(item.tipo_media == 'IMAGE'){
                                            $("#tipoImagen_accion_inicial").click();
                                        }else if(item.tipo_media == 'VIDEO'){
                                            $("#tipoVideo_accion_inicial").click();
                                        }else if(item.tipo_media == 'AUDIO'){
                                            $("#tipoAudio_accion_inicial").click();
                                        }else if(item.tipo_media == 'DOCUMENT'){
                                            $("#tipoDocumento_accion_inicial").click();
                                        }

                                        $("#mediaActualaccion_inicial").html('<a href="'+item.media+'" target="black">'+item.nombre_media+'</a>');
                                        $("#seccionMediaActualaccion_inicial").show();
                                    }

                                    // Cargo los botones
                                    mostrarBotones(item.body_mensaje_interactivo, 'a_inicial');
                                }else{
                                    // El resto de las acciones
                                    agregarFilaBot('edit');
                                    
                                    $("#tag_"+i)[0].selectize.destroy();
                                    $("#tag_"+i).val(item.pregunta);
                                    inicializarSelectizeTags(i);
    
                                    $("#campo_"+i).val(item.id);
    
                                    let tituloInsertar = obtenerIconoTitulo()+' '+$('#tag_'+i).val();
    
                                    $('#tituloBot_'+i).html(tituloInsertar.substr(0, 150));
                                    
                                    $("#rpta_"+i).val(item.respuesta); // Lo inicializo por aca
                                    tinymce.get("rpta_"+i).setContent(item.respuesta);

                                    // tipo de respuesta
                                    if(item.tipo_media == 'IMAGE'){
                                        $("#tipoImagen_"+i).click();
                                        $("#seccionMediaActual"+i).show();
                                        $("#mediaActual"+i).html('<a href="'+item.media+'" target="black">'+item.nombre_media+'</a>');
                                    }else if(item.tipo_media == 'VIDEO'){
                                        $("#tipoVideo_"+i).click();
                                        $("#seccionMediaActual"+i).show();
                                        $("#mediaActual"+i).html('<a href="'+item.media+'" target="black">'+item.nombre_media+'</a>');
                                    }else if(item.tipo_media == 'AUDIO'){
                                        $("#tipoAudio_"+i).click();
                                        $("#seccionMediaActual"+i).show();
                                        $("#mediaActual"+i).html('<a href="'+item.media+'" target="black">'+item.nombre_media+'</a>');
                                    }else if(item.tipo_media == 'DOCUMENT'){
                                        $("#tipoDocumento_"+i).click();
                                        $("#seccionMediaActual"+i).show();
                                        $("#mediaActual"+i).html('<a href="'+item.media+'" target="black">'+item.nombre_media+'</a>');
                                    }else{
                                        $("#tipoTexto_"+i).click();
                                    }

                                    // Cargo los botones
                                    mostrarBotones(item.body_mensaje_interactivo, i);

    
                                    if(item.accion == 2){

                                        // Si esta condicion se cumple significa que es pasar a otro bot
                                        if(item.id_bot_transferido && item.id_bot_transferido != 0){
                                            $("#accion_"+i).val("2_2").change();
                                            $("#bot_"+i).val(item.id_bot_transferido).trigger('change');
                                            $("#bot_"+i).attr('data-basetransferencia', item.id_base_transferencia);

                                        }else{
                                            $("#accion_"+i).val("2_1").change();
                                            $("#bot_seccion_"+i).val(item.id_base_transferencia).trigger('change');
                                        }

                                    }else{
                                        $("#accion_"+i).val(item.accion).change();
                                    }
                                    
                                    $("#campan_"+i).val(item.id_campana).trigger('change');
                                    $("#pregun_"+i).val(item.id_pregun).trigger('change');

                                    if(item.id_pregun && item.id_pregun_gestion){
                                        $("#guardar_respuesta_"+i).val(3).change();
                                        $("#pregunConver_"+i).val(item.id_pregun).change();
                                    }else if(item.id_pregun){
                                        $("#guardar_respuesta_"+i).val(1).change();
                                        $("#pregunConver_"+i).val(item.id_pregun).change();
                                    }else if(item.id_pregun_gestion){
                                        $("#guardar_respuesta_"+i).val(2).change();
                                        if(item.pregun_gestion_propio == 1){
                                            $("#usarCampoGestionPropio"+i).click();
                                            $("#nombre_variable_"+i).val(item.nombre_variable);
                                        }else{
                                            $("#usarCampoGestionExistente"+i).click();
                                            $("#pregunGestionExistente_"+i).val(item.id_pregun_gestion);
                                        }
                                    }else{
                                        $("#guardar_respuesta_"+i).val(0).change();
                                    }

                                }

                            });
                        }
                        
                        break;

                    case "2":

                        $("#row_accion_final").show();
                        $("#agregarFilaCaptura").show();
                        $("#agregarFilaConsulta").show();
                        $("#agregarFilaConsultaDyalogo").show();
                        
                        // Aqui es donde lleno todas las acciones del bot
                        if(data.campos.length > 0){

                            data.campos.forEach( (item, i) => {

                                let tipoAccion = 'captura';

                                if(item.pregunta == 'DY_WS_CONSUMO'){
                                    tipoAccion = 'consulta';
                                }else if(item.pregunta == 'DY_CONSULTA_BD'){
                                    tipoAccion = 'consultaDy';
                                }

                                if(tipoAccion == 'captura'){
                                    agregarFilaBot("edit", "2");

                                    $("#pregunta_bot_"+i).val(item.pregunta);
                                    if(tinymce.get("pregunta_bot_"+i) !== null){
                                        tinymce.get("pregunta_bot_"+i).setContent(item.pregunta);
                                    }

                                    let textoPregunta = $('#pregunta_bot_'+i).val();
                                    textoPregunta = textoPregunta.replace(/(<([^>]+)>)/ig, '');
                                    let tituloInsertar = obtenerIconoTitulo()+' '+textoPregunta;
                                    $('#tituloBot_'+i).html(tituloInsertar.substr(0, 150));

                                    $("#campo_"+i).val(item.id);

                                    $("#rpta_"+i).val(item.respuesta); // Lo inicializo por aca
                                    if(tinymce.get("rpta_"+i) !== null){
                                        tinymce.get("rpta_"+i).setContent(item.respuesta);
                                    }

                                    $("#accion_"+i).val(item.accion).change();
                                    
                                    if(item.accion == 3){
                                        $("#pregun_"+i).val(item.id_pregun).trigger('change');
                                    }else if(item.accion == 4){
                                        $("#nombre_variable_"+i).val(item.nombre_variable);
                                    }else if(item.accion == 5){
                                        $("#pregun_"+i).val(item.id_pregun).trigger('change');
                                    }else if(item.accion == 6){
                                        if(item.pregun_gestion_propio == 1){
                                            $("#usarCampoGestionPropio"+i).click();
                                            $("#nombre_variable_"+i).val(item.nombre_variable);
                                        }else{
                                            $("#usarCampoGestionExistente"+i).click();
                                            $("#nombre_variable_"+i).val(item.nombre_variable);
                                            $("#pregunGestionExistente_"+i).val(item.id_pregun_gestion);
                                        }
                                    }
                                }

                                if(tipoAccion == 'consulta'){
                                    agregarFilaBot("edit", "3");

                                    let ws = data.wsBotSecciones.find(element => element.id_base_autorespuestas == item.id_base_autorespuestas);

                                    $("#campo_"+i).val(ws.id);
                                    $("#webservice_"+i).val(ws.id_ws_general);
                                    $("#webservice_"+i).data("oldvalue", ws.id_ws_general);

                                    let texto = $("#webservice_"+i+" option:selected").text();
                                    $("#tituloService_"+i).html(obtenerIconoTitulo()+'&nbsp;'+texto);

                                    // Ahora traigo los parametros de cada webservice

                                    let htmlParamsBody = '';

                                    // Armo la estructura 
                                    $.each(data.wsParametrosSeccion[ws.id], function(j, itemP){
                                        htmlParamsBody += `
                                        <tr>
                                            <input type="hidden" name="parametro_${i}_${itemP.id_parametro}" id="parametro_${i}_${itemP.id_parametro}">
                                            <td>${itemP.parametro}</td>
                                            <td>
                                                <select name="tipoParametro_${i}_${itemP.id_parametro}" id="tipoParametro_${i}_${itemP.id_parametro}" class="form-control input-sm" onchange="cambioTipoValor(${itemP.id_parametro}, ${i})">
                                                    <option value="1">Estatico</option>
                                                    <option value="2">Dinamico</option>
                                                    <option value="3">Combinado</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="valorEstatico_${i}_${itemP.id_parametro}" id="valorEstatico_${i}_${itemP.id_parametro}" class="form-control input-sm">

                                                <select name="valorDinamico_${i}_${itemP.id_parametro}" id="valorDinamico_${i}_${itemP.id_parametro}" class="form-control input-sm" style="display:none">
                                                    ${variablesDinamicas}
                                                </select> 
                                            </td>
                                        </tr>
                                        `;
                                    });

                                    // Armo la estructura de los parametros
                                    let htmlParamsEstructura = `
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nombre del parametro</th>
                                                    <th>Tipo de valor</th>
                                                    <th>Valor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${htmlParamsBody}
                                            </tbody>
                                        </table>
                                    `;

                                    $('#webserviceParametros_'+i).html(htmlParamsEstructura);

                                    // Lleno los datos
                                    $.each(data.wsParametrosSeccion[ws.id], function(k, itemK){
                                        $("#parametro_"+i+"_"+itemK.id_parametro).val(itemK.id);
                                        $("#tipoParametro_"+i+"_"+itemK.id_parametro).val(itemK.tipo_valor);

                                        if(itemK.tipo_valor == 1){
                                            $("#valorEstatico_"+i+"_"+itemK.id_parametro).val(itemK.valor);
                                        }else{
                                            $("#valorDinamico_"+i+"_"+itemK.id_parametro).val(itemK.valor);
                                        }

                                        $("#tipoParametro_"+i+"_"+itemK.id_parametro).change();
                                    });
                                }

                                if(tipoAccion == 'consultaDy'){
                                    agregarFilaBot("add", "5");
                                    let bd = item.consulta_dy_tabla.replace('G', '');
                                    $("#bdConsultaDy_"+i).val(bd).change();
                                    $("#bdConsultaDy_"+i).attr('data-variables', item.consulta_dy_campos);
                                    $("#bdConsultaDy_"+i).attr('data-condiciones', item.consulta_dy_condicion);
                                }

                            });

                        }

                        // Acciones Finales
                        if(data.camposAccionesFinales.length > 0){

                            tamano = data.camposAccionesFinales.length;

                            data.camposAccionesFinales.forEach( (item, i) => {

                                let contador = $("#totalAccionesFinales").val();

                                $("#autorespuestaAccionFinal").val(item.id_base_autorespuestas);

                                if(i == tamano - 1){
                                    agregarAccionFinal(true, item.condicion, 'edit');
                                }else{
                                    agregarAccionFinal(false, item.condicion, 'edit');
                                }

                                $("#campo_accion_final_"+contador).val(item.id);

                                $("#rpta_accion_final_"+contador).val(item.respuesta);
                                if(tinymce.get("rpta_accion_final_"+contador) !== null){
                                    tinymce.get("rpta_accion_final_"+contador).setContent(item.respuesta);
                                }

                                if(item.accion == 2){

                                    // Si esta condicion se cumple significa que es pasar a otro bot
                                    if(item.id_bot_transferido && item.id_bot_transferido != 0){
                                        $("#accion_accion_final_"+contador).val("2_2").change();
                                        $("#bot_accion_final_"+contador).val(item.id_bot_transferido).trigger('change');
                                        $("#bot_accion_final_"+contador).attr('data-basetransferencia', item.id_base_transferencia);

                                    }else{
                                        $("#accion_accion_final_"+contador).val("2_1").change();
                                        $("#bot_seccion_accion_final_"+contador).val(item.id_base_transferencia).trigger('change');
                                    }
                                }

                                if(item.accion == 1){
                                    $("#accion_accion_final_"+contador).val(item.accion).change();
                                    $("#campan_accion_final_"+contador).val(item.id_campana).trigger('change');
                                }

                                if(item.accion == 7){
                                    $("#accion_accion_final_"+contador).val(item.accion).change();
                                    $("#pasos_externos_accion_final_"+contador).val(item.id_estpas).trigger('change');
                                }
                            });
                        }
                        
                        if(data.camposAccionesFinales.length > 1){
                            let id = data.camposAccionesFinales.length - 1;
                            let nombreAccionFinal = `<i class="fa fa-comments-o"></i>&nbsp;Acción cuando ninguna de las condiciones de las acciones anteriores se cumplió`;
                            $("#tituloAccionFinal"+id).html(nombreAccionFinal);
                        }

                        break;
                
                    default:
                        break;
                }
            }
        }
    });
    */

}



//Consultar Datos
function CargarDatos() {
    var Url= CapturarDireccionIVR(Dirreccion);
    console.log("Url: ", Url);

    
    $.ajax({
        //url: Servidor+"cruds/DYALOGOCRM_SISTEMA/G26/G26_CRUD.php?getDatosPaso=true&id_paso=2&huesped=1",
        url: Url+"Controller/ConsultasFlujograma.php",
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        success: function(data){

            console.log("data: ", data);

            

        },
        complete : function(){
            $.unblockUI();
        }
    });

    
    init();
};

//Funciones Para Flujograma IVR's 
$(document).ready(function(){

    CargarDatos();
    
    /*
    //Capturar Direccion IVR
    function CapturarDireccionIVR(Dirreccion){
        const Servidor= Dirreccion;
        console.log("Servidor:", Servidor);
        const Url= Servidor+"cruds/DYALOGOCRM_SISTEMA/G11/";
        return Url;
    }
    */

    //Control Scrol Modal SubModal
    $(document).on('hidden.bs.modal', '.modal', function () {
        $('.modal:visible').length && $(document.body).addClass('modal-open');
    });

});


