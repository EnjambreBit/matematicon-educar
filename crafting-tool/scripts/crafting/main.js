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

// TODO: decoration_table should be parameter
craftingApp.controller('CraftingToolCtrl', function ($scope) {
    var stage = new createjs.Stage("canvas");
    var draw = $scope.drawing = new drawing.Drawing();

    $scope.decoration_table = decoration_table;
    // Create render view
    $scope.update = function(obj, action, shape)
    {
        if(action == "selectedShape")
        {   // Selected shape changed in render view
            $scope.editShape(shape.index);
            $scope.$apply();
        }
    }

    var renderer = new render.Renderer(stage, 5, decoration_table);
    renderer.addDrawing(draw, 0, 0);
    renderer.addObserver($scope);
    
    // Tmp data when creating new shapes, used for template bindings
    $scope.new_shape_data = {};

    // Tmp data when editing a shape, used for template bindings
    $scope.edit_shape_data = {};

    /**
     * Show new shape creation dialog for the specified shape
     *
     * @param shape Shape type, ex: "circle"
     */
    $scope.showCreateShape = function(shape_type)
    {
        $scope.hideEditShape();
        $scope.new_shape_data = { type: shape_type}; // Just tell the template what shape dialog must shown
    }

    $scope.hideCreateShape = function()
    {
        $scope.new_shape_data = {};
    }

    $scope.hideEditShape = function()
    {
        $scope.edit_shape_data = {};
    }

    $scope.randomDecorationId = function()
    {
        var keys = Object.keys($scope.decoration_table);
        return keys[Math.floor(Math.random() * keys.length)];
    }

    // Functions to save changes to the currently edited shape
    var _shapeSavers = {
        visitSquare: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.side = $scope.edit_shape_data.side;
        },
        visitCircle: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.radius = $scope.edit_shape_data.radius;
        },
        visitRectangle: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.width = $scope.edit_shape_data.width;
            shape.height = $scope.edit_shape_data.height;
        },
        visitTrapezoid: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.base1 = $scope.edit_shape_data.base1;
            shape.base2 = $scope.edit_shape_data.base2;
            shape.height = $scope.edit_shape_data.height;
        }

    };

    // Functions to setup shape editing based on shape type
    var _editShapeSetup = {
        visitSquare : function(shape) {
            $scope.edit_shape_data.side = shape.side;
        },
        visitCircle : function(shape) {
            $scope.edit_shape_data.radius = shape.radius;
        },
        visitRectangle : function(shape) {
            $scope.edit_shape_data.width = shape.width;
            $scope.edit_shape_data.height = shape.height;
        },
        visitTrapezoid : function(shape) {
            $scope.edit_shape_data.base1 = shape.base1;
            $scope.edit_shape_data.base2 = shape.base2;
            $scope.edit_shape_data.height = shape.height;
        }
    };

    /**
     * Start editing the shape with index `index`.
     *
     * @param index The shape index in the drawing
     */
    $scope.editShape = function(index)
    {
        var shape = $scope.drawing.getShapeByIndex(index);
        $scope.edit_shape_data = {shape: shape, index: index};
        $scope.hideCreateShape();
        shape.visit(_editShapeSetup); // Setup editing data based on shape type
    }

    /**
     * Save changes to the shape beign currently edited.
     */
    $scope.saveShapeChanges = function()
    {
        $scope.edit_shape_data.shape.visit(_shapeSavers); // Do actions that depend on shape type
        draw.updateShape($scope.edit_shape_data.shape);
        $scope.edit_shape_data = {};
    }

    /**
     * Create a new square and add it to the drawing
     *
     * @param side Square side
     */
    $scope.addSquare = function(side) {
        var square = new drawing.Square(Math.random() * 100, Math.random() * 100, side);
        square.decoration_id = $scope.randomDecorationId();
        draw.addShape(square);
        $scope.hideCreateShape();
    };

    /**
     * Create a new circle and add it to the drawing
     *
     * @param radius Circle radius
     */
    $scope.addCircle = function(radius) {
        var circle = new drawing.Circle(Math.random() * 100, Math.random() * 100, radius);
        circle.decoration_id = $scope.randomDecorationId();
        draw.addShape(circle);
        $scope.hideCreateShape();
    };

    /**
     * Create a new rectangle and add it to the drawing
     *
     * @param width
     * @param height
     */
    $scope.addRectangle = function(width, height) {
        var rectangle = new drawing.Rectangle(Math.random() * 100, Math.random() * 100, width, height);
        rectangle.decoration_id = $scope.randomDecorationId();
        draw.addShape(rectangle);
        $scope.hideCreateShape();
    };

    /**
     * Create a new trapezoid and add it to the drawing
     *
     * @param base1
     * @param base2
     * @param height
     */
    $scope.addTrapezoid = function(base1, base2, height) {
        var trapezoid = new drawing.Trapezoid(Math.random() * 100, Math.random() * 100, base1, base2, height);
        trapezoid.decoration_id = $scope.randomDecorationId();
        draw.addShape(trapezoid);
        $scope.hideCreateShape();
    };

    $scope.shapes = ["square", "rectangle", "circle", "trapezoid"];
});

// Create decoration table with associated assets
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
