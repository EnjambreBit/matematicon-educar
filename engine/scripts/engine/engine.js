/**
 * Plataforma Matematicon Educ.ar
 *
 * Developed by PressEnter.com.ar team
 *
 * @license magnet:?xt=urn:btih:1f739d935676111cfff4b4693e3816e664797050&dn=gpl-3.0.txt GPL-v3-or-Later
 */

// TODO: Observer en Drawing y Figure
// TODO: Renderers
// TODO: requirejs

define(function () {

var ns = {};

ns.Drawing = function()
{
    this.title = '';
    this.shapes = new Array();
}

ns.Drawing.prototype.addShape = function(shape)
{
    this.shapes.push(shape);
}

ns.Drawing.prototype.removeShape = function(shape)
{
    var i = this.shapes.indexOf(shape);
    if(i != -1)
    {
	    this.shapes.splice(i, 1);
    }
}

ns.Shape = function(x, y)
{
    this.x = x;
    this.y = y;
    this.z = 0;
    this.rotation = 0;
    this.decoration = null;
}

ns.Circle = function(x, y, radius)
{
    ns.Shape.call(this, x, y);
    this.radius = radius;
}

ns.Circle.prototype = Object.create(ns.Shape.prototype);
ns.Circle.prototype.constructor = ns.Circle;
ns.Circle.prototype.visit = function(visitor)
{
    visitor.visitCircle(this);
}

return ns;
}); 

