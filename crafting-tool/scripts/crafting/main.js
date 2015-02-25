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
                    console.log("back", scene_id, zone);
                    var asset = queue.getResult(scene_id + "_" + zone[0] + "-" + zone[1]);
                    //var img = new createjs.Bitmap(asset.src);
                    var img = new createjs.Bitmap("assets/backgrounds/" + scene_id + "/" + zone[0] + "-" + zone[1] + ".png");
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
   
    
    $scope.createNew = function ()
    {
        $scope.drawing = new drawing.Drawing();
        $scope.screens_stack = new Array();
        $scope.setNewDrawing($scope.drawing);
        $scope.gotoScreen('drawing_tool');
        $scope.gotoScreen('select_scene');
    }
    
    $scope.finalizeEditingProperties = function()
    {
        $scope.editing_properties = false;
    }
    
    $scope.propertiesEditScene = function()
    {
        $scope.editing_properties = true;
        $scope.gotoScreen('select_scene');
    }
    
    $scope.setDrawingZone = function(scene, zone)
    {
        if($scope.editing_properties)
        {
            $scope.properties_scene_id = scene.id;
            $scope.properties_zone = zone;
            $scope.$broadcast("properties_zone_changed");
        }
        else
        {
            $scope.drawing.scene_id = scene.id;
            $scope.drawing.zone = zone;
            $scope.$broadcast("drawing_zone_changed");
        }
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
        $scope.editing_properties = false;
        $scope.drawing = drawing;
        $scope.screens_stack = new Array();
        $scope.gotoScreen('drawing_tool');
        $scope.$broadcast('load_drawing');
        $scope.$apply(); // In case of ajax delays, TODO:fix random error
    }

    $scope.setStatus = function(msg)
    {
        $scope.status_text = msg;
        $scope.$apply();
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
        //image.scaleX = image.scaleY = 1920. / 1860.;
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
        var image = new createjs.Bitmap($scope.selected_scene.full_image.src);
        image.scaleX = image.scaleY = 960./1920.;
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
    $scope.contextMenu = jq("#contextMenu");
    $scope.undo_stack = new Array();
    $scope.save_after_properties = false;
    $scope.insert_after_save = false;

    $scope._registerUndoAction = function(undoData)
    {
        $scope.undo_stack.push(undoData);
    }

    $scope.undo = function()
    {
        var action = $scope.undo_stack.pop();
        if(action == undefined)
            return;
        
        $scope.selectedShape = null;
        $scope.contextMenu.hide();
        $scope.setTool("select");
        
        switch(action.type)
        {
            case "shape_data":
                action.shape.restoreState(action.state); 
                $scope.drawing.updateShape(action.shape);
                break;
            case "new_shape":
                $scope.drawing.removeShape(action.shape);
                break;
            case "delete_shape":
                $scope.drawing.restoreShapeInOrder(action.shape, action.order);
                break;
            case "order":
                $scope.drawing.removeShape(action.shape);
                $scope.drawing.restoreShapeInOrder(action.shape, action.order);
        }
    }

    $scope.update = function(obj, action, data)
    {
        switch(action)
        {
            case "selectedShape": // Selected shape changed in render view
                $scope.selectedShape = data;
                $scope.selectedDecorationId = data.decoration_id;
                $scope.editShape(data.index);
                $scope.$apply();
                $scope.contextMenu.hide();
                break;
            case "contextMenu":
                //console.log(jq("#canvas").position();
                $scope.contextMenu.show();
                $scope.contextMenu.css({top: data.stageY, left: data.stageX, position:'absolute'});
                break;
            case "beforeTransform": // add current shape data for later undo
                $scope._registerUndoAction({
                    type: "shape_data",
                    state: data.saveState(),
                    shape: data
                });
                break;
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
        $scope.contextMenu.hide();
        $scope.undo_stack = new Array();
        $scope.save_after_properties = false;
        $scope.insert_after_save = false;
    });
    
    $scope.$on('drawing_zone_changed', function(evt) {
        $scope.showHideBackground();
        $scope.showHideBackground();
        $scope.contextMenu.hide();
    });
    
    $scope.$on('properties_zone_changed', function(evt) {
        $scope.properties_zone_changed = true;
        for(var i=0; i < ScenesList.length; i++)
        {
            if(ScenesList[i].id == $scope.properties_scene_id)
            {
                $scope.properties_selected_scene = ScenesList[i].title;
                $scope.properties_scene = ScenesList[i];
            }
        }
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
        $scope.contextMenu.hide();
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
        $scope.setStatus('');
        $scope.contextMenu.hide();
        if(tool_name != "properties")
        {
            $scope.insert_after_save = false;
            $scope.save_after_properties = false;
        }

        if(tool_name != "select" && tool_name != "properties" && $scope.selectedShape == null)
        {
            $scope.setStatus('Seleccioná una figura primero para poder modificarla');
            return;
        }
        switch(tool_name)
        {
            case 'properties':
                $scope.properties_drawing_title = $scope.drawing.title;
                $scope.properties_zone_changed = false;
                for(var i=0; i < ScenesList.length; i++) // TODO: fix, ugly code
                    if(ScenesList[i].id == $scope.drawing.scene_id)
                        $scope.properties_selected_scene = ScenesList[i].title; 
                break;
            case 'decorate':
                $scope.shapeOriginalDecorationId = $scope.selectedShape.decoration_id;
                $scope.tool = tool_name;
                break;
        }
        $scope.tool = tool_name;
        $scope.renderer.setTool(tool_name);
    }

    $scope.setTool("select"); // set default tool
    $scope.saveProperties = function(title)
    {
        $scope.finalizeEditingProperties();
        $scope.properties_title_error = false;
        if(title == '')
        {
            $scope.properties_title_error = true;
            return;
        }
        $scope.drawing.title = title;

        if($scope.properties_zone_changed)
        {
            console.log("cambio!");
            $scope.setDrawingZone($scope.properties_scene, $scope.properties_zone);
        }

        if($scope.save_after_properties)
        {
            $scope.saveDrawing();
        }
        $scope.save_after_properties = false;
        $scope.setTool("select");
    }

    /**
     * Delete currently selected shape
     */
    $scope.contextDelete = function()
    {
        $scope._registerUndoAction({
            type: "delete_shape",
            shape: $scope.selectedShape,
            order: $scope.drawing.getOrder($scope.selectedShape)
        });

        $scope.drawing.removeShape($scope.selectedShape);
        $scope.selectedShape = null;
        $scope.contextMenu.hide();
        $scope.setTool("select");
    }
    
    $scope.contextClone = function()
    {
        var shape = $scope.selectedShape.clone()
        
        draw.addShape(shape);
        $scope._registerUndoAction({
            type: "new_shape",
            shape: shape
        });

        $scope.selectedShape = null;
        $scope.contextMenu.hide();
        $scope.setTool("select");
    }
    
    $scope.contextSendToBack = function()
    {
        $scope._registerUndoAction({
            type: "order",
            shape: $scope.selectedShape,
            order: $scope.drawing.getOrder($scope.selectedShape)
        });
        
        $scope.drawing.sendToBack($scope.selectedShape);
        $scope.contextMenu.hide();
        $scope.setTool("select");
    }

    $scope.contextBringToFront = function()
    {
        $scope._registerUndoAction({
            type: "order",
            shape: $scope.selectedShape,
            order: $scope.drawing.getOrder($scope.selectedShape)
        });
        
        $scope.drawing.bringToFront($scope.selectedShape);
        $scope.contextMenu.hide();
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

    var _shapeValidators = {
        visitSquare: function()
        {
            if(isNaN($scope.edit_shape_data.side) || $scope.edit_shape_data.side <= 0)
                return false;
            return true;
        },
        visitRhombus: function()
        {
            if(isNaN($scope.edit_shape_data.diag1) || $scope.edit_shape_data.diag1 <= 0
                || isNaN($scope.edit_shape_data.diag2) || $scope.edit_shape_data.diag2 <= 0
                || Number($scope.edit_shape_data.diag2) >= Number($scope.edit_shape_data.diag1))
                return false;
            return true;
        },
        visitCircle: function()
        {
            if(isNaN($scope.edit_shape_data.radius) || $scope.edit_shape_data.radius <= 0)
                return false;
            return true;
        },
        visitPolygon: function()
        {
            if(isNaN($scope.edit_shape_data.sides) || $scope.edit_shape_data.sides < 3
                || isNaN($scope.edit_shape_data.side) || $scope.edit_shape_data.side <= 0)
                return false;
            return true;
        },
        visitEllipse: function()
        {
            if(isNaN($scope.edit_shape_data.width) || $scope.edit_shape_data.width <= 0
                || isNaN($scope.edit_shape_data.height) || $scope.edit_shape_data.height <= 0)
                return false;
            return true;
        },
        visitRectangle: function()
        {
            if(isNaN($scope.edit_shape_data.width) || $scope.edit_shape_data.width <= 0
                || isNaN($scope.edit_shape_data.height) || $scope.edit_shape_data.height <= 0)
                return false;
            return true;
        },
        visitTrapezoid: function()
        {
            return drawing.validTrapezoid($scope.edit_shape_data.base1,
                $scope.edit_shape_data.base2,
                $scope.edit_shape_data.height,
                $scope.edit_shape_data.angle);
        },
        visitTriangle: function()
        {
            if(isNaN($scope.edit_shape_data.base) || $scope.edit_shape_data.base <= 0
                || isNaN($scope.edit_shape_data.height) || $scope.edit_shape_data.height <= 0
                || isNaN($scope.edit_shape_data.angle) || $scope.edit_shape_data.angle <= 0 || $scope.edit_shape_data.angle >= 180)
                return false;
            return true;
        }

    };

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
            shape.diag1 = $scope.edit_shape_data.diag1;
            shape.diag2 = $scope.edit_shape_data.diag2;
        },
        visitCircle: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.radius = $scope.edit_shape_data.radius;
        },
        visitPolygon: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.sides = $scope.edit_shape_data.sides;
            shape.side = $scope.edit_shape_data.side;
        },
        visitEllipse: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.width = $scope.edit_shape_data.width;
            shape.height = $scope.edit_shape_data.height;
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
            shape.base1 = Number($scope.edit_shape_data.base1);
            shape.base2 = Number($scope.edit_shape_data.base2);
            shape.height = Number($scope.edit_shape_data.height);
            shape.angle = Number($scope.edit_shape_data.angle);
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
            $scope.edit_shape_data.diag1 = shape.diag1;
            $scope.edit_shape_data.diag2 = shape.diag2;
        },
        visitCircle : function(shape) {
            $scope.edit_shape_data.radius = shape.radius;
        },
        visitPolygon : function(shape) {
            $scope.edit_shape_data.sides = shape.sides;
            $scope.edit_shape_data.side = shape.side;
        },
        visitEllipse : function(shape) {
            $scope.edit_shape_data.width = shape.width;
            $scope.edit_shape_data.height = shape.height;
        },
        visitRectangle : function(shape) {
            $scope.edit_shape_data.width = shape.width;
            $scope.edit_shape_data.height = shape.height;
        },
        visitTrapezoid : function(shape) {
            $scope.edit_shape_data.base1 = shape.base1;
            $scope.edit_shape_data.base2 = shape.base2;
            $scope.edit_shape_data.height = shape.height;
            $scope.edit_shape_data.angle = shape.angle;
        },
        visitTriangle : function(shape) {
            $scope.edit_shape_data.base = shape.base;
            $scope.edit_shape_data.angle = shape.angle;
            $scope.edit_shape_data.height = shape.height;
        }
    };

    $scope.setSelectedShape = function(shape)
    {
        $scope.renderer.setSelectedShape(shape);
        $scope.renderer.update();
    }
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
        if(!$scope.edit_shape_data.shape.visit(_shapeValidators))
        {
            $scope.edit_shape_data.error = true;
            return;
        }
        $scope.edit_shape_data.error = false;
        $scope._registerUndoAction({
            type: "shape_data",
            shape: $scope.edit_shape_data.shape,
            state: $scope.edit_shape_data.shape.saveState()
        });
        $scope.edit_shape_data.shape.visit(_shapeSavers); // Do actions that depend on shape type
        $scope.drawing.updateShape($scope.edit_shape_data.shape);
        //$scope.edit_shape_data = {};
        $scope.contextMenu.hide();
    }

    /**
     * Create a new square and add it to the drawing
     *
     * @param side Square side
     */
    $scope.addSquare = function(side) {
        if(isNaN(side) || side <= 0)
        {
            $scope.new_shape_data.error = true;
            return;
        }
        var square = new drawing.Square(13, 13, side);
        square.decoration_id = $scope.randomDecorationId();
        draw.addShape(square);
        $scope.hideCreateShape();
        $scope._registerUndoAction({
            type: "new_shape",
            shape: square
        });
    };
    
    /**
     * Create a new regular polygon and add it to the drawing
     *
     * @param sides
     * @param side
     */
    $scope.addPolygon = function(sides, side) {
        if(isNaN(sides) || sides < 3 || isNaN(side) || side <= 0)
        {
            $scope.new_shape_data.error = true;
            return;
        }
        var poly = new drawing.Polygon(13, 13, sides, side);
        poly.decoration_id = $scope.randomDecorationId();
        draw.addShape(poly);
        $scope.hideCreateShape();
        $scope._registerUndoAction({
            type: "new_shape",
            shape: poly
        });
    };

    /**
     * Create a new circle and add it to the drawing
     *
     * @param radius Circle radius
     */
    $scope.addCircle = function(radius) {
        if(isNaN(radius) || radius <= 0)
        {
            $scope.new_shape_data.error = true;
            return;
        }

        var circle = new drawing.Circle(13, 13, radius);
        circle.decoration_id = $scope.randomDecorationId();
        draw.addShape(circle);
        $scope.hideCreateShape();
        $scope._registerUndoAction({
            type: "new_shape",
            shape: circle
        });
    };

    /**
     * Create a new ellipse and add it to the drawing
     *
     * @param width
     * @param height
     */
    $scope.addEllipse = function(width, height) {
        if(isNaN(width) || width <= 0 || isNaN(height) || height <= 0)
        {
            $scope.new_shape_data.error = true;
            return;
        }
        
        var ellipse = new drawing.Ellipse(13, 13, width, height);
        ellipse.decoration_id = $scope.randomDecorationId();
        draw.addShape(ellipse);
        $scope.hideCreateShape();
        $scope._registerUndoAction({
            type: "new_shape",
            shape: ellipse
        });
    };

    /**
     * Create a new rectangle and add it to the drawing
     *
     * @param width
     * @param height
     */
    $scope.addRectangle = function(width, height) {
        if(isNaN(width) || width <= 0 || isNaN(height) || height <= 0)
        {
            $scope.new_shape_data.error = true;
            return;
        }
        
        var rectangle = new drawing.Rectangle(13, 13, width, height);
        rectangle.decoration_id = $scope.randomDecorationId();
        draw.addShape(rectangle);
        $scope.hideCreateShape();
        $scope._registerUndoAction({
            type: "new_shape",
            shape: rectangle
        });
    };

    /**
     * Create a new trapezoid and add it to the drawing
     *
     * @param base1
     * @param base2
     * @param height
     * @param angle
     */
    $scope.addTrapezoid = function(base1, base2, height, angle) {
        if(!drawing.validTrapezoid(base1, base2, height, angle))
        {
            $scope.new_shape_data.error = true;
            return;
        }
        
        var trapezoid = new drawing.Trapezoid(13, 13, base1, base2, height, angle);
        trapezoid.decoration_id = $scope.randomDecorationId();
        draw.addShape(trapezoid);
        $scope.hideCreateShape();
        $scope._registerUndoAction({
            type: "new_shape",
            shape: trapezoid
        });
    };
    
    $scope.addTriangle = function(base, height, angle) {
        if(isNaN(base) || base <= 0 || isNaN(height) || height <= 0 || isNaN(angle) || angle <=0 || angle >= 180)
        {
            $scope.new_shape_data.error = true;
            return;
        }

        var triangle = new drawing.Triangle(13, 13, base, height, angle);
        triangle.decoration_id = $scope.randomDecorationId();
        draw.addShape(triangle);
        $scope.hideCreateShape();
        $scope._registerUndoAction({
            type: "new_shape",
            shape: triangle
        });
    };
    
    $scope.addRhombus = function(diag1, diag2) {
        diag1 = Number(diag1);
        diag2 = Number(diag2);
        if(isNaN(diag1) || diag1 <= 0 || isNaN(diag2) || diag2 <= 0 || diag2 >= diag1)
        {
            $scope.new_shape_data.error = true;
            return;
        }

        var r = new drawing.Rhombus(13, 13, diag1, diag2);
        r.decoration_id = $scope.randomDecorationId();
        draw.addShape(r);
        $scope.hideCreateShape();
        $scope._registerUndoAction({
            type: "new_shape",
            shape: r
        });
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
        $scope.selectedShape.decoration_id = $scope.selectedDecorationId;
        $scope.drawing.updateShape($scope.selectedShape);
    }

    $scope.saveDecoration = function()
    {
        $scope._registerUndoAction({
            type: "shape_data",
            shape: $scope.selectedShape,
            state: $scope.selectedShape.saveState()
        });
        $scope.selectedShape.decoration_id = $scope.selectedDecorationId;
        $scope.setTool('select');
        $scope.drawing.updateShape($scope.selectedShape);
    }

    $scope.cancelDecoration = function()
    {
        $scope.selectedShape.decoration_id = $scope.shapeOriginalDecorationId;
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
        $scope.contextMenu.hide();
    }


    $scope.insertDrawing = function()
    {
        $scope.insert_after_save = true;
        $scope.saveDrawing();
    }

    $scope._processInsertDrawing = function()
    {
        $scope.setStatus("Insertando objeto");
        jq.ajax({
            url: "../app_dev.php/my_objects/insert",
            type: "POST",
            data: {
                id: $scope.drawing.id,
            }
        }).done(function(msg)
        {
            $scope.setStatus("Objeto insertado");
            $scope.gotoScreen('view_city');
        });
    }

    $scope.saveDrawing = function()
    {
        if($scope.drawing.title == '')
        {
            $scope.save_after_properties = true;
            $scope.setTool('properties');
            return false;
        }

        $scope.setStatus('Guardando...');
        var json = JSON.stringify($scope.drawing);
        var thumb = $scope.renderer.makeThumb();
        var insert_after = $scope.insert_after_save;
        $scope.insert_after_save = false;

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
            $scope.setStatus("Objeto guardado");
            if(insert_after)
            {
                $scope._processInsertDrawing();
            }
        });
        $scope.contextMenu.hide();
    }
    // List of valid shapes
    $scope.shapes = ["square", "rectangle", "circle", "trapezoid", "triangle", "rhombus", "polygon", "ellipse"];
    $scope.new_shape_pager_from = 0;

    $scope.newShapePagerNext = function()
    {
        if($scope.new_shape_pager_from < $scope.shapes.length - 7)
            $scope.new_shape_pager_from += 1;
    }
    
    $scope.newShapePagerPrev = function()
    {
        if($scope.new_shape_pager_from > 0)
            $scope.new_shape_pager_from -= 1;
    }
});

/**
 * View City controller:
 */
craftingApp.controller('ViewCityCtrl', function ($scope, ScenesList) {
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
        jq.ajax({
            url: "../app_dev.php/city/" + $scope.drawing.id  + "/create",
            type: "GET",
            dataType: 'json'
        }).done(function(resp)
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
        var image = new createjs.Bitmap(selected_scene.full_image.src);
        $scope.stage.addChild(image);

        // Add drawings
        var used_zones = new Array();

        for(var i=0; i<objects.length;i++)
        {
            var tmp = new createjs.Bitmap("../app_dev.php/city/" + objects[i].id + "/image");
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
            tmp.title=objects[i].title;
            tmp.on("mouseover", function(evt) {
                evt.target.alpha=0.8;
                $scope.stage.update();
                $scope.bubbleMenu.html("<div class='bubble-user'>usuario</div><div class='bubble-title'>"+evt.target.title+"</div>");
                $scope.bubbleMenu.css({top: evt.target.y+$scope.stage.y-80, left: evt.target.x+$scope.stage.x+20, position:'absolute'});
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
        jq("#main-container").show();
    });
});

queue.loadManifest({id: "manifest", src:"assets/manifest.json", type:createjs.LoadQueue.MANIFEST});


});
