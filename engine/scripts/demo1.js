require(["kinetic"], function(Kinetic) {

var stage = new Kinetic.Stage({
    container: 'container',
    width: 578,
    height: 200,
});

var layer = new Kinetic.Layer();

var circle = new Kinetic.Circle({
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

// add the layer to the stage
stage.add(layer);

});
