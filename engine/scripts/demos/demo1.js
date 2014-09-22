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
decoration_table["bricks"] = {type: 'pattern', fill: queue.getResult("bricks")};


var draw = new drawing.Drawing();
var circle = new drawing.Circle(50, 20, 5);
circle.decoration = new drawing.Decoration("azul", "");

var square = new drawing.Square(22,0,30);
square.decoration = new drawing.Decoration("bricks", "");
square.rotation = 23;
draw.addShape(square);
draw.addShape(circle);

var renderer = new render.Renderer(stage, 5, decoration_table);
renderer.render(draw, 10, 10);
stage.update();
}, true);

});
