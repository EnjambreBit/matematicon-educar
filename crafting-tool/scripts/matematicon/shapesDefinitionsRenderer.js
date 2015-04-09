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
 * Drawings Renderer
 *
 * @param interactive If false, shapes are static, else they are draggable.
 *
 * @param CreateJS stage
 */
ns.Renderer = function(stage)
{
    this._stage = stage;
    this._offsetX = 15;
    this._offsetY = 80;
    this._offsetYIncrement = 30;
}

ns.Renderer.prototype.render = function(drawing)
{
    drawing.visitShapes(this);
    this._stage.update();
}

ns.Renderer.prototype.putText = function(shapeType, propertiesStr)
{
    var text = new createjs.Text(shapeType+"\n"+propertiesStr, "14px arial", "#000");
    text.x = this._offsetX;
    text.y = this._offsetY;
    this._offsetY += this._offsetYIncrement;
    this._stage.addChild(text);
}

ns.Renderer.prototype.visitCircle = function(shape)
{
    this.putText("Circulo", "Radio: " + shape.radius + "u Radio");
}

ns.Renderer.prototype.visitSquare = function(shape)
{
    this.putText("Cuadrado", "Lado: " + shape.side + "u");
}

ns.Renderer.prototype.visitPolygon = function(shape)
{
    this.putText("Poligono regular", "Lados: " + shape.sides + " Lado: " + shape.side + "u");
}

ns.Renderer.prototype.visitEllipse = function(shape)
{
    this.putText("Elipse", "Radio mayor: " + shape.radius1 + "u Radio menor:" + shape.radius2 + "u");
}

ns.Renderer.prototype.visitRectangle = function(shape)
{
    this.putText("Rectángulo", "Base: " + shape.width + "u Altura:" + shape.height + "u");
}

ns.Renderer.prototype.visitTrapezoid = function(shape)
{
    this.putText("Trapecio", "Base menor: " + shape.base1 + "u Base mayor: " + shape.base2 + "u Altura:" + shape.height + "u");
}

ns.Renderer.prototype.visitTriangle = function(shape)
{
    this.putText("Triángulo", "Base: " + shape.base + "u Altura:" + shape.height + "u Ángulo: " + shape.angle + "°");
}

ns.Renderer.prototype.visitRhombus = function(shape)
{
    this.putText("Rombo:", "Diagonal menor: " + shape.diag2 + "u Diagonal mayor:" + shape.diag1 + "u");
}

return ns;
}); 

