/**
 * Plataforma Matematicon Educ.ar
 *
 * Developed by PressEnter.com.ar team
 *
 * @license magnet:?xt=urn:btih:1f739d935676111cfff4b4693e3816e664797050&dn=gpl-3.0.txt GPL-v3-or-Later
 */
'use strict';

define(["createjs", "jquery", "observer"], function (createjs, $, observer) {

var ns = {};

/**
 * Drawings Renderer
 *
 * @param interactive If false, shapes are static, else they are draggable.
 *
 * @param CreateJS stage
 */
ns.Renderer = function(stage, scaleFactor, decorationTable, interactive)
{
    this._stage = stage;
    this._scaleFactor = scaleFactor;
    this._decorationTable = decorationTable;
    this._shapes = new Array(); // EaselJS shapes
    this._drawings = new Array(); // Drawings in the stage with their offset
    this._subject = new observer.Subject();
    this._tool = "";
    this._selectedShape = null;
    this._background = null;
    this._offsetX = 0;
    this._offsetY = 0;
    this._maxCoord = 312;
    this._interactive = interactive == undefined || interactive;
}

ns.Renderer.prototype.destroy = function()
{
    this._stage.removeAllChildren();
    this._stage.removeAllEventListeners();
    this._stage = null;
}

/**
 * Set current tool
 */
ns.Renderer.prototype.setTool = function(tool_name)
{
    this._tool = tool_name;
}

/**
 * Add a drawing in the stage
 *
 * @param matematicon/drawing.Drawing drawing
 * @param int offsetX
 * @param int offsetY
 */
ns.Renderer.prototype.addDrawing = function(drawing, offsetX, offsetY)
{
    if(this._interactive)
    {
        drawing.addObserver(this);
    }
    this._drawings.push({
        offsetX: offsetX,
        offsetY: offsetY,
        drawing: drawing
    });
}

ns.Renderer.prototype.addObserver = function(observer)
{
    this._subject.observe(observer); 
}

ns.Renderer.prototype.hideBackground = function()
{
    this._stage.removeChild(this._background);
    this._background = null;
    this.render();
}

ns.Renderer.prototype.setBackground = function(bkg)
{
    this._background = bkg;
    this.render();
}

ns.Renderer.prototype.render = function()
{
    if(this._background != null)
    {
        this._stage.addChild(this._background);
    }

    var renderer = this;
    this._drawings.forEach(function(drawing) {
        renderer._offsetX = drawing.offsetX;
        renderer._offsetY = drawing.offsetY;
        drawing.drawing.visitShapes(renderer);
    });
    this._stage.update();
}

ns.Renderer.prototype.makeThumb = function()
{
    var renderer = this;
    
    var tmp_sel = this._selectedShape;
    this._selectedShape = null;

    var tmp_bkg = this._background;
    
    if(this._background != null)
    {
        this._stage.removeChild(this._background);
    }

    this.background = null;
    
    this._drawings.forEach(function(drawing) {
        drawing.drawing.visitShapes(renderer);
    });
    this._stage.update();
    
    var data = this._stage.toDataURL();

    this._selectedShape = tmp_sel;
    this._background = tmp_bkg;
    this.render();

    return data;
}

ns.Renderer.prototype.update = function(drawing, action, shape)
{
    if(action == "deletedShape" && this._shapes[shape.index] != undefined)
    {
        this._stage.removeChild(this._shapes[shape.index]);
        delete this._shapes[shape.index];
    }

    this.render();
    if(action != "deletedShape")
    {
        this.checkShape(shape);
    }
}

ns.Renderer.prototype.checkShape = function(shape)
{
    // Adjust shape to be inside the stage
    var minY=0;
    var minX=0;
    var maxY=this._maxCoord;
    var maxX=this._maxCoord;
    for(var i=0; i < this._shapes[shape.index].vertices.length; i++)
    {
        var tmpv = this._shapes[shape.index].vertices[i];
        var tmp = this._shapes[shape.index].localToGlobal(tmpv.x, tmpv.y);  
        var v = this._stage.globalToLocal(tmp.x, tmp.y);
        if(v.x < minX)
            minX = v.x;
        if(v.y < minY)
            minY = v.y;
        if(v.x > maxX)
            maxX = v.x;
        if(v.y > maxY)
            maxY = v.y;
    }
    if(minX < 0)
    {
        shape.x += -minX /this._scaleFactor;
    }
    if(minY < 0)
    {
        shape.y += -minY /this._scaleFactor;
    }
    if(maxX > this._maxCoord)
    {
        shape.x -= (maxX - this._maxCoord) /this._scaleFactor;
    }
    if(maxY > this._maxCoord)
    {
        shape.y -= (maxY - this._maxCoord) /this._scaleFactor;
    }
    this.render();
}


ns.Renderer.prototype._configureDecoration = function(graphics, decoration)
{
    if(decoration == null)
    {
        return graphics;
    }

    var decoration_def = this._decorationTable[decoration];
    if(decoration_def.type == 'color')
    {
        return graphics.beginFill(decoration_def.fill);
    }
    else if(decoration_def.type == 'pattern')
    {
        return graphics.beginBitmapFill(decoration_def.fill);
    }
    return graphics;
}


ns.Renderer.prototype.setSelectedShape = function(shape)
{
    this._selectedShape = shape;
    this._subject.notify(this, "selectedShape", shape);
}

ns.Renderer.prototype._contextMenu = function(evt)
{
    this._subject.notify(this, "contextMenu", evt);
}

ns.Renderer.prototype._notifyBeforeTransform = function(shape)
{
    this._notifyBeforeTransformDone = true;
    this._subject.notify(this, "beforeTransform", shape);
}

ns.Renderer.prototype._prepareGraphics = function(shape)
{
    if(this._shapes[shape.index] == undefined)
    {
        var gshape = this._shapes[shape.index] = new createjs.Shape();
   
        
        if(this._interactive)
        {
            var renderer = this;
            var maxcoord = renderer._stage.canvas.clientWidth;

            gshape.on("click", function(evt) {
                switch(renderer._tool)
                {
                    case "select":
                        renderer.setSelectedShape(shape);
                        renderer.render();
                        if(evt.nativeEvent.button == 2)
                        {
                            renderer._contextMenu(evt);
                        }
                }
            });

            gshape.on("mousedown", function(evt) {
                renderer._notifyBeforeTransformDone = false;
                switch(renderer._tool)
                {
                    case "select":
                        renderer.setSelectedShape(shape);
                        this.parent.addChild(this);
                        this.offset = {x:this.x-evt.stageX, y:this.y-evt.stageY};
                        break;
                    case "rotate":
                        this.parent.addChild(this);
                        this.old_offset = {x:evt.stageX, y:evt.stageY};
                        break;
                }
            });

            var scaleFactor = this._scaleFactor;

            gshape.on("pressup", function(evt) {
                // Adjust shape to be inside the stage
                var minY=0;
                var minX=0;
                var maxY=maxcoord;
                var maxX=maxcoord;
                for(var i=0; i < renderer._shapes[shape.index].vertices.length; i++)
                {
                    var tmpv = renderer._shapes[shape.index].vertices[i];
                    var tmp = renderer._shapes[shape.index].localToGlobal(tmpv.x, tmpv.y);  
                    var v = renderer._stage.globalToLocal(tmp.x, tmp.y);
                    if(v.x < minX)
                        minX = v.x;
                    if(v.y < minY)
                        minY = v.y;
                    if(v.x > maxX)
                        maxX = v.x;
                    if(v.y > maxY)
                        maxY = v.y;
                }
                if(minX < 0)
                {
                    shape.x += -minX /scaleFactor;
                }
                if(minY < 0)
                {
                    shape.y += -minY /scaleFactor;
                }
                if(maxX > maxcoord)
                {
                    shape.x -= (maxX - maxcoord) /scaleFactor;
                }
                if(maxY > maxcoord)
                {
                    shape.y -= (maxY - maxcoord) /scaleFactor;
                }
                renderer.render();
            });

            gshape.on("pressmove", function(evt) {
                if(!renderer._notifyBeforeTransformDone && (renderer._tool == "select" || renderer._tool == "rotate"))
                {
                    renderer._notifyBeforeTransform(shape);
                }

                switch(renderer._tool)
                {
                    case "select":
                        var tmp_x = evt.stageX + this.offset.x;
                        var tmp_y = evt.stageY + this.offset.y;
                        var old_x = shape.x;
                        var old_y = shape.y;
                        shape.x = tmp_x / scaleFactor;
                        shape.y = tmp_y / scaleFactor;
                        var vertices = renderer._shapes[shape.index].vertices;
                        for(var i = 0; i < vertices.length; i++)
                        {
                            var tmp = renderer._shapes[shape.index].localToGlobal(vertices[i].x, vertices[i].y);  
                            var p = renderer._stage.globalToLocal(tmp.x, tmp.y);
                            if(p.x < 0 && shape.x < old_x)
                            {
                                shape.x = old_x;
                            }
                            if(p.x > maxcoord && shape.x > old_x)
                            {
                                shape.x = old_x;
                            }
                            if(p.y < 0 && shape.y < old_y)
                            {
                                shape.y = old_y;
                            }
                            if(p.y > maxcoord && shape.y > old_y)
                            {
                                shape.y = old_y;
                            }
                        }
                            
                        renderer.render();
                        break;
                    case "rotate":
                        if(renderer._selectedShape == shape)
                        {
                            shape.rotation += (this.old_offset.y - evt.stageY + evt.stageX - this.old_offset.x);
                            this.old_offset = {x:evt.stageX, y:evt.stageY};
                            renderer.render();
                        }
                        break;
                }
            });
        }
    }
    else
    {
        this._shapes[shape.index].graphics.clear();
    }
    var gshape = this._shapes[shape.index];
    var graphics = this._configureDecoration(gshape.graphics, shape.decoration_id);
    
    gshape.x = (shape.x + this._offsetX) * this._scaleFactor;
    gshape.y = (shape.y + this._offsetY) * this._scaleFactor;
    gshape.rotation = shape.rotation;
    this._stage.addChild(gshape);

    return gshape.graphics;
}

ns.Renderer.prototype._prepareSelectionGraphics = function(graphics)
{
    return graphics.endFill().setStrokeStyle(3, 0, "bevel").beginStroke("#555");
}

ns.Renderer.prototype.visitCircle = function(shape)
{
    var graph = this._prepareGraphics(shape).drawCircle(0, 0, shape.radius * this._scaleFactor);
    
    var vertices = new Array();
    var r = shape.radius * this._scaleFactor;
    vertices.push(new createjs.Point(-r, -r));
    vertices.push(new createjs.Point(r, -r));
    vertices.push(new createjs.Point(-r, r));
    vertices.push(new createjs.Point(r, r));
    this._shapes[shape.index].vertices = vertices;
    if(this._selectedShape == shape)
    {
        this._prepareSelectionGraphics(graph).drawCircle(0, 0, shape.radius * this._scaleFactor);
    }
}

ns.Renderer.prototype.visitSquare = function(shape)
{
    var graph = this._prepareGraphics(shape).rect(0, 0, shape.side * this._scaleFactor, shape.side * this._scaleFactor);
    
    var vertices = new Array();
    var r = shape.side * this._scaleFactor;
    vertices.push(new createjs.Point(0, 0));
    vertices.push(new createjs.Point(0, r));
    vertices.push(new createjs.Point(r, 0));
    vertices.push(new createjs.Point(r, r));
    
    this._shapes[shape.index].vertices = vertices;
    this._shapes[shape.index].regX = shape.side / 2 * this._scaleFactor;
    this._shapes[shape.index].regY = shape.side / 2 * this._scaleFactor;
    if(this._selectedShape == shape)
    {
        this._prepareSelectionGraphics(graph).rect(0, 0, shape.side * this._scaleFactor, shape.side * this._scaleFactor);
    }
}

ns.Renderer.prototype.visitPolygon = function(shape)
{
    var rad = shape.side / (2 * Math.sin(Math.PI / shape.sides));
    var graph = this._prepareGraphics(shape).dp(0, 0, rad * this._scaleFactor, shape.sides, 0, 0);
    
    var vertices = new Array();
    var r = rad * this._scaleFactor;
    vertices.push(new createjs.Point(-r, -r));
    vertices.push(new createjs.Point(r, -r));
    vertices.push(new createjs.Point(-r, r));
    vertices.push(new createjs.Point(r, r));
    this._shapes[shape.index].vertices = vertices;
    
    if(this._selectedShape == shape)
    {
        this._prepareSelectionGraphics(graph).dp(0, 0, rad * this._scaleFactor, shape.sides, 0, 0);
    }
}

ns.Renderer.prototype.visitEllipse = function(shape)
{
    var graph = this._prepareGraphics(shape).de(0, 0, shape.radius1 * 2. * this._scaleFactor, shape.radius2 * 2. * this._scaleFactor);
    
    var vertices = new Array();
    var w = shape.radius1 * 2. * this._scaleFactor;
    var h = shape.radius2 * 2. * this._scaleFactor;
    vertices.push(new createjs.Point(0, 0));
    vertices.push(new createjs.Point(0, h));
    vertices.push(new createjs.Point(w, 0));
    vertices.push(new createjs.Point(w, h));
    this._shapes[shape.index].vertices = vertices;

    this._shapes[shape.index].regX = shape.radius1 * this._scaleFactor;
    this._shapes[shape.index].regY = shape.radius2 * this._scaleFactor;
    if(this._selectedShape == shape)
    {
        this._prepareSelectionGraphics(graph).de(0, 0, shape.radius1 * 2. * this._scaleFactor, shape.radius2 * 2. * this._scaleFactor);
    }
}

ns.Renderer.prototype.visitRectangle = function(shape)
{
    var graph = this._prepareGraphics(shape).rect(0, 0, shape.width * this._scaleFactor, shape.height * this._scaleFactor);
    var vertices = new Array();
    var w = shape.width * this._scaleFactor;
    var h = shape.height * this._scaleFactor;
    vertices.push(new createjs.Point(0, 0));
    vertices.push(new createjs.Point(0, h));
    vertices.push(new createjs.Point(w, 0));
    vertices.push(new createjs.Point(w, h));
    this._shapes[shape.index].vertices = vertices;
    this._shapes[shape.index].regX = shape.width / 2 * this._scaleFactor;
    this._shapes[shape.index].regY = shape.height / 2 * this._scaleFactor;
    if(this._selectedShape == shape)
    {
        this._prepareSelectionGraphics(graph).rect(0, 0, shape.width * this._scaleFactor, shape.height * this._scaleFactor);
    }
}

ns.Renderer.prototype.visitTrapezoid = function(shape)
{
    var x = shape.height * Math.cos(shape.angle / 180 * Math.PI) / Math.sin(shape.angle / 180 * Math.PI); 
    
    var vertices = new Array();
    vertices.push(new createjs.Point(0, 0));
    vertices.push(new createjs.Point(x * this._scaleFactor, -shape.height * this._scaleFactor));
    vertices.push(new createjs.Point((x + shape.base1) * this._scaleFactor, -shape.height * this._scaleFactor));
    vertices.push(new createjs.Point(shape.base2 * this._scaleFactor, 0));
 
    var graph = this._prepareGraphics(shape);
    for(var i=0; i < vertices.length; i++)
    {
        graph.lt(vertices[i].x, vertices[i].y)
    }
    graph.cp();
    
    this._shapes[shape.index].vertices = vertices;
    this._shapes[shape.index].regX = shape.base2 / 2 * this._scaleFactor;
    this._shapes[shape.index].regY = -shape.height / 2 * this._scaleFactor;
    
    if(this._selectedShape == shape)
    {
        var graph = this._prepareSelectionGraphics(graph).mt(0, 0);
        for(var i=0; i < vertices.length; i++)
        {
            graph.lt(vertices[i].x, vertices[i].y)
        }
    }
}

ns.Renderer.prototype.visitTriangle = function(shape)
{
    var side = shape.height / Math.sin(shape.angle / 180 * Math.PI);
    var x = Math.sqrt(side * side - shape.height * shape.height);
    if(shape.angle > 90)
        x = -x;
    
    var vertices = new Array();
    vertices.push(new createjs.Point(0, 0));
    vertices.push(new createjs.Point(x * this._scaleFactor, -shape.height * this._scaleFactor));
    vertices.push(new createjs.Point(shape.base * this._scaleFactor, 0));

    var graph = this._prepareGraphics(shape);
    for(var i=0; i < vertices.length; i++)
    {
        graph.lt(vertices[i].x, vertices[i].y)
    }
    graph.cp();

    this._shapes[shape.index].vertices = vertices;
    this._shapes[shape.index].regX = shape.base / 2 * this._scaleFactor;
    this._shapes[shape.index].regY = -shape.height / 2 * this._scaleFactor;
    if(this._selectedShape == shape)
    {
        var graph = this._prepareSelectionGraphics(graph).mt(0, 0);
        for(var i=0; i < vertices.length; i++)
        {
            graph.lt(vertices[i].x, vertices[i].y)
        }
        graph.cp().drawCircle(0,0, 5);
    }
}

ns.Renderer.prototype.visitRhombus = function(shape)
{
    var vertices = new Array();
    vertices.push(new createjs.Point(0, 0));
    vertices.push(new createjs.Point(shape.diag2 / 2. * this._scaleFactor, shape.diag1 / 2. * this._scaleFactor));
    vertices.push(new createjs.Point(shape.diag2 * this._scaleFactor, 0))
    vertices.push(new createjs.Point(shape.diag2 / 2. * this._scaleFactor, -shape.diag1 / 2. * this._scaleFactor));

    var graph = this._prepareGraphics(shape);
    for(var i=0; i < vertices.length; i++)
    {
        graph.lt(vertices[i].x, vertices[i].y)
    }
    graph.cp();
    
    this._shapes[shape.index].vertices = vertices;
    this._shapes[shape.index].regX = shape.diag2 / 2 * this._scaleFactor;
    
    if(this._selectedShape == shape)
    {
        var graph = this._prepareSelectionGraphics(graph).mt(0, 0);
        for(var i=0; i < vertices.length; i++)
        {
            graph.lt(vertices[i].x, vertices[i].y)
        }
        graph.cp();
    }
}


return ns;
}); 

