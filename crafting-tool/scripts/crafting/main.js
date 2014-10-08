/**
 * Plataforma Matematicon Educ.ar
 *
 * Developed by PressEnter.com.ar team
 *
 * @license magnet:?xt=urn:btih:1f739d935676111cfff4b4693e3816e664797050&dn=gpl-3.0.txt GPL-v3-or-Later
 */
'use strict';

require(["require",
    "jquery",
    "angular",
    "createjs",
    "matematicon/drawing",
    "matematicon/render"],
function(require, jq, ng, createjs, drawing, render) {

var decoration_table = null;

var craftingApp = ng.module('craftingApp', []);

craftingApp.controller('CraftingToolCtrl', function ($scope) {
    var stage = new createjs.Stage("canvas");
    var draw = $scope.drawing = new drawing.Drawing();
    var renderer = new render.Renderer(stage, 5, decoration_table);
    renderer.render(draw, 10, 10);

    $scope.new_shape = "";
    $scope.new_side = 5;
    $scope.new_radius = 5;

    $scope.showCreateShape = function(shape)
    {
        $scope.new_shape = shape;
    }
    $scope.addSquare = function(side) {
        var square = new drawing.Square(Math.random() * 100, Math.random() * 100, side);
        square.new_side = side;
        square.decoration = new drawing.Decoration("bricks", "");
        draw.addShape(square);
        $scope.new_shape = "";
    };

    $scope.saveSquareChanges = function(shape)
    {
        shape.side = shape.new_side;
        draw.updateShape(shape);
    }

    $scope.addCircle = function(radius) {
        var circle = new drawing.Circle(Math.random() * 100, Math.random() * 100, radius);
        circle.new_radius = radius;
        circle.decoration = new drawing.Decoration("bricks", "");
        draw.addShape(circle);
        $scope.new_shape = "";
    };

    $scope.saveCircleChanges = function(shape)
    {
        shape.radius = shape.new_radius;
        draw.updateShape(shape);
    }

    $scope.shapes = ["circle", "square"];
});

function prepareDecorationTable(table, assets)
{
    jq.each(table, function(item) {
        if(table[item].type == "pattern")
        {
            table[item].fill = assets.getResult(table[item].fill_id);
        }
    });
}

// Load assets
var queue = new createjs.LoadQueue(true);
queue.on("complete", function() {
    // Load decorations table

    // Bootstrap angular app after loading assets
    decoration_table = queue.getResult("decoration_table");
    prepareDecorationTable(decoration_table, queue);
    require(['domReady!'], function (document) {
        ng.bootstrap(document, ['craftingApp']);
    });
});

queue.loadManifest({id: "manifest", src:"assets/manifest.json", type:createjs.LoadQueue.MANIFEST});


});
