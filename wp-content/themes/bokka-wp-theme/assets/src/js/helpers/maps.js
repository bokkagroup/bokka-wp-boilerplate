window.loadMapsAPI = function(callback){
    //------load maps-API
    window.mapsCallback = callback
    window.addEventListener('load',function(){
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3&callback=mapsCallback';
        document.body.appendChild(script);
    });
}
