'use strict';

define(["createjs",
        "jquery",
        "matematicon/drawing",
        "matematicon/render"],
function(createjs, jq, drawing, render) {
/**
 * Drawing tool controller.
 */
return function ($scope, DecorationTable, BackgroundFactory, ScenesList, ObjectsPersistor) {
    var stage = new createjs.Stage("canvas");
    stage.scaleX=stage.scaleY=312./276.; // hack
    var draw = $scope.drawing;

    $scope.Math = window.Math;
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
        $scope.showHideBackground();
        $scope.showHideBackground();
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
            $scope.setDrawingZone($scope.properties_scene, $scope.properties_zone);
        }

        var gotoSave = $scope.save_after_properties;
        var insertAfter = $scope.insert_after_save;
        $scope.setTool("select");

        if(gotoSave)
        {
            $scope.insert_after_save = insertAfter;
            $scope.save_after_properties = false;
            $scope.saveDrawing();
        }
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
        shape.x += 3;
        shape.y += 3;
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
            return drawing.validSquare(Number($scope.edit_shape_data.side));
        },
        visitRhombus: function()
        {
            return drawing.validRhombus(Number($scope.edit_shape_data.diag1), Number($scope.edit_shape_data.diag2));
        },
        visitCircle: function()
        {
            return drawing.validCircle(Number($scope.edit_shape_data.radius));
        },
        visitSemiCircle: function()
        {
            return drawing.validSemiCircle(Number($scope.edit_shape_data.radius));
        },
        visitPolygon: function()
        {
            return drawing.validPolygon(Number($scope.edit_shape_data.sides), Number($scope.edit_shape_data.side));
        },
        visitEllipse: function()
        {
            return drawing.validEllipse(Number($scope.edit_shape_data.radius1), Number($scope.edit_shape_data.radius2));
        },
        visitSemiEllipse: function()
        {
            return drawing.validSemiEllipse(Number($scope.edit_shape_data.radius1), Number($scope.edit_shape_data.radius2));
        },
        visitRectangle: function()
        {
            return drawing.validRectangle(Number($scope.edit_shape_data.width), Number($scope.edit_shape_data.height));
        },
        visitTrapezoid: function()
        {
            return drawing.validTrapezoid(
                Number($scope.edit_shape_data.base1),
                Number($scope.edit_shape_data.base2),
                Number($scope.edit_shape_data.height)
            );
        },
        visitTriangle: function()
        {
            return drawing.validTriangle(
                Number($scope.edit_shape_data.base),
                Number($scope.edit_shape_data.height),
                Number($scope.edit_shape_data.angle)
            );
        }

    };

    // Functions to save changes to the currently edited shape
    var _shapeSavers = {
        visitSquare: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.side = Number($scope.edit_shape_data.side);
        },
        visitRhombus: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.diag1 = Number($scope.edit_shape_data.diag1);
            shape.diag2 = Number($scope.edit_shape_data.diag2);
        },
        visitCircle: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.radius = Number($scope.edit_shape_data.radius);
        },
        visitSemiCircle: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.radius = Number($scope.edit_shape_data.radius);
        },
        visitPolygon: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.sides = Number($scope.edit_shape_data.sides);
            shape.side = Number($scope.edit_shape_data.side);
        },
        visitEllipse: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.radius1 = Number($scope.edit_shape_data.radius1);
            shape.radius2 = Number($scope.edit_shape_data.radius2);
        },
        visitSemiEllipse: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.radius1 = Number($scope.edit_shape_data.radius1);
            shape.radius2 = Number($scope.edit_shape_data.radius2);
        },
        visitRectangle: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.width = Number($scope.edit_shape_data.width);
            shape.height = Number($scope.edit_shape_data.height);
        },
        visitTrapezoid: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.base1 = Number($scope.edit_shape_data.base1);
            shape.base2 = Number($scope.edit_shape_data.base2);
            shape.height = Number($scope.edit_shape_data.height);
        },
        visitTriangle: function()
        {
            var shape = $scope.edit_shape_data.shape;
            shape.base = Number($scope.edit_shape_data.base);
            shape.angle = Number($scope.edit_shape_data.angle);
            shape.height = Number($scope.edit_shape_data.height);
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
        visitSemiCircle : function(shape) {
            $scope.edit_shape_data.radius = shape.radius;
        },
        visitPolygon : function(shape) {
            $scope.edit_shape_data.sides = shape.sides;
            $scope.edit_shape_data.side = shape.side;
        },
        visitEllipse : function(shape) {
            $scope.edit_shape_data.radius1 = shape.radius1;
            $scope.edit_shape_data.radius2 = shape.radius2;
        },
        visitSemiEllipse : function(shape) {
            $scope.edit_shape_data.radius1 = shape.radius1;
            $scope.edit_shape_data.radius2 = shape.radius2;
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
        if(!drawing.validSquare(Number(side)))
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
        if(!drawing.validPolygon(Number(sides), Number(side)))
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
     * Create a new Semi circle and add it to the drawing
     *
     * @param radius Circle radius
     */
    $scope.addSemiCircle = function(radius) {
        if(!drawing.validSemiCircle(Number(radius)))
        {
            $scope.new_shape_data.error = true;
            return;
        }
        var semicircle = new drawing.SemiCircle(13, 13, radius)
        semicircle.decoration_id = $scope.randomDecorationId();
        draw.addShape(semicircle);
        $scope.hideCreateShape();
        $scope._registerUndoAction({
            type: "new_shape",
            shape: semicircle
        });
    };
    /**
     * Create a new circle and add it to the drawing
     *
     * @param radius Circle radius
     */
    $scope.addCircle = function(radius) {
        if(!drawing.validCircle(Number(radius)))
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
    $scope.addEllipse = function(radius1, radius2) {
        if(!drawing.validEllipse(Number(radius1), Number(radius2)))
        {
            $scope.new_shape_data.error = true;
            return;
        }

        var ellipse = new drawing.Ellipse(13, 13, radius1, radius2);
        ellipse.decoration_id = $scope.randomDecorationId();
        draw.addShape(ellipse);
        $scope.hideCreateShape();
        $scope._registerUndoAction({
            type: "new_shape",
            shape: ellipse
        });
    };

    /**
     * Create a new Semiellipse and add it to the drawing
     *
     * @param width
     * @param height
     */
    $scope.addSemiEllipse = function(radius1, radius2) {
        if(!drawing.validSemiEllipse(Number(radius1), Number(radius2)))
        {
            $scope.new_shape_data.error = true;
            return;
        }

        var semiellipse = new drawing.SemiEllipse(13, 13, radius1, radius2);
        semiellipse.decoration_id = $scope.randomDecorationId();
        draw.addShape(semiellipse);
        $scope.hideCreateShape();
        $scope._registerUndoAction({
            type: "new_shape",
            shape: semiellipse
        });
    };

  /**
     * Create a new rectangle and add it to the drawing
     *
     * @param width
     * @param height
     */
    $scope.addRectangle = function(width, height) {
        if(!drawing.validRectangle(Number(width), Number(height)))
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
    $scope.addTrapezoid = function(base1, base2, height) {
        if(!drawing.validTrapezoid(Number(base1), Number(base2), Number(height)))
        {
            $scope.new_shape_data.error = true;
            return;
        }

        var trapezoid = new drawing.Trapezoid(13, 13, base1, base2, height);
        trapezoid.decoration_id = $scope.randomDecorationId();
        draw.addShape(trapezoid);
        $scope.hideCreateShape();
        $scope._registerUndoAction({
            type: "new_shape",
            shape: trapezoid
        });
    };

    $scope.addTriangle = function(base, height, angle) {
        if(!drawing.validTriangle(Number(base), Number(height), Number(angle)))
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
        if(!drawing.validRhombus(Number(diag1), Number(diag2)))
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
        ObjectsPersistor.insertDrawing($scope.drawing, function()
        {
            $scope.setStatus("Objeto insertado");
            $scope.gotoScreen('view_city');
            $scope.$apply();
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
        var thumb = $scope.renderer.makeThumb();
        var insert_after = $scope.insert_after_save;
        $scope.insert_after_save = false;

        ObjectsPersistor.save($scope.drawing, thumb, function()
        {
            $scope.setStatus("Objeto guardado");
            if(insert_after)
            {
                $scope._processInsertDrawing();
            }
        });
        $scope.contextMenu.hide();
    }
    // List of valid shapes
    $scope.shapes = ["semicircle", "square", "rectangle", "circle", "trapezoid", "triangle", "rhombus", "polygon", "ellipse", "semiellipse"];
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

    $scope.importFile = function(evt)
    {
        console.log(evt);
        console.log(jq("#file").file);
    }
};
});
