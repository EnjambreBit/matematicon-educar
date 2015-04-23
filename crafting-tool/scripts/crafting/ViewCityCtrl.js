'use strict';

define(["createjs",
        "jquery",
        "offline"],
function(createjs, jq, offline) {
/**
 * View City controller:
 */
return function ($scope, ScenesList, CityObjectsFetcher) {
    $scope.stage = null;
    $scope.zoom_on = false;
    $scope.bubbleMenu = jq("#view-city-bubble");
    $scope.bubbleMenu.hide();

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

    $scope.$on('screen_view_city', function(evt)
    {
        $scope.setStatus("Generando mundo");
        CityObjectsFetcher.fetchObjectsCityForDrawing($scope.drawing, function(resp)
        {
            $scope.drawCity(resp);
            $scope.setStatus("Mundo generado");
        });
     }); 
        
     $scope.drawCity = function(objects)
     {
        // Redraw
        $scope.bubbleMenu.hide();
        $scope.zoom_on = false;
        $scope.stage = new createjs.Stage("view-city-canvas");
        $scope.stage.enableMouseOver();
        var selected_scene = null;
        for(var i = 0; i < ScenesList.length; i++)
        {
            if(ScenesList[i].id == $scope.drawing.scene_id)
                selected_scene = ScenesList[i];
        }
        var image = new createjs.Bitmap(selected_scene.full_image_src);
        image.image.onload = function() {$scope.stage.update();};
        $scope.stage.addChild(image);

        // Add drawings
        var used_zones = new Array();

        for(var i=0; i<objects.length;i++)
        {
            var tmp = CityObjectsFetcher.getImage(objects[i].id);
            tmp.image.onload = function() {$scope.stage.update();};
            tmp.scaleX = tmp.scaleY = 96. / 350.;
            tmp.alpha=1;
            var zone=null;
            if(used_zones.indexOf(objects[i].zone[0] + objects[i].zone[1]*20) < 0)
            {
                zone = objects[i].zone;
            }
            else
            {
                continue;
            }
            tmp.x = zone[0] * 96;
            tmp.y = zone[1] * 96;
            tmp.drawing_id = objects[i].id;
            tmp.title=objects[i].title;
            tmp.user=objects[i].user == undefined ? 'Usuario': objects[i].user;
            tmp.provincia=objects[i].provincia;
            tmp.age=objects[i].age;
            tmp.age = tmp.age == undefined ? '' : '(' + tmp.age + ')';
            tmp.provincia = tmp.provincia == undefined ? '' : 'De ' + tmp.provincia;
            tmp.on("mouseover", function(evt) {
                evt.target.alpha=0.8;
                $scope.stage.update();
                var html = "<div class='bubble-user'>"+evt.target.user+" " + evt.target.age + "<br />"+evt.target.provincia+"</div><div class='bubble-title'>"+evt.target.title+"</div>";
                if(!offline())
                {
                    html += '<div style="text-align: right; margin-right: 5px"><a href="../denunciar?id='+evt.target.drawing_id+'" target="_blank" title="Denunciar contenido inapropiado"><img src="assets/icons/denunciar.png" /></div>';
                }
                $scope.bubbleMenu.html(html);
                $scope.bubbleMenu.css({top: evt.target.y+$scope.stage.y-80, left: evt.target.x+$scope.stage.x+50, position:'absolute'});
                $scope.bubbleMenu.show();
            });
            tmp.on("mouseout", function(evt) {
                evt.target.alpha=1;
                $scope.stage.update();
                //$scope.bubbleMenu.hide();
            });
            used_zones.push(zone[0] + zone[1] * 20);
            $scope.stage.addChild(tmp);
        }
        
        var mouse_offset = null;
        image.on("mousedown", function(evt) {
			mouse_offset = {x:evt.stageX, y:evt.stageY};
            $scope.bubbleMenu.hide();
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
    };
};


});
