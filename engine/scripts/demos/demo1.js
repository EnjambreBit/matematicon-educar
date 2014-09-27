require(["matematicon/drawing", "matematicon/render", "createjs"], function(drawing, render, createjs) {

// Load assets
var queue = new createjs.LoadQueue(false); // TODO: deberia ser true
queue.loadFile({id:"bricks", src:"img/bricks.jpg"});
queue.on("complete", function() {
//Create a stage by getting a reference to the canvas
stage = new createjs.Stage("demoCanvas");
var decoration_table = Array();
decoration_table["rojo"] = {type: 'color', fill: 'red'};
decoration_table["azul"] = {type: 'color', fill: 'blue'};
decoration_table["gris"] = {type: 'color', fill: 'gray'};
decoration_table["negro"] = {type: 'color', fill: 'black'};
decoration_table["bricks"] = {type: 'pattern', fill: queue.getResult("bricks")};


var draw = new drawing.Drawing();
var circle = new drawing.Circle(20, 60, 10);
circle.decoration = new drawing.Decoration("negro", "");
draw.addShape(circle);

var circle = new drawing.Circle(70, 62, 8);
circle.decoration = new drawing.Decoration("negro", "");
draw.addShape(circle);

var circle = new drawing.Circle(20, 60, 7);
circle.decoration = new drawing.Decoration("gris", "");
draw.addShape(circle);

var circle = new drawing.Circle(70, 62, 5);
circle.decoration = new drawing.Decoration("gris", "");
draw.addShape(circle);

var rect = new drawing.Rect(5,10,1,40);
rect.decoration = new drawing.Decoration("bricks", "");
rect.rotation=-20;
draw.addShape(rect);

var circle = new drawing.Circle(5, 10, 2);
circle.decoration = new drawing.Decoration("rojo", "");
draw.addShape(circle);

var rect = new drawing.Rect(15,47,60,10);
rect.decoration = new drawing.Decoration("bricks", "");
draw.addShape(rect);

var square = new drawing.Square(35,17,30);
square.decoration = new drawing.Decoration("bricks", "");
square.rotation = 15;
draw.addShape(square);

var renderer = new render.Renderer(stage, 5, decoration_table);
renderer.render(draw, 10, 10);
stage.update();
}, true);

});
