/**
 * Plataforma Matematicon Educ.ar
 *
 * Developed by PressEnter.com.ar team
 *
 * @license magnet:?xt=urn:btih:1f739d935676111cfff4b4693e3816e664797050&dn=gpl-3.0.txt GPL-v3-or-Later
 */


define(['matematicon/render', 'matematicon/shapesDefinitionsRenderer', 'createjs'], function (render, shapesDefinitionsRenderer, createjs) {

var ns = {};

ns.ImageExporter = function(scale, decoration_table)
{
    this._scale = scale;
    this._decoration_table = decoration_table;
}

ns.ImageExporter.prototype.exportTo = function(drawing, canvas)
{
    var stage = new createjs.Stage(canvas);

    var background = new createjs.Shape();
    background.graphics.beginFill("#fff").drawRect(0, 0, 1050, 700);
    stage.addChild(background);

    var title = new createjs.Text("Objeto: " + drawing.title, "32px arial bold underline", "#000");
    title.x = 300;
    title.y = 2;
    stage.addChild(title);

    var renderer = new render.Renderer(stage, this._scale, this._decoration_table, false);
    renderer.addDrawing(drawing, 50, 8);
    renderer.render();

    var shapesDefRenderer = new shapesDefinitionsRenderer.Renderer(stage);
    shapesDefRenderer.render(drawing);
    stage.update();
}


return ns;
}); 

