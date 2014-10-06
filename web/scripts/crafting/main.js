/**
 * Plataforma Matematicon Educ.ar
 *
 * Developed by PressEnter.com.ar team
 *
 * @license magnet:?xt=urn:btih:1f739d935676111cfff4b4693e3816e664797050&dn=gpl-3.0.txt GPL-v3-or-Later
 */
'use strict';

require(["require", "angular", "createjs", "matematicon/drawing", "matematicon/render"], function(require, ng, createjs, drawing, render) {

var decoration_table = Array();
decoration_table["rojo"] = {type: 'color', fill: 'red'};
decoration_table["azul"] = {type: 'color', fill: 'blue'};
decoration_table["gris"] = {type: 'color', fill: 'gray'};
decoration_table["negro"] = {type: 'color', fill: 'black'};

// Load resources
//var queue = new createjs.LoadQueue(false);

var craftingApp = ng.module('craftingApp', []);

craftingApp.controller('CraftingToolCtrl', function ($scope) {
    var stage = new createjs.Stage("canvas");
    var draw = $scope.drawing = new drawing.Drawing();
    var renderer = new render.Renderer(stage, 5, decoration_table);
    renderer.render(draw, 10, 10);

    $scope.addShape = function() {
        var circle = new drawing.Circle(Math.random() * 100, Math.random() * 100, 3);
        circle.decoration = new drawing.Decoration("rojo", "");
        draw.addShape(circle);
    };
});

//queue.on("complete", function() {
    require(['domReady!'], function (document) {
        ng.bootstrap(document, ['craftingApp']);
    });
//});

});
