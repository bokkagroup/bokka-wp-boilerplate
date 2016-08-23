window.loadMapsAPI = function(callback){
    //------load maps-API
    if (typeof google == "undefined") {
        window.mapsCallback = callback
        window.addEventListener('load',function(){
            var script = document.createElement('script')
            script.type = 'text/javascript'
            script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAcTFXfdY2MgwZqfpDxD_FQ-jcm6HepHjU&v=3&callback=mapsCallback'
            document.body.appendChild(script)
        })
    } else {
        callback()
    }
}
