/**
 * Plataforma Matematicon Educ.ar
 *
 * Developed by PressEnter.com.ar team
 *
 * @license magnet:?xt=urn:btih:1f739d935676111cfff4b4693e3816e664797050&dn=gpl-3.0.txt GPL-v3-or-Later
 */


define(['observer'], function (observer) {

var ns = {};

ns.Drawing = function()
{
    this.title = '';
    this._shapes = new Array();
    this._subject = new observer.Subject();
}

ns.Drawing.prototype.addObserver = function(observer)
{
    this._subject.observe(observer);    
}

ns.Drawing.prototype.addShape = function(shape)
{
    this._shapes.push(shape);
    this._subject.notify(this, "newShape", shape);
}

ns.Drawing.prototype.removeShape = function(shape)
{
    var i = this._shapes.indexOf(shape);
    if(i != -1)
    {
	    this._shapes.splice(i, 1);
    }
}

ns.Drawing.prototype.visitShapes = function(visitor)
{
    this._shapes.forEach(function(shape) { shape.visit(visitor); });
}

ns.Shape = function(x, y)
{
    this.x = x;
    this.y = y;
    this.z = 0;
    this.rotation = 0;
    this.decoration = null;
}

// Circle
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

// Square
ns.Square = function(x, y, side)
{
    ns.Shape.call(this, x, y);
    this.side = side;
}

ns.Square.prototype = Object.create(ns.Shape.prototype);
ns.Square.prototype.constructor = ns.Square;
ns.Square.prototype.visit = function(visitor)
{
    visitor.visitSquare(this);
}

// Rect
ns.Rect = function(x, y, width, height)
{
    ns.Shape.call(this, x, y);
    this.width = width;
    this.height = height;
}

ns.Rect.prototype = Object.create(ns.Shape.prototype);
ns.Rect.prototype.constructor = ns.Rect;
ns.Rect.prototype.visit = function(visitor)
{
    visitor.visitRect(this);
}

ns.Decoration = function(id, description)
{
    this.id = id;
    this.description = description;
}

return ns;
}); 

