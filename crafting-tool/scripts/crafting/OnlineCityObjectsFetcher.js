'use strict';

define(['jquery', 'createjs'],
function(jq, createjs) {
    
var OnlineCityObjectsFetcher = function()
{
}

OnlineCityObjectsFetcher.prototype.fetchObjectsCityForDrawing = function(drawing, callback)
{
    jq.ajax({
        url: "../city/" + drawing.id  + "/create",
        type: "GET",
        dataType: 'json'
    }).done(function(resp)
    {
        console.log(resp);
        callback(resp);
    });
}

OnlineCityObjectsFetcher.prototype.getImage = function(id)
{
    return new createjs.Bitmap("../city/" + id + "/image");
}

return OnlineCityObjectsFetcher;
});
