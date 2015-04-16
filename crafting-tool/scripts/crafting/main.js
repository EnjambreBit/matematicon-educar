/**
 * Plataforma Matematicon Educ.ar
 *
 * Developed by PressEnter.com.ar team
 *
 * @license magnet:?xt=urn:btih:1f739d935676111cfff4b4693e3816e664797050&dn=gpl-3.0.txt GPL-v3-or-Later
 */
'use strict';

requirejs(["require",
    "crafting/MainCtrl",
    "crafting/ViewSceneCtrl",
    "crafting/SceneSelectCtrl",
    "crafting/GalleryCtrl",
    "crafting/MyObjectsCtrl",
    "crafting/CraftingToolCtrl",
    "crafting/ViewCityCtrl",
    "jquery",
    "angular",
    "createjs",
    "offline"],
function(require,
    MainCtrl,
    ViewSceneCtrl,
    SceneSelectCtrl,
    GalleryCtrl,
    MyObjectsCtrl,
    CraftingToolCtrl,
    ViewCityCtrl,
    jq, ng, createjs,
    offline) {

var decoration_table = null;
var scenes_list = null;
var gallery_dict = null;

var craftingApp =  ng.module('craftingApp', [], function($compileProvider) {
    $compileProvider.imgSrcSanitizationWhitelist(/^\s*(https?|ftp|file|chrome-extension|app):|data:image\//);
    $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|file|chrome-extension|app):/);
});

craftingApp.factory('DecorationTable', function () {
    return decoration_table;
});

craftingApp.factory('ScenesList', function () {
    return scenes_list;
});

craftingApp.factory('Gallery', function () {
    return gallery_dict;
});

craftingApp.factory('BackgroundFactory', function () {
    return {find : function(scene_id, zone)
                {
                    var asset = queue.getResult(scene_id + "_" + zone[0] + "-" + zone[1]);
                    var img = new createjs.Bitmap("assets/backgrounds/" + scene_id + "/" + zone[0] + "-" + zone[1] + ".png");
                    img.alpha = 0.7;
                    return img;
                }
            };
});

craftingApp.factory('Offline', function () {
    return offline();
});



// Create decoration table with associated assets
function prepareDecorationTable(table, assets)
{
    jq.each(table, function(item) {
        if(table[item].type == "pattern")
        {
            table[item].fill = assets.getResult(table[item].fill_id);
        }
    });
}

function prepareScenesList(table, assets)
{
    jq.each(table, function(item) {
        table[item].background = assets.getResult(table[item].id);
        table[item].full_image = assets.getResult(table[item].id+"_full");
    });
}

// Load assets
var queue = new createjs.LoadQueue(true);
queue.on("complete", function() {
    // Bootstrap angular app after loading assets
    decoration_table = this.getResult("decoration_table");
    scenes_list = this.getResult("scenes");
    gallery_dict = this.getResult("gallery");
    prepareDecorationTable(decoration_table, this);
    prepareScenesList(scenes_list, this);
    
    // Initialize App
    var persistorClass = offline() ? 'crafting/OfflineObjectsPersistor' : 'crafting/OnlineObjectsPersistor';
    requirejs(['domReady!', persistorClass], function (document, ObjectsPersistor) {
        var objectsPersistor = new ObjectsPersistor();
        console.log(objectsPersistor);
        craftingApp.factory('ObjectsPersistor', function () { return objectsPersistor; } );

        craftingApp.controller('MainCtrl', MainCtrl);
        craftingApp.controller('ViewSceneCtrl', ViewSceneCtrl);
        craftingApp.controller('SceneSelectCtrl', SceneSelectCtrl);
        craftingApp.controller('GalleryCtrl', GalleryCtrl);
        craftingApp.controller('MyObjectsCtrl', MyObjectsCtrl);
        craftingApp.controller('CraftingToolCtrl', CraftingToolCtrl);
        craftingApp.controller('ViewCityCtrl', ViewCityCtrl);
        ng.bootstrap(document, ['craftingApp']);
        jq("#main-container").show();
    });
});

queue.loadManifest({id: "manifest", src:"assets/manifest.json", type:"manifest"});
});
