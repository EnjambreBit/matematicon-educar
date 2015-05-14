'use strict';

define(["createjs"],
function(createjs) {

/**
 * Scene select controller:
 *  Choose drawing positions.
 */
return function ($scope, ScenesList) {
    $scope.selected_scene_index = 0;
    $scope.selected_scene = ScenesList[0];
    $scope.stage = new createjs.Stage("positionObjectCanvas");
    $scope.stage.enableMouseOver();
    $scope.selected_zone = null;
    $scope.step = 'select_scene'; // Screen to show

    $scope.nextScene = function()
    {
        if(++$scope.selected_scene_index >= ScenesList.length)   
            $scope.selected_scene_index = 0;
        $scope.selected_scene = ScenesList[$scope.selected_scene_index];
    }
    
    $scope.prevScene = function()
    {
        if(--$scope.selected_scene_index < 0)
            $scope.selected_scene_index = ScenesList.length - 1;
        $scope.selected_scene = ScenesList[$scope.selected_scene_index];
    }

    $scope.drawGrid = function()
    {
        $scope.stage.removeAllChildren();
        var text = new createjs.Text("Cargando !", "34px arial", "#000");
        text.x = 300;
        text.y = 100;
        $scope.stage.addChild(text);
        var image = new createjs.Bitmap($scope.selected_scene.full_image_src);
        image.image.onload = function() {$scope.stage.removeChild(text); $scope.stage.update();};
        image.scaleX = image.scaleY = 960./1920.;
        image.on("click", function(evt) { $scope.selectZone(Math.floor(evt.stageX / 48) , Math.floor(evt.stageY / 48)); });
        $scope.stage.addChild(image);
        
        for(var i=0;i<20;i++)
            for(var j=0;j<4;j++)
            {
                var shape = new createjs.Shape();
                shape.x = i * 48;
                shape.y = j * 48;

                var graphics = shape.graphics;
                if($scope.selected_zone != null && $scope.selected_zone[0] == i && $scope.selected_zone[1] == j)
                {
                    graphics = graphics.setStrokeStyle(5, 0, "bevel").beginStroke("#09c8d7");
                }
                else
                {
                    graphics = graphics.setStrokeStyle(1, 0, "bevel").beginStroke("#09c8d7");
                }
                graphics.rect(0, 0, 48, 48);
                $scope.stage.addChild(shape);
                
                var shape = new createjs.Shape();
                shape.x = i * 48;
                shape.y = j * 48;

                var graphics = shape.graphics;
                
                if($scope.selected_scene.zones.indexOf(j * 20 + i) >= 0)
                {
                    graphics.beginFill("#1ad9e8").rect(0, 0, 48, 48);
                    shape.alpha = 0.01;
                    shape.on("mouseover", function(evt) {
                        evt.target.alpha=0.5;
                        $scope.stage.update();
                    });
                    shape.on("mouseout", function(evt) {
                        evt.target.alpha=0.01;
                        $scope.stage.update();
                    });
                    shape.on("click", function(evt) { $scope.selectZone(Math.floor(evt.stageX / 48) , Math.floor(evt.stageY / 48)); });

                }
                /*else
                {   // locked zone
                    graphics.beginFill("#555").rect(0, 0, 48, 48);
                    shape.alpha = 0.7;
                }*/

                $scope.stage.addChild(shape);
            }

        $scope.stage.update();
    }
    
    $scope.selectScene = function()
    {
        $scope.step = 'select_zone';
        $scope.selected_zone = null;
        $scope.drawGrid();
    }

    $scope.selectZone = function(x, y)
    {
        if($scope.selected_scene.zones.indexOf(y * 20 + x) >= 0)
        {
            $scope.selected_zone = new Array(x, y);
        }
        $scope.drawGrid();
    }

    $scope.gotoSceneSelect = function()
    {
        $scope.step = 'select_scene';
    }

    $scope.acceptZone = function()
    {
        if($scope.selected_zone != null)
        {
            $scope.setDrawingZone($scope.selected_scene, $scope.selected_zone);
            $scope.step = 'select_scene';
            $scope.exitScreen();
        }
    }
};
});
