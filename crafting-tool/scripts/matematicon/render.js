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

ns.Renderer.prototype.render = function()
{
    var renderer = this;
    this._drawings.forEach(function(drawing) {
        drawing.drawing.visitShapes(renderer);
    });
    this._stage.update();
}

ns.Renderer.prototype.update = function(drawing, action, shape)
{
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

ns.Renderer.prototype._prepareGraphics = function(shape)
{
    var renderer = this;
    if(this._shapes[shape.index] == undefined)
    {
        var gshape = this._shapes[shape.index] = new createjs.Shape();
   
        gshape.on("click", function(evt) {
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
    
    gshape.x = shape.x * this._scaleFactor;
    gshape.y = shape.y * this._scaleFactor;
    gshape.rotation = shape.rotation;
    this._stage.addChild(gshape);

    return gshape;
}

ns.Renderer.prototype.visitCircle = function(shape)
{
    this._prepareGraphics(shape).graphics.setStrokeStyle(2).drawCircle(0, 0, shape.radius * this._scaleFactor);
}

ns.Renderer.prototype.visitSquare = function(shape)
{
    this._prepareGraphics(shape).graphics.rect(0, 0, shape.side * this._scaleFactor, shape.side * this._scaleFactor);
}

ns.Renderer.prototype.visitRectangle = function(shape)
{
    this._prepareGraphics(shape).graphics.rect(0, 0, shape.width * this._scaleFactor, shape.height * this._scaleFactor);
}

ns.Renderer.prototype.visitTrapezoid = function(shape)
{
    this._prepareGraphics(shape).graphics.mt(0, 0).lt(shape.base2 * this._scaleFactor, 0).lt((shape.base2 - (shape.base2 - shape.base1)/2.) * this._scaleFactor, -shape.height * this._scaleFactor).lt((shape.base2 - shape.base1) / 2. * this._scaleFactor, -shape.height * this._scaleFactor).cp();
}

return ns;
}); 

