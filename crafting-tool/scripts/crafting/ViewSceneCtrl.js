'use strict';

define(["createjs",
    "matematicon/render"],
function(createjs, render) {

/**
 * View Scene controller:
 */
return function ($scope, ScenesList, DecorationTable) {
    $scope.stage = null;
    $scope.renderer = null;
    $scope.zoom_on = false;

    $scope.toggleZoom = function()
    {
        if($scope.zoom_on)
        {
            $scope.zoom_on = false;
            $scope.stage.scaleX = $scope.stage.scaleY = 1;
        }
        else
        {
            $scope.zoom_on = true;
            $scope.stage.scaleX = $scope.stage.scaleY = 2;
        }
        $scope.stage.x = 0;
        $scope.stage.y = 0;
        $scope.stage.update();
    }

    /**
     * Draw the scene with only the current object in it.
     *
     * Current object = $scope.drawing.
     */
    $scope.$on('screen_view_scene', function(evt)
    {   // Redraw
        $scope.zoom_on = false;
        $scope.stage = new createjs.Stage("view-scene-canvas");
        var selected_scene = null;
        for(var i = 0; i < ScenesList.length; i++)
        {
            if(ScenesList[i].id == $scope.drawing.scene_id)
                selected_scene = ScenesList[i];
        }
        var image = new createjs.Bitmap(selected_scene.full_image.src);
        //image.scaleX = image.scaleY = 1920. / 1860.;
        $scope.stage.addChild(image);
        $scope.renderer = new render.Renderer($scope.stage, 96. / 26., DecorationTable, false);
        var offsetX = $scope.drawing.zone[0] * 26;
        var offsetY = $scope.drawing.zone[1] * 26;
        $scope.renderer.addDrawing($scope.drawing, offsetX, offsetY);
        $scope.renderer.render();
        
        var mouse_offset = null;
        image.on("mousedown", function(evt) {
			mouse_offset = {x:evt.stageX, y:evt.stageY};
        });

        image.on("pressmove", function(evt) {
            $scope.stage.x += evt.stageX - mouse_offset.x;
            $scope.stage.y += evt.stageY - mouse_offset.y;
            var bounds = $scope.stage.getBounds();
            if($scope.stage.x > 0)
                $scope.stage.x = 0;
            if(bounds.width * $scope.stage.scaleX + $scope.stage.x < 960)
                $scope.stage.x = 960 - bounds.width * $scope.stage.scaleX;
            if($scope.stage.y > 0)
                $scope.stage.y = 0;
            if(bounds.height * $scope.stage.scaleY + $scope.stage.y < 384)
                $scope.stage.y = 384 - bounds.height * $scope.stage.scaleY;
            $scope.stage.update();
			mouse_offset = {x:evt.stageX, y:evt.stageY};
        });

        $scope.stage.scaleX = $scope.stage.scaleY = 1;
        $scope.stage.update();
    });
};
});
