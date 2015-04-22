'use strict';

define(["jquery"],
function(jq) {
    
var OnlineObjectsPersistor = function()
{
}

OnlineObjectsPersistor.prototype.save = function(drawing, thumb, callback)
{
    jq.ajax({
        url: "../my_objects/save",
        type: "POST",
        data: {
            json: JSON.stringify(drawing),
            thumb: thumb,
            id: drawing.id,
            title: drawing.title,
            scene_id: drawing.scene_id
        }
    }).done(function(msg)
    {
        drawing.id = msg;
        callback();
    });
}

OnlineObjectsPersistor.prototype.remove = function(drawing_id, callback)
{
    jq.ajax({
        url: "../my_objects/"+drawing_id+"/delete"
    }).done(function(resp)
    {
        callback();
    });
}

OnlineObjectsPersistor.prototype.list = function(scene_id, page, callback)
{
    jq.ajax({
        url: "../my_objects/",
        dataType: 'json',
        data: {
            page: page,
            scene_id: scene_id
        }
    }).done(function(resp)
    {
        callback(resp);
    });
}

OnlineObjectsPersistor.prototype.loadDrawing = function(id, callback)
{
    jq.ajax({
        url: "../my_objects/"+id,
        dataType: 'json'
    }).done(function(resp)
    {
        callback(resp);
    });
}

OnlineObjectsPersistor.prototype.insertDrawing = function(drawing, callback)
{
    jq.ajax({
        url: "../my_objects/insert",
        type: "POST",
        data: {
            id: drawing.id,
        }
    }).done(function(msg)
    {
        callback();
    });
}

return OnlineObjectsPersistor;
});
