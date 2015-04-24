'use strict';

define([],
function() {
    
var OfflineCityObjectsFetcher = function(persistor)
{
    this._persistor = persistor;
}

// TODO: sacar esto a un archivo en assets
var _gallery = {
    scene_1: [
        {id: 'a1', zone: [2,1], title: 'Pez', thumb: 'app://matematicon/assets/gallery/1_obj_acu.png'},
        {id: 'a2', zone: [13,3], title: 'Cangrejo', thumb: 'app://matematicon/assets/gallery/2_obj_acu.png'},
// se ve feo        {id: 'a3', zone: [10,1], title: 'Velero', thumb: 'app://matematicon/assets/gallery/3_obj_acu.png'},
        {id: 'a4', zone: [16,1], title: 'Medusa', thumb: 'app://matematicon/assets/gallery/4_obj_acu.png'},
        {id: 'a5', zone: [1,2], title: 'Submarino', thumb: 'app://matematicon/assets/gallery/5_obj_acu.png'},
        {id: 'a6', zone: [6,3], title: 'Tesoro', thumb: 'app://matematicon/assets/gallery/6_obj_acu.png'}
    ],
    scene_2: [
        {id: 'r1', zone: [5,3], title: 'Tractor', thumb: 'app://matematicon/assets/gallery/1_obj_rur.png'},
        {id: 'r2', zone: [2,2], title: 'Oveja', thumb: 'app://matematicon/assets/gallery/2_obj_rur.png'},
        {id: 'r3', zone: [5,2], title: 'Vaca', thumb: 'app://matematicon/assets/gallery/3_obj_rur.png'},
        {id: 'r4', zone: [14,2], title: 'Chanchito', thumb: 'app://matematicon/assets/gallery/4_obj_rur.png'},
        {id: 'r5', zone: [11,2], title: 'Granero', thumb: 'app://matematicon/assets/gallery/5_obj_rur.png'},
        {id: 'r6', zone: [10,2], title: 'Huerta', thumb: 'app://matematicon/assets/gallery/6_obj_rur.png'}
    ],
    scene_3: [
        {id: 'u1', zone: [8,2], title: 'Auto', thumb: 'app://matematicon/assets/gallery/1_obj_urb.png'},
        {id: 'u2', zone: [2,2], title: 'Semáforo', thumb: 'app://matematicon/assets/gallery/2_obj_urb.png'},
        {id: 'u3', zone: [5,2], title: 'Árboles', thumb: 'app://matematicon/assets/gallery/3_obj_urb.png'},
        {id: 'u4', zone: [12,2], title: 'Luces', thumb: 'app://matematicon/assets/gallery/4_obj_urb.png'},
        {id: 'u5', zone: [12,3], title: 'Fábrica', thumb: 'app://matematicon/assets/gallery/5_obj_urb.png'},
        {id: 'u6', zone: [11,3], title: 'Heladeria', thumb: 'app://matematicon/assets/gallery/6_obj_urb.png'}
    ]
};

OfflineCityObjectsFetcher.prototype.fetchObjectsCityForDrawing = function(drawing, callback)
{
    var result = [{id: drawing.id, zone: drawing.zone, title: drawing.title}];
    var objects = this._persistor.listForScene(drawing.scene_id);

    for(var i=0; i < 20; i++)
    {
        var idx = Math.floor(Math.random() * objects.length);
        if(idx >= 0 && idx < objects.length)
        {
            var obj = this._persistor.loadDrawingSync(objects[idx]);
            result.push({
                id: obj.id,
                zone: obj.zone,
                title: obj.title,
                user: 'Usuario'
            });
        }
        objects.splice(idx, 1);
    }

    var objects = _gallery[drawing.scene_id].concat();
    for(var i = result.length; i < 20; i++)
    {
        // Agregar de la galeria
        var idx = Math.floor(Math.random() * objects.length);
        if(idx >= 0 && idx < objects.length)
        {
            var obj = objects[idx];
            result.push({
                id: obj.id,
                zone: obj.zone,
                title: obj.title,
                user: 'Galería'
            });
        }
        objects.splice(idx, 1);
    }

    callback(result);
}

OfflineCityObjectsFetcher.prototype.getImage = function(id)
{
    if(isNaN(id))
    {   // Ver si es de la galeria
        for(var sceneIdx in Object.keys(_gallery))
        {
            var scene = Object.keys(_gallery)[sceneIdx];
            var objects = _gallery[scene];
            for(var i = 0; i < objects.length; i++)
            {
                if(objects[i].id == id)
                {
                    return new createjs.Bitmap(objects[i].thumb);
                }
            }
        }
    }
    else
    {
        return new createjs.Bitmap(this._persistor._getThumb(id));
    }
}

return OfflineCityObjectsFetcher;
});
