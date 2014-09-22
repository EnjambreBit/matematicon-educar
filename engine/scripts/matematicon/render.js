/**
 * Plataforma Matematicon Educ.ar
 *
 * Developed by PressEnter.com.ar team
 *
 * @license magnet:?xt=urn:btih:1f739d935676111cfff4b4693e3816e664797050&dn=gpl-3.0.txt GPL-v3-or-Later
 */

define(["kinetic", "jquery"], function (Kinetic, $) {

var ns = {};

/**
 * Kinetic renderer
 *
 * @param KineticLayer layer
 */
ns.Renderer = function(layer, scaleFactor, decorationTable)
{
    this._layer = layer;
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

ns.Renderer.prototype._buildDecorationParameters = function(decoration)
{
    if(decoration == null)
    {
        return {};
    }
    return this._decorationTable[decoration.id];
}

ns.Renderer.prototype._buildShapeParameters = function(shape)
{
    return $.extend(
        this._buildDecorationParameters(shape.decoration),
        {
            x: shape.x,
            y: shape.y,
            scaleX: this._scaleFactor,
            scaleY: this._scaleFactor,
            offsetX: this._offsetX,
            offsetY: this._offsetY,
            rotation: shape.rotation,
        }
    );
}

ns.Renderer.prototype.visitCircle = function(shape)
{
    var circle = new Kinetic.Circle($.extend(
        this._buildShapeParameters(shape),
        {
            radius: shape.radius,
        }
    ));

    circle.setZIndex(shape.z);

    this._layer.add(circle);
}


ns.Renderer.prototype.visitSquare = function(shape)
{
    var square = new Kinetic.Rect($.extend(
        this._buildShapeParameters(shape),
        {
            width: shape.side,
            height: shape.side,
        }
    ));

    square.setZIndex(shape.z);

    this._layer.add(square);
}

return ns;
}); 

