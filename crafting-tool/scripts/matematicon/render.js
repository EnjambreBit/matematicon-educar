/**
 * Plataforma Matematicon Educ.ar
 *
 * Developed by PressEnter.com.ar team
 *
 * @license magnet:?xt=urn:btih:1f739d935676111cfff4b4693e3816e664797050&dn=gpl-3.0.txt GPL-v3-or-Later
 */
'use strict';

define(["createjs", "jquery"], function (createjs, $) {

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
    this._shapes = new Array();
}

/**
 * Renders a drawing in the stage
 *
 * @param matematicon/drawing.Drawing drawing
 * @param int offsetX
 * @param int offsetY
 */
ns.Renderer.prototype.render = function(drawing, offsetX, offsetY)
{
    drawing.addObserver(this);
    this._offsetX = offsetX;
    this._offsetY = offsetY;
    drawing.visitShapes(this);
}

ns.Renderer.prototype.update = function(drawing, action, shape)
{
    console.log(action);
    //if(action != "newShape")
    //{
    //    this._stage.removeChild(this._shapes[shape]);
    //    this._stage.update();
    //    delete this._shapes[shape];
    //}
    shape.visit(this);
    this._stage.update();
}

ns.Renderer.prototype._configureDecoration = function(graphics, decoration)
{
    if(decoration == null)
    {
        return graphics;
    }

    var decoration_def = this._decorationTable[decoration.id];
    if(decoration_def.type == 'color')
    {
        return graphics.beginFill(decoration_def.fill);
    }
    else if(decoration_def.type == 'pattern')
    {
        return graphics.beginBitmapFill(decoration_def.fill);
    }
    console.log("Invalid decoration:", decoration);
    return graphics;
}

ns.Renderer.prototype._prepareGraphics = function(shape)
{
    if(this._shapes[shape.index] == undefined)
    {
        console.log("create gshape");
        var gshape = this._shapes[shape.index] = new createjs.Shape();
   
        gshape.on("mousedown", function(evt) {
			this.parent.addChild(this);
			this.offset = {x:this.x-evt.stageX, y:this.y-evt.stageY};
		});

        var stage = this._stage;
        var scaleFactor = this._scaleFactor;

        gshape.on("pressmove", function(evt) {
			this.x = evt.stageX+ this.offset.x;
			this.y = evt.stageY+ this.offset.y;
               shape.x = this.x / scaleFactor;
               shape.y = this.y / scaleFactor;
			// indicate that the stage should be updated on the next tick:
			stage.update();
		}); 
    }
    else
    {
        console.log("clearing!!");
        this._shapes[shape.index].graphics.clear();
    }
    console.log(this._shapes);
    var gshape = this._shapes[shape.index];
    var graphics = this._configureDecoration(gshape.graphics, shape.decoration);
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

ns.Renderer.prototype.visitRect = function(shape)
{
    this._prepareGraphics(shape).graphics.rect(0, 0, shape.width * this._scaleFactor, shape.height * this._scaleFactor);
}

return ns;
}); 

