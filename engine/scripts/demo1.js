require(["matematicon/drawing", "matematicon/render", "kinetic"], function(drawing, render, Kinetic) {

var stage = new Kinetic.Stage({
    container: 'container',
    width: 578,
    height: 200,
});

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
console.log(draw);

var renderer = new render.Renderer(layer, 5);
renderer.render(draw, -10, -10);

// add the layer to the stage
stage.add(layer);

});
