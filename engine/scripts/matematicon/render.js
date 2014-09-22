/**
 * Plataforma Matematicon Educ.ar
 *
 * Developed by PressEnter.com.ar team
 *
 * @license magnet:?xt=urn:btih:1f739d935676111cfff4b4693e3816e664797050&dn=gpl-3.0.txt GPL-v3-or-Later
 */

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
    this._offsetX = offsetX;
    this._offsetY = offsetY;
    drawing.visitShapes(this);
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
    var gshape = new createjs.Shape();
    var graphics = this._configureDecoration(gshape.graphics, shape.decoration);
    gshape.x = shape.x * this._scaleFactor;
    gshape.y = shape.y * this._scaleFactor;
    gshape.rotation = shape.rotation;
    this._stage.addChild(gshape);
    return gshape;
 }

ns.Renderer.prototype.visitCircle = function(shape)
{
    this._prepareGraphics(shape).graphics.drawCircle(0, 0, shape.radius * this._scaleFactor);
}


ns.Renderer.prototype.visitSquare = function(shape)
{
    this._prepareGraphics(shape).graphics.rect(0, 0, shape.side * this._scaleFactor, shape.side * this._scaleFactor);
}

return ns;
}); 

