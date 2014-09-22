require(["matematicon/drawing", "matematicon/render", "kinetic"], function(drawing, render, Kinetic) {

var stage = new Kinetic.Stage({
    container: 'container',
    width: 578,
    height: 200,
});

var decoration_table = Array();
decoration_table["rojo"] = {fill: 'red'};
decoration_table["azul"] = {fill: 'blue'};
decoration_table["bricks"] = {fill:'black'};

var layer = new Kinetic.Layer();

/*var circle = new Kinetic.Circle({
    x: 200,
    y: 100,
    radius: 70,
    fill: 'red',
    stroke: 'black',
    strokeWidth: 4,
    draggable: true,
});

circle.on('dragend', function() { console.log(circle); });

// add the shape to the layer
layer.add(circle);
*/

var draw = new drawing.Drawing();
var circle = new drawing.Circle(10, 20, 5);
circle.decoration = new drawing.Decoration("azul", "");
draw.addShape(circle);

var square = new drawing.Square(40,40,10);
square.decoration = new drawing.Decoration("bricks", "");
draw.addShape(square);

var renderer = new render.Renderer(layer, 5, decoration_table);
renderer.render(draw, -10, -10);

// add the layer to the stage
stage.add(layer);

});
