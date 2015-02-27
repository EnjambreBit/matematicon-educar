'use strict';

define(["jquery",
        "matematicon/drawing"],
function(jq, drawing) {
/**
 * MyObjects controller
 */
return function ($scope, ScenesList) {
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
        jq.ajax({
            url: "../app_dev.php/my_objects/",
            dataType: 'json',
            data: {
                page: $scope.current_page,
                scene_id: $scope.current_scene_id
            }
        }).done(function(resp)
        {
            $scope.current_page_objects = resp;
            $scope.$apply();
        });
    }

    $scope.loadDrawingById = function(id)
    {
        $scope.setStatus('Cargando objeto');
        jq.ajax({
            url: "../app_dev.php/my_objects/"+id,
            dataType: 'json'
        }).done(function(resp)
        {
            var d = new drawing.unserialize(id, resp);
            $scope.setNewDrawing(d);
            $scope.setStatus('Objeto cargado');
        });
    }

    $scope.deleteDrawingById = function(id)
    {
        $scope.setStatus('Borrando objeto');
        jq.ajax({
            url: "../app_dev.php/my_objects/"+id+"/delete"
        }).done(function(resp)
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
