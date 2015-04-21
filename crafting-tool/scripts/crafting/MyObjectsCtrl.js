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
        if(!confirm("Estas seguro de que deseas borrar el objeto seleccionado ?"))
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

    $scope.showObjectsForScene($scope.scenes[0].id);
};
});
