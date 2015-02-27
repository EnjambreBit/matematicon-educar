'use strict';

define([],
function() {
/**
 * Gallery controller
 */
return function ($scope, ScenesList, Gallery) {
    $scope.gallery_scenes = ScenesList;
    
    $scope.showGalleryForScene = function(scene_id)
    {
        $scope.current_scene_id = scene_id;
        $scope.total = Gallery[scene_id].length;
        $scope.current_index = 0;
        $scope.current = Gallery[$scope.current_scene_id][$scope.current_index];
    }


    $scope.prev = function()
    {
        if($scope.current_index > 0)
        {
            $scope.current_index--;
            $scope.current = Gallery[$scope.current_scene_id][$scope.current_index];
        }
    }
    
    $scope.next = function()
    {
        if($scope.current_index < $scope.total - 1)
        {
            $scope.current_index++;
            $scope.current = Gallery[$scope.current_scene_id][$scope.current_index];
        }
    }
    
    $scope.current_scene_index = 0;
    $scope.showGalleryForScene($scope.gallery_scenes[0].id);
};
});
