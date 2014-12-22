/**
 * Plataforma Matematicon Educ.ar
 *
 * Developed by PressEnter.com.ar team
 *
 * @license magnet:?xt=urn:btih:1f739d935676111cfff4b4693e3816e664797050&dn=gpl-3.0.txt GPL-v3-or-Later
 */


define(['observer', 'jquery'], function (observer, jq) {

var ns = {};

ns.Drawing = function()
{
    this.title = '';
    this.shapes = new Array();
    this._subject = new observer.Subject();
    this._currIndex = 0;
    this.scene_id = null;
    this.zone = null;
    this.id = null;
}

ns.unserialize = function(id, obj)
{
    d = new ns.Drawing();
    d.id = id;
    d.title = obj.title;
    d.zone = obj.zone;
    d.scene_id = obj.scene_id;
    
    jq.each(obj.shapes, function(index) {
        var shape = obj.shapes[index];
        var s = null;
        switch(shape.type)
        {
            case "square":
                s = new ns.Square(shape.x, shape.y, shape.side);
                break;
            case "polygon":
                s = new ns.Polygon(shape.x, shape.y, shape.sides, shape.side);
                break;
            case "ellipse":
                s = new ns.Ellipse(shape.x, shape.y, shape.width, shape.height);
                break;
            case "rectangle":
                s = new ns.Rectangle(shape.x, shape.y, shape.width, shape.height);
                break;
            case "circle":
                s = new ns.Circle(shape.x, shape.y, shape.radius);
                break;
            case "trapezoid":
                s = new ns.Trapezoid(shape.x, shape.y, shape.base1, shape.base2, shape.height);
                break;
            case "triangle":
                s = new ns.Triangle(shape.x, shape.y, shape.base,shape.height, shape.angle);
                break;
            case "rhombus":
                s = new ns.Rhombus(shape.x, shape.y, shape.width, shape.height);
                break;
        }
        if(s == null)
        {
            console.log("Cannot unserialize", shape);
        }
        else
        {
            s.rotation = shape.rotation;
            s.decoration_id = shape.decoration_id;
        }

        d.addShape(s);
    });
    return d;
}

ns.Drawing.prototype.toJSON = function()
{
    return {
        id: this.id, 
        title: this.title,
        scene_id: this.scene_id,
        zone: this.zone,
        shapes: this.shapes
    };
}

ns.Drawing.prototype.addObserver = function(observer)
{
    this._subject.observe(observer);    
}

ns.Drawing.prototype.addShape = function(shape)
{
    shape.index = this._currIndex;
    this._currIndex++;
    this.shapes.push(shape);
    this._subject.notify(this, "newShape", shape);
}

ns.Drawing.prototype.restoreShapeInOrder = function(shape, order)
{
    this.shapes.splice(order, 0, shape);
    this._subject.notify(this, "newShape", shape);
}

ns.Drawing.prototype.sendToBack = function(shape)
{
    var i = this.shapes.indexOf(shape);
    if(i != -1)
    {
	    this.shapes.splice(i, 1);
        this.shapes.unshift(shape);
    }
    this._subject.notify(this, "toBack", shape);
}

ns.Drawing.prototype.bringToFront = function(shape)
{
    var i = this.shapes.indexOf(shape);
    if(i != -1)
    {
	    this.shapes.splice(i, 1);
        this.shapes.push(shape);
    }
    this._subject.notify(this, "toBack", shape);
}

ns.Drawing.prototype.removeShape = function(shape)
{
    var i = this.shapes.indexOf(shape);
    if(i != -1)
    {
	    this.shapes.splice(i, 1);
    }
    this._subject.notify(this, "deletedShape", shape);
}

ns.Drawing.prototype.updateShape = function(shape)
{
    this._subject.notify(this, "updatedShape", shape);
}

ns.Drawing.prototype.visitShapes = function(visitor)
{
    this.shapes.forEach(function(shape) { shape.visit(visitor); });
}

ns.Drawing.prototype.getOrder = function(shape)
{
    for(var i=0; i < this.shapes.length; i++)
    {
        if(this.shapes[i].index == shape.index)
            return i;
    }
    return null;
}

ns.Drawing.prototype.getShapeByIndex = function(index)
{
    for(var i=0; i < this.shapes.length; i++)
    {
        if(this.shapes[i].index == index)
            return this.shapes[i];
    }
    return null;
}

ns.Shape = function(type, x, y)
{
    this.type = type;
    this.x = x;
    this.y = y;
    this.z = 0;
    this.rotation = 0;
    this.decoration_id = null;
}

ns.Shape.prototype.saveState = function()
{
    return {
        x: this.x,
        y: this.y,
        rotation: this.rotation,
        decoration_id: this.decoration_id,
    };
}

ns.Shape.prototype.restoreState = function(state)
{
    this.x = state.x;
    this.y = state.y;
    this.rotation = state.rotation;
    this.decoration_id = state.decoration_id;
}
// Circle
ns.Circle = function(x, y, radius)
{
    ns.Shape.call(this, "circle", x, y);
    this.radius = radius;
}

ns.Circle.prototype = Object.create(ns.Shape.prototype);
ns.Circle.prototype.constructor = ns.Circle;
ns.Circle.prototype.visit = function(visitor)
{
    visitor.visitCircle(this);
}

ns.Circle.prototype.saveState = function()
{
    return {
        radius: this.radius,
        basic: ns.Shape.prototype.saveState.apply(this)
    };
};

ns.Circle.prototype.restoreState = function(state)
{
    this.radius = state.radius;
    ns.Shape.prototype.restoreState.apply(this, new Array(state.basic));
};

// Square
ns.Square = function(x, y, side)
{
    ns.Shape.call(this, "square", x, y);
    this.side = side;
}

ns.Square.prototype = Object.create(ns.Shape.prototype);
ns.Square.prototype.constructor = ns.Square;
ns.Square.prototype.visit = function(visitor)
{
    return visitor.visitSquare(this);
}

ns.Square.prototype.saveState = function()
{
    return {
        side: this.side,
        basic: ns.Shape.prototype.saveState.apply(this)
    };
};

ns.Square.prototype.restoreState = function(state)
{
    this.side = state.side;
    ns.Shape.prototype.restoreState.apply(this, new Array(state.basic));
};

// Polygon
ns.Polygon = function(x, y, sides, side)
{
    ns.Shape.call(this, "polygon", x, y);
    this.sides = sides;
    this.side = side;
}

ns.Polygon.prototype = Object.create(ns.Shape.prototype);
ns.Polygon.prototype.constructor = ns.Polygon;
ns.Polygon.prototype.visit = function(visitor)
{
    visitor.visitPolygon(this);
}

ns.Polygon.prototype.saveState = function()
{
    return {
        sides: this.sides,
        side: this.side,
        basic: ns.Shape.prototype.saveState.apply(this)
    };
};

ns.Polygon.prototype.restoreState = function(state)
{
    this.sides = state.sides;
    this.side = state.side;
    ns.Shape.prototype.restoreState.apply(this, new Array(state.basic));
};

// Ellipse
ns.Ellipse = function(x, y, width, height)
{
    ns.Shape.call(this, "ellipse", x, y);
    this.width = width;
    this.height = height;
}

ns.Ellipse.prototype = Object.create(ns.Shape.prototype);
ns.Ellipse.prototype.constructor = ns.Ellipse;
ns.Ellipse.prototype.visit = function(visitor)
{
    visitor.visitEllipse(this);
}

ns.Ellipse.prototype.saveState = function()
{
    return {
        width: this.width,
        height: this.height,
        basic: ns.Shape.prototype.saveState.apply(this)
    };
};

ns.Ellipse.prototype.restoreState = function(state)
{
    this.width = state.width;
    this.height = state.height;
    ns.Shape.prototype.restoreState.apply(this, new Array(state.basic));
};

// Rect
ns.Rectangle = function(x, y, width, height)
{
    ns.Shape.call(this, "rectangle", x, y);
    this.width = width;
    this.height = height;
}

ns.Rectangle.prototype = Object.create(ns.Shape.prototype);
ns.Rectangle.prototype.constructor = ns.Rectangle;
ns.Rectangle.prototype.visit = function(visitor)
{
    visitor.visitRectangle(this);
}

ns.Rectangle.prototype.saveState = function()
{
    return {
        width: this.width,
        height: this.height,
        basic: ns.Shape.prototype.saveState.apply(this)
    };
};

ns.Rectangle.prototype.restoreState = function(state)
{
    this.width = state.width;
    this.height = state.height;
    ns.Shape.prototype.restoreState.apply(this, new Array(state.basic));
};

// Trapezoid (isosceles)
ns.Trapezoid = function(x, y, base1, base2, height)
{
    ns.Shape.call(this, "trapezoid", x, y);
    this.base1 = base1;
    this.base2 = base2;
    this.height = height;
}

ns.Trapezoid.prototype = Object.create(ns.Shape.prototype);
ns.Trapezoid.prototype.constructor = ns.Trapezoid;
ns.Trapezoid.prototype.visit = function(visitor)
{
    visitor.visitTrapezoid(this);
}

ns.Trapezoid.prototype.saveState = function()
{
    return {
        base1: this.base1,
        base2: this.base2,
        height: this.height,
        basic: ns.Shape.prototype.saveState.apply(this)
    };
};

ns.Trapezoid.prototype.restoreState = function(state)
{
    this.base1 = state.base1;
    this.base2 = state.base2;
    this.height = state.height;
    ns.Shape.prototype.restoreState.apply(this, new Array(state.basic));
};

// Triangle
ns.Triangle = function(x, y, base, height, angle)
{
    ns.Shape.call(this, "triangle", x, y);
    this.base = base;
    this.height = height;
    this.angle = angle;
}

ns.Triangle.prototype = Object.create(ns.Shape.prototype);
ns.Triangle.prototype.constructor = ns.Triangle;
ns.Triangle.prototype.visit = function(visitor)
{
    visitor.visitTriangle(this);
}

ns.Triangle.prototype.saveState = function()
{
    return {
        base: this.base,
        height: this.height,
        angle: this.angle,
        basic: ns.Shape.prototype.saveState.apply(this)
    };
};

ns.Triangle.prototype.restoreState = function(state)
{
    this.base = state.base;
    this.height = state.height;
    this.angle = state.angle;
    ns.Shape.prototype.restoreState.apply(this, new Array(state.basic));
};

// Rhombus
ns.Rhombus = function(x, y, width, height)
{
    ns.Shape.call(this, "rhombus", x, y);
    this.width = width;
    this.height = height;
}

ns.Rhombus.prototype = Object.create(ns.Shape.prototype);
ns.Rhombus.prototype.constructor = ns.Rhombus;
ns.Rhombus.prototype.visit = function(visitor)
{
    visitor.visitRhombus(this);
}

ns.Rhombus.prototype.saveState = function()
{
    return {
        width: this.width,
        height: this.height,
        basic: ns.Shape.prototype.saveState.apply(this)
    };
};

ns.Rhombus.prototype.restoreState = function(state)
{
    this.side = state.side;
    ns.Shape.prototype.restoreState.apply(this, new Array(state.basic));
};


return ns;
}); 

