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
 * CreateJS renderer
 *
 * @param CreateJS stage
 */
ns.Renderer = function(stage, scaleFactor, decorationTable)
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
    drawing.addObserver(this);
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

ns.Renderer.prototype._setSelectedShape = function(shape)
{
    this._selectedShape = shape;
    this._subject.notify(this, "selectedShape", shape);
}

ns.Renderer.prototype._contextMenu = function(evt)
{
    this._subject.notify(this, "contextMenu", evt);
}

ns.Renderer.prototype._prepareGraphics = function(shape)
{
    var renderer = this;
    if(this._shapes[shape.index] == undefined)
    {
        var gshape = this._shapes[shape.index] = new createjs.Shape();
   
        gshape.on("click", function(evt) {
            switch(renderer._tool)
            {
                case "select":
                    renderer._setSelectedShape(shape);
                    renderer.render();
                    if(evt.nativeEvent.button == 2)
                    {
                        renderer._contextMenu(evt);
                    }
            }
        });

        gshape.on("mousedown", function(evt) {
            switch(renderer._tool)
            {
                case "select":
                    renderer._setSelectedShape(shape);
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

        gshape.on("pressmove", function(evt) {
            switch(renderer._tool)
            {
                case "select":
			        var tmp_x = evt.stageX + this.offset.x;
                    var tmp_y = evt.stageY + this.offset.y;
                    shape.x = tmp_x / scaleFactor;
                    shape.y = tmp_y / scaleFactor;
                    // indicate that the stage should be updated on the next tick:
                    renderer.render();
                    break;
                case "rotate":
                    if(renderer._selectedShape == shape)
                    {
                        shape.rotation += (this.old_offset.y - evt.stageY + evt.stageX - this.old_offset.x);
			            this.old_offset = {x:evt.stageX, y:evt.stageY};
                        // indicate that the stage should be updated on the next tick:
                        renderer.render();
                    }
                    break;
            }
		}); 
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
    if(this._selectedShape == shape)
    {
        this._prepareSelectionGraphics(graph).drawCircle(0, 0, shape.radius * this._scaleFactor);
    }
}

ns.Renderer.prototype.visitSquare = function(shape)
{
    var graph = this._prepareGraphics(shape).rect(0, 0, shape.side * this._scaleFactor, shape.side * this._scaleFactor);
    this._shapes[shape.index].regX = shape.side / 2 * this._scaleFactor;
    this._shapes[shape.index].regY = shape.side / 2 * this._scaleFactor;
    if(this._selectedShape == shape)
    {
        this._prepareSelectionGraphics(graph).rect(0, 0, shape.side * this._scaleFactor, shape.side * this._scaleFactor);
    }
}

ns.Renderer.prototype.visitRectangle = function(shape)
{
    var graph = this._prepareGraphics(shape).rect(0, 0, shape.width * this._scaleFactor, shape.height * this._scaleFactor);
    this._shapes[shape.index].regX = shape.width / 2 * this._scaleFactor;
    this._shapes[shape.index].regY = shape.height / 2 * this._scaleFactor;
    if(this._selectedShape == shape)
    {
        this._prepareSelectionGraphics(graph).rect(0, 0, shape.width * this._scaleFactor, shape.height * this._scaleFactor);
    }
}

ns.Renderer.prototype.visitTrapezoid = function(shape)
{
    var graph = this._prepareGraphics(shape).mt(0, 0).lt(shape.base2 * this._scaleFactor, 0).lt((shape.base2 - (shape.base2 - shape.base1)/2.) * this._scaleFactor, -shape.height * this._scaleFactor).lt((shape.base2 - shape.base1) / 2. * this._scaleFactor, -shape.height * this._scaleFactor).cp();
    this._shapes[shape.index].regX = shape.base2 / 2 * this._scaleFactor;
    this._shapes[shape.index].regY = -shape.height / 2 * this._scaleFactor;
    if(this._selectedShape == shape)
    {
        this._prepareSelectionGraphics(graph).mt(0, 0).lt(shape.base2 * this._scaleFactor, 0).lt((shape.base2 - (shape.base2 - shape.base1)/2.) * this._scaleFactor, -shape.height * this._scaleFactor).lt((shape.base2 - shape.base1) / 2. * this._scaleFactor, -shape.height * this._scaleFactor).cp();
    }
}

ns.Renderer.prototype.visitTriangle = function(shape)
{
    var side = shape.height / Math.sin(shape.angle / 180 * Math.PI);
    console.log(side);
    var x = Math.sqrt(side * side - shape.height * shape.height);
    console.log(x);
    var graph = this._prepareGraphics(shape).mt(0, 0).lt(x * this._scaleFactor, -shape.height * this._scaleFactor).lt(shape.base * this._scaleFactor, 0).lt(0,0).cp();
    this._shapes[shape.index].regX = shape.base / 2 * this._scaleFactor;
    this._shapes[shape.index].regY = -shape.height / 2 * this._scaleFactor;
    if(this._selectedShape == shape)
    {
        this._prepareSelectionGraphics(graph).mt(0, 0).lt(x * this._scaleFactor, -shape.height * this._scaleFactor).lt(shape.base * this._scaleFactor, 0).lt(0,0).cp();
    }
}

ns.Renderer.prototype.visitRhombus = function(shape)
{
    shape.rotation += 45;
    var graph = this._prepareGraphics(shape).rect(0, 0, shape.side * this._scaleFactor, shape.side * this._scaleFactor);
    this._shapes[shape.index].regX = shape.side / 2 * this._scaleFactor;
    this._shapes[shape.index].regY = shape.side / 2 * this._scaleFactor;
    if(this._selectedShape == shape)
    {
        this._prepareSelectionGraphics(graph).rect(0, 0, shape.side * this._scaleFactor, shape.side * this._scaleFactor);
    }
    shape.rotation -=45;
}


return ns;
}); 

