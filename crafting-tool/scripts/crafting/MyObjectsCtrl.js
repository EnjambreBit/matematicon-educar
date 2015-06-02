'use strict';

define(["jquery",
        "matematicon/drawing"],
function(jq, drawing) {
/**
 * MyObjects controller
 */
return function ($scope, $timeout, ScenesList, ObjectsPersistor) {
    $scope.scenes = ScenesList;
    $scope.current_page_objects = [];

    $scope.showObjectsForScene = function(scene_id)
    {
        $scope.current_scene_id = scene_id;
        $scope.current_page = 0;
        $scope.fetchPage();
    }

    $scope.$on('screen_my_objects', function(evt)
    {
        $scope.showObjectsForScene($scope.current_scene_id);
    });

    $scope.prev = function()
    {
        if($scope.current_page > 0)
        {
            $scope.current_page--;
        }
        $scope.fetchPage();
    }
    
    $scope.next = function()
    {
        $scope.current_page++;
        $scope.fetchPage();
    }
    
    $scope.fetchPage = function()
    {
        ObjectsPersistor.list($scope.current_scene_id, $scope.current_page, function(resp) {
            $scope.current_page_objects = resp;
            $timeout(function(){});
            //$scope.$apply();
        });
    }

    $scope.loadDrawingById = function(id)
    {
        $scope.setStatus('Cargando objeto');
        ObjectsPersistor.loadDrawing(id, function(draw)
        {
            var d = drawing.unserialize(id, draw);
            $scope.setNewDrawing(d);
            $scope.setStatus('Objeto cargado');
        });
    }

    $scope.deleteDrawingById = function(id)
    {
        if(!confirm("¿Estás seguro de que deseas borrar el objeto seleccionado?"))
        {
            return;
        }

        $scope.setStatus('Borrando objeto');
        ObjectsPersistor.remove(id, function()
        {
            if(id == $scope.drawing.id)
                $scope.drawing.id = null;
            $scope.fetchPage();
            $scope.setStatus('Objeto borrando');
        });
    }

    $scope.exportDrawing = function(id)
    {
        
        if($scope.offline)
        {
            ObjectsPersistor.loadDrawing(id, function(drawing)
            {
                $scope.saveFile("#export_file", JSON.stringify(drawing));
            });
        }
        else
        {
            window.location = ObjectsPersistor.downloadUrl(id);
        }
    }

    $scope.importFromFile = function()
    {
        var msg  = "Los objetos creados con la plataforma Matematicón se guardan en archivos denominados .JSON.\n";
        msg = msg + "Estos archivos solo pueden crearse y abrirse con Matematicón, sirven para las versiones online y offline de la plataforma.\n\n";
        msg = msg + "Si creaste un objeto con Matematicón y lo guardaste en tu computadora, podés recuperarlo en la plataforma seleccionando el archivo con extensión .JSON desde tu computadora.\n\n";
        msg = msg + "Recordá que los cambios al objeto actual que no hayas guardado se perderán, ¿querés continuar? ";

        if(confirm(msg))
        {
            var chooser = document.querySelector("#import_file");
            if(chooser.evtRegistrado == undefined)
            {
                chooser.evtRegistrado = true;
                chooser.addEventListener("change", function(evt) {
                    var f = evt.target.files[0];
                    if(f)
                    {
                        var r = new FileReader();
                        r.onload = function(e) {
                            try {
                                var draw = JSON.parse(e.target.result);
                                if(draw.title != undefined && draw.scene_id != undefined)
                                {
                                    draw.id = "";
                                    var d = drawing.unserialize("", draw);
                                    $scope.setNewDrawing(d);
                                    $scope.setStatus('Objeto importado');
                                    return;
                                }
                            }
                            catch(e)
                            {
                                console.log("Excep!");
                            }
                            alert("Error al importar archivo");
                        };
                        r.readAsText(f);
                    }
                }, false);
            }

            chooser.click();
        }
    };

    $scope.saveFile = function(name,data) {
        var chooser = document.querySelector(name);
        $scope.saveData = data;
        if(chooser.evtRegistrado == undefined)
        {
            chooser.evtRegistrado = true;
            chooser.addEventListener("change", function(evt) {
                var fs = require('fs');// save it now
                fs.writeFile(this.value, $scope.saveData, function(err) {
                    if(err) {
                       alert("error"+err);
                    }
                });
                this.value = null;
            }, false);
        }
        chooser.click();  
    };

    $scope.showObjectsForScene($scope.scenes[0].id);
};
});
