requirejs.config({
    baseUrl: 'scripts',
    paths: {
        app: '../'
    },
    shim: {
        createjs : {exports: 'createjs'}
    }
});
