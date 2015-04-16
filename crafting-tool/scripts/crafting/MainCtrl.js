'use strict';

define(["matematicon/drawing", "matematicon/drawingImageExporter"],
function(drawing, drawingImageExporter) {
/**
 * Main application controller:
 *  Manage the flow between screens.
 */
return function($scope, DecorationTable, Offline)
{
    $scope.screens_stack = new Array();

    $scope.screen = 'select_scene';
    $scope.drawing = new drawing.Drawing();
    $scope.offline = Offline;   
    $scope.online = !Offline;   
    
    $scope.createNew = function ()
    {
        if(confirm("Cuidado, si tenes cambios sin guardar, los mismos se perderan. Estás seguro que deseas continuar ?"))
        {
            $scope.drawing = new drawing.Drawing();
            $scope.screens_stack = new Array();
            $scope.setNewDrawing($scope.drawing);
            $scope.gotoScreen('drawing_tool');
            $scope.gotoScreen('select_scene');
        }
    }
    
    $scope.finalizeEditingProperties = function()
    {
        $scope.editing_properties = false;
    }
    
    $scope.propertiesEditScene = function()
    {
        $scope.editing_properties = true;
        $scope.gotoScreen('select_scene');
    }
    
    $scope.setDrawingZone = function(scene, zone)
    {
        if($scope.editing_properties)
        {
            $scope.properties_scene_id = scene.id;
            $scope.properties_zone = zone;
            $scope.$broadcast("properties_zone_changed");
        }
        else
        {
            $scope.drawing.scene_id = scene.id;
            $scope.drawing.zone = zone;
            $scope.$broadcast("drawing_zone_changed");
        }
    }

    $scope.gotoScreen = function(screen)
    {
        $scope.screens_stack.push($scope.screen);
        $scope.screen = screen;
        $scope.$broadcast("screen_" + $scope.screen);
    }
    
    $scope.replaceScreen = function(screen)
    {
        $scope.exitScreen();
        $scope.screens_stack.push($scope.screen);
        $scope.screen = screen;
        $scope.$broadcast("screen_" + $scope.screen);
    }

    $scope.exitScreen = function()
    {
        $scope.screen = $scope.screens_stack.pop();
        $scope.$broadcast("screen_" + $scope.screen);
    }

    $scope.setNewDrawing = function(drawing)
    {
        $scope.editing_properties = false;
        $scope.drawing = drawing;
        $scope.screens_stack = new Array();
        $scope.gotoScreen('drawing_tool');
        $scope.$broadcast('load_drawing');
        $scope.$apply(); // In case of ajax delays, TODO:fix random error
    }

    $scope.setStatus = function(msg)
    {
        $scope.status_text = msg;
        $scope.$apply();//$timeout(function(){});
    }
   
    $scope.exportImage = function()
    {
        var exporter = new drawingImageExporter.ImageExporter(12, DecorationTable);
        exporter.exportTo($scope.drawing, "export_canvas");
        $scope.gotoScreen('export');
    }

    $scope.gotoScreen('drawing_tool');
    $scope.gotoScreen('select_scene');
};
});
