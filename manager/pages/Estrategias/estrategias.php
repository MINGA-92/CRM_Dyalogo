 <style type="text/css">
    .end-element { background-color : #FFCCFF; }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_strategias_title ;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="txtNombreEstrategia"><?php echo $str_name_estrategia;?></label>
                            <input type="text" name="txtNombreEstrategia" id="txtNombreEstrategia" class="form-control" placeholder="<?php echo $str_name_estrategia;?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                         <div class="form-group">
                            <label for="cmbEstrategia"><?php echo $str_plantilla;?></label>
                            <select name="cmbEstrategia" id="cmbEstrategia" class="form-control">
                                <option value="0">SELECCIONE</option>
                                <?php

                                    $tipoStratLsql = "SELECT * FROM ".$BaseDatos_systema.".TIPO_ESTRAT";
                                    $tipoStratResu = $mysqli->query($tipoStratLsql);

                                    while ($tipoStrat = $tipoStratResu->fetch_object()) {
                                        echo "<option value='".$tipoStrat->TIPO_ESTRAT_ConsInte__b."'>".utf8_encode($tipoStrat->TIPO_ESTRAT_Nombre____b)."</option>";
                                    }

                                ?>
                            </select>
                         </div>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="txtNombreEstrategia"><?php echo $str_base_datos;?></label>
                            <input type="text" name="txtBaseDatos" id="txtBaseDatos" class="form-control" placeholder="<?php echo $str_base_datos;?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                         <div class="form-group">
                            <label for="cmbEstrategia"><?php echo $str_color;?></label>
                            <select name="cmbEstrategia" id="cmbEstrategia" class="form-control">
                                <option value="bg-aqua-active"><?php echo $str_azul;?></option>
                                <option value="bg-green-active"><?php echo $str_verde;?></option>
                                <option value="bg-yellow-active"><?php echo $str_amarillo;?></option>
                                <option value="bg-red-active"><?php echo $str_rojo;?></option>
                            </select>
                         </div>   
                    </div>
                </div>
                 <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="txtNombreEstrategia"><?php echo $str_coment_estrategia;?></label>
                            <textarea name="txtComentarios" id="txtComentarios" class="form-control" placeholder="<?php echo $str_base_datos;?>"></textarea>
                        </div>
                    </div>
                </div>

                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#Flujograma">
                                FLUJOGRAMA
                            </a>
                        </h4>
                    </div>
                    <div id="Flujograma" class="panel-collapse collapse in">
                        <div class="box-body">
                            <div class="row" id="sample">
                                <div class="col-md-12" style="width:100%; white-space:nowrap;">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h4><?php echo $str_type_pass;?></h4>
                                            <span style="display: inline-block; vertical-align: top; width:100%;">
                                                <div id="myPaletteDiv" style="height: 750px;"></div>
                                            </span>
                                        </div>
                                        <div class="col-md-10">
                                            <h4><?php echo $str_flujograma; ?></h4>
                                            <span style="display: inline-block; vertical-align: top; width:100%">
                                                <div id="myDiagramDiv" style="border: solid 1px black; height: 750px;"></div>
                                            </span>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="form-group" style="display: none;">
                                        <textarea id="mySavedModel" class="form-control">
{
    "class": "go.GraphLinksModel",
    "linkFromPortIdProperty": "fromPort",
    "linkToPortIdProperty": "toPort",
    "nodeDataArray": [

    ],
    "linkDataArray": [

    ]
}
                                        </textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="oclOperacion" id="oclOperacion" value="add">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#Metas">
                                METAS
                            </a>
                        </h4>
                    </div>
                    <div id="Metas" class="panel-collapse collapse in">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for='txtNombreMeta'><?php echo $str_Meta_nombre;?></label>
                                        <input type="text" name="txtNombreMeta" id="txtNombreMeta" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for='txtNivelMeta'><?php echo $str_Meta_nivel;?></label>
                                        <input type="text" name="txtNivelMeta" id="txtNivelMeta" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for='txtPasoMeta'><?php echo $str_Meta_paso;?></label>
                                        <input type="text" name="txtPasoMeta" id="txtPasoMeta" class="form-control">
                                    </div> 
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for='txtTipoMeta'><?php echo $str_Meta_tipo;?></label>
                                        <input type="text" name="txtTipoMeta" id="txtTipoMeta" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for='txtSubtipoMeta'><?php echo $str_Meta_subTipo;?></label>
                                        <input type="text" name="txtSubtipoMeta" id="txtSubtipoMeta" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for='txtRangoMeta'><?php echo $str_Meta_rango;?></label>
                                        <input type="text" name="txtRangoMeta" id="txtRangoMeta" class="form-control">
                                    </div>
                                </div>
                            </div> 

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-10">
                        &nbsp;
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-block" id="SaveButton" onclick="save()"><?php echo $str_guardar;?></button>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<div class="modal fade-in" id="crearEstrategia" data-backdrop="static"  data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="post" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php  echo $str_new_campain?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="txtNombreEstrategia"><?php echo $str_name_estrategia;?></label>
                        <input type="text" name="txtNombreEstrategia" id="txtNombreEstrategia" class="form-control" placeholder="<?php echo $str_name_estrategia;?>">
                    </div>
                    <div class="form-group">
                        <label for="cmbProyecto"><?php echo $str_proyect_estrategia;?></label>
                        <select name="cmbProyecto" id="cmbProyecto" class="form-control">
                            <option value="0">SELECCIONE</option>
                            <?php

                                $proyectosLsql = "SELECT * FROM ".$BaseDatos_systema.".PROYEC ORDER BY PROYEC_NomProyec_b";
                                $proyectosResu = $mysqli->query($proyectosLsql);
                                while ($proyectos = $proyectosResu->fetch_object()) {
                                    echo "<option value='".$proyectos->PROYEC_ConsInte__b."'>".utf8_encode($proyectos->PROYEC_NomProyec_b)."</option>";
                                }

                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cmbEstrategia"><?php echo $str_tipe_estrategia;?></label>
                        <select name="cmbEstrategia" id="cmbEstrategia" class="form-control">
                            <option value="0">SELECCIONE</option>
                            <?php

                                $tipoStratLsql = "SELECT * FROM ".$BaseDatos_systema.".TIPO_ESTRAT";
                                $tipoStratResu = $mysqli->query($tipoStratLsql);

                                while ($tipoStrat = $tipoStratResu->fetch_object()) {
                                    echo "<option value='".$tipoStrat->TIPO_ESTRAT_ConsInte__b."'>".utf8_encode($tipoStrat->TIPO_ESTRAT_Nombre____b)."</option>";
                                }

                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="txtComentarios"><?php echo $str_coment_estrategia;?></label>
                        <textarea type="text" name="txtComentarios" id="txtComentarios" class="form-control" placeholder="<?php echo $str_coment_estrategia; ?>"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" id="btnCancelar" data-dismiss="modal"  type="button"><?php echo $str_cancela;?></button>
                    <button class="btn btn-success" id="btnGuardar" type="button"><i class="fa fa-save"></i> <?php echo $str_guardar;?></button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="assets/plugins/Flowchart/flowchart.js"></script>

<script type="text/javascript" id="code">
    var colors = {
        blue:   "#00B5CB",
        orange: "#F47321",
        green:  "#C8DA2B",
        gray:   "#888",
        white:  "#F5F5F5"
    }
    function init() {
        if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
        var $ = go.GraphObject.make;  // for conciseness in defining templates
    myDiagram =
        $(go.Diagram, "myDiagramDiv",  // must name or refer to the DIV HTML element
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
    myDiagram.addDiagramListener("Modified", function(e) {
        var button = document.getElementById("SaveButton");
            if (button) button.disabled = !myDiagram.isModified;
                var idx = document.title.indexOf("*");
            if (myDiagram.isModified) {
                if (idx < 0) document.title += "*";
            } else {
                if (idx >= 0) document.title = document.title.substr(0, idx);
            }
    });
    // helper definitions for node templates
    function nodeStyle() {
        return [
            // The Node.location comes from the "loc" property of the node data,
            // converted by the Point.parse static method.
            // If the Node.location is changed, it updates the "loc" property of the node data,
            // converting back using the Point.stringify static method.
            new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
            {
                // the Node.location is at the center of each node
                locationSpot: go.Spot.Center,
                //isShadowed: true,
                //shadowColor: "#888",
                // handle mouse enter/leave events to show/hide the ports
                mouseEnter: function (e, obj) { showPorts(obj.part, true); },
                mouseLeave: function (e, obj) { showPorts(obj.part, false); }
            }
        ];
    }
    // Define a function for creating a "port" that is normally transparent.
    // The "name" is used as the GraphObject.portId, the "spot" is used to control how links connect
    // and where the port is positioned on the node, and the boolean "output" and "input" arguments
    // control whether the user can draw links from or to the port.
    function makePort(name, spot, output, input) {
      // the port is basically just a small circle that has a white stroke when it is made visible
        return $(go.Shape, "Rectangle",
                   {
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
        // define the Node templates for regular nodes
        var lightText = 'whitesmoke';

        myDiagram.nodeTemplateMap.add("",  // the default category
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
                            stroke: lightText,
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        },
                        new go.Binding("text").makeTwoWay()
                    )
                ),
                // four named ports, one on each side:
                makePort("T", go.Spot.Top, false, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("EnPhone",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#BDBDBD",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        },
                        new go.Binding("text").makeTwoWay()
                    )
                ),
                // three named ports, one on each side except the top, all output only:
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("CargueDatos",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#BDBDBD",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        },
                        new go.Binding("text").makeTwoWay()
                    )
                ),
                // three named ports, one on each side except the top, all output only:
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("EnChat",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#BDBDBD",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        },
                        new go.Binding("text").makeTwoWay()
                    )
                ),
                // three named ports, one on each side except the top, all output only:
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("EnMail",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#BDBDBD",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        },
                        new go.Binding("text").makeTwoWay()
                    )
                ),
                // three named ports, one on each side except the top, all output only:
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("Formul",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#BDBDBD",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        },
                        new go.Binding("text").makeTwoWay()
                    )
                ),
                // three named ports, one on each side except the top, all output only:
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("salPhone",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#42A5F5",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        },
                        new go.Binding("text").makeTwoWay()
                    )
                ),
           
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        myDiagram.nodeTemplateMap.add("salMail",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#42A5F5",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        },
                        new go.Binding("text").makeTwoWay()
                    )
                ),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );


        myDiagram.nodeTemplateMap.add("salSms",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#42A5F5",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "19px FontAwesome",
                            stroke: lightText,
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        },
                        new go.Binding("text").makeTwoWay()
                    )
                ),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );


        myDiagram.nodeTemplateMap.add("salCheck",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                    {
                        minSize: new go.Size(40, 40),
                        fill: "#42A5F5",
                        stroke: null
                    }),
                  $(go.TextBlock, {
                            text: '\uf046',
                            stroke: '#FFF',
                            margin: 8,
                            font: '28px FontAwesome',
                            editable: true,
                            isMultiline: false
                        }
                    )
                ),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        // replace the default Link template in the linkTemplateMap
        myDiagram.linkTemplate =
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
                    stroke: "gray",
                    strokeWidth: 2
                }
            ),
            $(go.Shape,  // the arrowhead
                {
                    toArrow: "standard",
                    stroke: null,
                    fill: "gray"
                }
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
        // Make link labels visible if coming out of a "conditional" node.
        // This listener is called by the "LinkDrawn" and "LinkRelinked" DiagramEvents.
        function showLinkLabel(e) {
            var label = e.subject.findObject("LABEL");
            if (label !== null) label.visible = (e.subject.fromNode.data.figure === "Circle");
        }
        // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
        myDiagram.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
        myDiagram.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;
        load();  // load an initial diagram from some JSON text
        // initialize the Palette that is on the left side of the page
        myPalette =
            $(go.Palette, "myPaletteDiv",  // must name or refer to the DIV HTML element
            {
                "animationManager.duration": 800, // slightly longer than default (600ms) animation
                nodeTemplateMap: myDiagram.nodeTemplateMap,  // share the templates used by myDiagram
                model: new go.GraphLinksModel([  // specify the contents of the Palette
                    { category: "EnPhone", text: "\uf061 \uf095", figure : "Circle"},
                    { category: "EnChat", text: "\uf061 \uf0e5", figure : "Circle"  },
                    { category: "EnMail", text: "\uf061 \uf003", figure : "Circle" },
                    { category: "Formul", text: "\uf061 \uf022" , figure : "Circle"},
                    { category: "CargueDatos", text: "\uf061 \uf016", figure : "Circle"},

                    { category: "salPhone" , text: "\uf095 \uf061", figure : "Circle" },
                    { category: "salMail" , text: "\uf003 \uf061", figure : "Circle" },
                    { category: "salSms" , text: "\uf10a \uf061", figure : "Circle" },
                    { category: "salCheck" , text: "4" }
                ])
            });
        // The following code overrides GoJS focus to stop the browser from scrolling
        // the page when either the Diagram or Palette are clicked or dragged onto.
        function customFocus() {
            var x = window.scrollX || window.pageXOffset;
            var y = window.scrollY || window.pageYOffset;
            go.Diagram.prototype.doFocus.call(this);
            window.scrollTo(x, y);
        }
        myDiagram.doFocus = customFocus;
        myPalette.doFocus = customFocus;
    } // end init
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
        document.getElementById("mySavedModel").value = myDiagram.model.toJson();
        myDiagram.isModified = false;
        $("#crearEstrategia").modal();
    }
    function load() {
        myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
    }


    $(document).ready(function(){
        init();
        $("#estrategias").addClass('active');
        $("#btnGuardar").click(function(){
            if($("#txtNombreEstrategia").val().length < 1){
                alertify.error("<?php echo $str_message_estrategia_name;?>");
                $("#txtNombreEstrategia").focus();
            }else if($("#cmbProyecto").val() == 0){
                alertify.error("<?php echo $str_message_proyect_estrategia;?>");
            }else if($("#cmbEstrategia").val() == 0){
                alertify.error("<?php echo $str_message_tipe_estrategia;?>");
            }else{
                $.ajax({
                    url     : "pages/Estrategias/guardarEstrategia.php",
                    type    : "post",
                    data    :   {
                                    txtNombreEstrategia : $("#txtNombreEstrategia").val(),
                                    cmbProyecto         : $("#cmbProyecto").val(),
                                    cmbEstrategia       : $("#cmbEstrategia").val(),
                                    txtComentarios      : $("#txtComentarios").val(),
                                    mySavedModel        : $("#mySavedModel").val(),
                                    oclOperacion        : "ADD"
                                },
                    dataType: "json",
                    success : function(data){
                        if(data.code == '1'){
                            alertify.success("<?php echo $str_Exito;?>");
                            window.location.href = "index.php";
                        }
                    }
                });
            }
        });
    });
</script>
