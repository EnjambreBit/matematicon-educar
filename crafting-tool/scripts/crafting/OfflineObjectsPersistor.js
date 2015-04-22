'use strict';

define([],
function() {
    
var fs = require('fs');

function getUserHome() {
  return process.env.HOME || process.env.HOMEPATH || process.env.USERPROFILE;
}

var OfflineObjectsPersistor = function()
{
    this._basePath = getUserHome() + "/.matematicon";
    this._indexPath = this._basePath + "/index.json";
    this._configureRepository();
}

OfflineObjectsPersistor.prototype._configureRepository = function()
{
    if(!fs.existsSync(this._basePath))
    {
        fs.mkdirSync(this._basePath);
    }

    if(!fs.existsSync(this._indexPath))
    {
        var index = {
            nextId: 1,
            sceneObjects: {}
        };
        
        var fd = fs.openSync(this._indexPath, "w");
        fs.writeSync(fd, JSON.stringify(index));
        fs.closeSync(fd);
    }
}

OfflineObjectsPersistor.prototype._getIndex = function()
{
    var data = fs.readFileSync(this._indexPath, {encoding: 'utf8'});
    return JSON.parse(data);
}

OfflineObjectsPersistor.prototype.save = function(drawing, thumb, callback)
{
    var index = this._getIndex();
    if(drawing.id == null)
    {
        drawing.id = index.nextId;
        index.nextId++;
    }

    var fd = fs.openSync(this._basePath + "/" + drawing.id + ".json", "w");
    fs.writeSync(fd, JSON.stringify(drawing));
    fs.closeSync(fd);

    var fd = fs.openSync(this._basePath + "/" + drawing.id + ".png", "w");
    fs.writeSync(fd, thumb);
    fs.closeSync(fd);

    if(index.sceneObjects[drawing.scene_id] == undefined)
    {
        index.sceneObjects[drawing.scene_id] = [];
    }
    
    for(var sceneIdx in Object.keys(index.sceneObjects))
    {
        var scene = Object.keys(index.sceneObjects)[sceneIdx];
        if(scene != drawing.scene_id)
        {
            var i = index.sceneObjects[scene].indexOf(drawing.id);
            if(i != -1)
            {
	            index.sceneObjects[scene].splice(i, 1);
            }
        }
        else
        {
            var i = index.sceneObjects[scene].indexOf(drawing.id);
            if(i == -1)
            {
	            index.sceneObjects[scene].push(drawing.id);
            }
        }
    }
    this._saveIndex(index);
    callback();
}

OfflineObjectsPersistor.prototype._saveIndex = function(index)
{
    var fd = fs.openSync(this._indexPath, "w");
    fs.writeSync(fd, JSON.stringify(index));
    fs.closeSync(fd);
}

OfflineObjectsPersistor.prototype.remove = function(drawing_id, callback)
{
    var index = this._getIndex();

    fs.unlinkSync(this._basePath + "/" + drawing_id + ".json");
    fs.unlinkSync(this._basePath + "/" + drawing_id + ".png");

    for(var sceneIdx in Object.keys(index.sceneObjects))
    {
        var scene = Object.keys(index.sceneObjects)[sceneIdx];
        var i = index.sceneObjects[scene].indexOf(drawing_id);
        if(i != -1)
        {
	        index.sceneObjects[scene].splice(i, 1);
        }
    }
    this._saveIndex(index);
    callback();
}

OfflineObjectsPersistor.prototype.list = function(scene_id, page, callback)
{
    var index = this._getIndex();

    if(index.sceneObjects[scene_id] == undefined)
        return [];

    var result = [];
    var offset = 3 * page;
    var count = 3;
    
    var indexes = index.sceneObjects[scene_id].slice(offset, offset + count);
    for(var k in indexes)
    {
        var idx = indexes[k];
        result.push({id: idx, title: this._getDrawing(idx).title, thumb: this._getThumb(idx)});
    }
    callback(result);
}

OfflineObjectsPersistor.prototype._getDrawing = function(id)
{
    var data = fs.readFileSync(this._basePath + "/" + id + ".json", {encoding: 'utf8'});
    return JSON.parse(data);
}

OfflineObjectsPersistor.prototype.loadDrawing = function(id, callback)
{
    callback(this._getDrawing(id));
}

OfflineObjectsPersistor.prototype.loadDrawingSync = function(id)
{
    return this._getDrawing(id);
}

OfflineObjectsPersistor.prototype._getThumb = function(id)
{
    var data = fs.readFileSync(this._basePath + "/" + id + ".png", {encoding: 'utf8'});
    return data;
}

OfflineObjectsPersistor.prototype.insertDrawing = function(drawing, callback)
{
    callback();
}

OfflineObjectsPersistor.prototype.listForScene = function(scene_id)
{
    var index = this._getIndex();
    if(index.sceneObjects[scene_id] == undefined)
        return [];

    return index.sceneObjects[scene_id];
}


return OfflineObjectsPersistor;
});
