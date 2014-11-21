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
var scenes_list = null;
var gallery_dict = null;
var queue = new createjs.LoadQueue(true);

var craftingApp = ng.module('craftingApp', []);

craftingApp.factory('DecorationTable', function () {
    return decoration_table;
});

craftingApp.factory('ScenesList', function () {
    return scenes_list;
});

craftingApp.factory('Gallery', function () {
    return gallery_dict;
});

craftingApp.factory('BackgroundFactory', function () {
    return {find : function(scene_id, zone)
                {
                    var asset = queue.getResult(scene_id + "_" + zone[0] + "-" + zone[1]);
                    var img = new createjs.Bitmap(asset.src);
                    img.alpha = 0.7;
                    return img;
                }
            };
});

/**
 * Main application controller:
 *  Manage the flow between screens.
 */
craftingApp.controller('MainCtrl', function($scope)
{
    $scope.screens_stack = new Array();

    $scope.screen = 'select_scene';
    $scope.drawing = new drawing.Drawing();
    
    $scope.setDrawingZone = function(scene, zone)
    {
        $scope.drawing.scene_id = scene.id;
        $scope.drawing.zone = zone;
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
        $scope.drawing = drawing;
        $scope.screens_stack = new Array();
        $scope.gotoScreen('drawing_tool');
        $scope.$broadcast('load_drawing');
        $scope.$apply(); // In case of ajax delays
    }

    $scope.gotoScreen('drawing_tool');
    $scope.gotoScreen('select_scene');
});

/**
 * View Scene controller:
 */
craftingApp.controller('ViewSceneCtrl', function ($scope, ScenesList, DecorationTable) {
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
        image.scaleX = image.scaleY = 2;
        $scope.stage.addChild(image);
        $scope.renderer = new render.Renderer($scope.stage, 96. / 26., DecorationTable);
        console.log($scope.drawing.zone);
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
});
/**
 * Scene select controller:
 *  Choose drawing positions.
 */
craftingApp.controller('SceneSelectCtrl', function ($scope, ScenesList) {
    $scope.selected_scene_index = 0;
    $scope.selected_scene = ScenesList[0];
    $scope.stage = new createjs.Stage("positionObjectCanvas");
    $scope.selected_zone = null;
    $scope.step = 'select_scene'; // Screen to show

    $scope.nextScene = function()
    {
        if($scope.selected_scene_index < ScenesList.length - 1)
        {
            $scope.selected_scene_index++;
            $scope.selected_scene = ScenesList[$scope.selected_scene_index];
        }
    }
    
    $scope.prevScene = function()
    {
        if($scope.selected_scene_index > 0)
        {
            $scope.selected_scene_index--;
            $scope.selected_scene = ScenesList[$scope.selected_scene_index];
        }
    }

    $scope.drawGrid = function()
    {
        var image = new createjs.Bitmap($scope.selected_scene.full_image.src);
        image.on("click", function(evt) { $scope.selectZone(Math.floor(evt.stageX / 48) , Math.floor(evt.stageY / 48)); });
        $scope.stage.removeAllChildren();
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
        $scope.selected_zone = new Array(x, y);
        $scope.drawGrid();
    }

    $scope.gotoSceneSelect = function()
    {
        $scope.step = 'select_scene';
    }

    $scope.acceptZone = function()
    {
        $scope.setDrawingZone($scope.selected_scene, $scope.selected_zone);
        $scope.exitScreen();
    }
});

/**
 * Gallery controller
 */
craftingApp.controller('GalleryCtrl', function ($scope, ScenesList, Gallery) {
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
});

/**
 * MyObjects controller
 */
craftingApp.controller('MyObjectsCtrl', function ($scope, ScenesList) {
    $scope.scenes = ScenesList;
    $scope.current_page_objects = [];

    $scope.showObjectsForScene = function(scene_id)
    {
        $scope.current_scene_id = scene_id;
        $scope.current_page = 0;
        $scope.fetchPage();
    }

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
        jq.ajax({
            url: "../app_dev.php/my_objects/"+id,
            dataType: 'json'
        }).done(function(resp)
        {
            var d = new drawing.unserialize(id, resp);
            $scope.setNewDrawing(d);
        });
    }

    $scope.showObjectsForScene($scope.scenes[0].id);
});
/**
 * Drawing tool controller.
 */
craftingApp.controller('CraftingToolCtrl', function ($scope, DecorationTable, BackgroundFactory, ScenesList) {
    var stage = new createjs.Stage("canvas");
    stage.scaleX=stage.scaleY=312./276.; // hack
    var draw = $scope.drawing;

    $scope.decoration_table = DecorationTable;
    $scope.selectedDecorationId = null; // Currently selected decoration in the decoration selector
    $scope.selectedShape = null;
    $scope.background = false;

    $scope.update = function(obj, action, shape)
    {
        if(action == "selectedShape")
        {   // Selected shape changed in render view
            $scope.selectedShape = shape;
            $scope.selectedDecorationId = shape.decoration_id;
            $scope.editShape(shape.index);
            $scope.$apply();
        }
    }


    // Create render view
    $scope.renderer = new render.Renderer(stage, 12, DecorationTable);
    $scope.renderer.addDrawing(draw, 0, 0);
    $scope.renderer.addObserver($scope); // Observe when selected shape change

    // Tmp data when creating new shapes, used for template bindings
    $scope.new_shape_data = {};

    // Tmp data when editing a shape, used for template bindings
    $scope.edit_shape_data = {};

    $scope.$on('load_drawing', function(evt) {
        // Re init for new drawing
        draw = $scope.drawing;

        $scope.renderer.destroy();
        stage = new createjs.Stage("canvas");
        stage.scaleX=stage.scaleY=312./276.; // hack
        $scope.renderer = new render.Renderer(stage, 12, DecorationTable);
        $scope.renderer.addDrawing(draw, 0, 0);
        $scope.renderer.addObserver($scope); // Observe when selected shape change
        $scope.selectedDecorationId = null; // Currently selected decoration in the decoration selector
        $scope.selectedShape = null;
        $scope.background = false;
        $scope.renderer.render();
        $scope.setTool('select');
        $scope.new_shape_data = {};
        $scope.edit_shape_data = {};
    });
    
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

    $scope.setTool = function(tool_name)
    {
        if(tool_name != "select" && tool_name != "properties" && $scope.selectedShape == null)
            return;
        if(tool_name == "properties")
        {
            $scope.properties_drawing_title = $scope.drawing.title;
            for(var i=0; i < ScenesList.length; i++) // TODO: fix, ugly code
                if(ScenesList[i].id == $scope.drawing.scene_id)
                    $scope.properties_selected_scene = ScenesList[i].title; 
        }
        $scope.tool = tool_name;
        $scope.renderer.setTool(tool_name);
    }

    $scope.setTool("select"); // set default tool
    $scope.saveProperties = function(title)
    {
        $scope.drawing.title = title;
        $scope.setTool("select");
    }

    /**
     * Returns a random decoration id
     */
    $scope.randomDecorationId = function()
    {
        var keys = Object.keys(DecorationTable);
        return keys[Math.floor(Math.random() * keys.length)];
    }

    // Functions to save changes to the currently edited shape
    var _shapeSavers = {
        visitSquare: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.side = $scope.edit_shape_data.side;
        },
        visitRhombus: function()
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
        },
        visitTriangle: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.base = $scope.edit_shape_data.base;
            shape.angle = $scope.edit_shape_data.angle;
            shape.height = $scope.edit_shape_data.height;
        }

    };

    // Functions to setup shape editing based on shape type
    var _editShapeSetup = {
        visitSquare : function(shape) {
            $scope.edit_shape_data.side = shape.side;
        },
        visitRhombus : function(shape) {
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
        },
        visitTriangle : function(shape) {
            $scope.edit_shape_data.base = shape.base;
            $scope.edit_shape_data.angle = shape.angle;
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
        $scope.drawing.updateShape($scope.edit_shape_data.shape);
        $scope.edit_shape_data = {};
    }

    /**
     * Create a new square and add it to the drawing
     *
     * @param side Square side
     */
    $scope.addSquare = function(side) {
        var square = new drawing.Square(13, 13, side);
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
        var circle = new drawing.Circle(13, 13, radius);
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
        var rectangle = new drawing.Rectangle(13, 13, width, height);
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
        var trapezoid = new drawing.Trapezoid(13, 13, base1, base2, height);
        trapezoid.decoration_id = $scope.randomDecorationId();
        draw.addShape(trapezoid);
        $scope.hideCreateShape();
    };
    
    $scope.addTriangle = function(base, height, angle) {
        var triangle = new drawing.Triangle(13, 13, base, height, angle);
        triangle.decoration_id = $scope.randomDecorationId();
        draw.addShape(triangle);
        $scope.hideCreateShape();
    };
    
    $scope.addRhombus = function(side) {
        var r = new drawing.Rhombus(13, 13, side);
        r.decoration_id = $scope.randomDecorationId();
        draw.addShape(r);
        $scope.hideCreateShape();
    };

    /**
     * Sets currently selected decoration in the decoration selector.
     *
     * Called from template.
     */
    $scope.setSelectedDecoration = function(decoration_id)
    {
        console.log(decoration_id);
        $scope.selectedDecorationId = decoration_id;
    }

    $scope.saveDecoration = function()
    {
        $scope.selectedShape.decoration_id = $scope.selectedDecorationId;
        $scope.setTool('select');
        $scope.drawing.updateShape($scope.selectedShape);
    }

    $scope.showHideBackground = function()
    {
        if($scope.background)
        {
            $scope.renderer.hideBackground();
            $scope.background = false;
        }
        else
        {
            var bkg = BackgroundFactory.find($scope.drawing.scene_id, $scope.drawing.zone);
            console.log(bkg);
            $scope.renderer.setBackground(bkg);
            $scope.background = true;
        }
        $scope.renderer.render();
    }

    $scope.saveDrawing = function()
    {
        var json = JSON.stringify($scope.drawing);
        var thumb = $scope.renderer.makeThumb();
        jq.ajax({
            url: "../app_dev.php/my_objects/save",
            type: "POST",
            data: {
                json: json,
                thumb: thumb,
                id: $scope.drawing.id,
                title: $scope.drawing.title,
                scene_id: $scope.drawing.scene_id
            }
        }).done(function(msg)
        {
            $scope.drawing.id = msg;
        });
    }
    // List of valid shapes
    $scope.shapes = ["square", "rectangle", "circle", "trapezoid", "triangle", "rhombus"];
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

function prepareScenesList(table, assets)
{
    jq.each(table, function(item) {
        table[item].background = assets.getResult(table[item].id);
        table[item].full_image = assets.getResult(table[item].id+"_full");
    });
}

// Load assets
queue.on("complete", function() {
    // Load decorations table

    // Bootstrap angular app after loading assets
    decoration_table = queue.getResult("decoration_table");
    scenes_list = queue.getResult("scenes");
    gallery_dict = queue.getResult("gallery");
    prepareDecorationTable(decoration_table, queue);
    prepareScenesList(scenes_list, queue);
    require(['domReady!'], function (document) {
        ng.bootstrap(document, ['craftingApp']);
    });
});

queue.loadManifest({id: "manifest", src:"assets/manifest.json", type:createjs.LoadQueue.MANIFEST});


});
