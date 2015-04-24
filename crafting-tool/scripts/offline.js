'use strict';

define([],
function() {

/**
 * Returns true if offline (running in nw)
 */
return function() {
    try
    {
        return process != undefined;
    }
    catch(e)
    {
    }
    return false;
};

});
