

getSocket = function(url) {
    
    if(!url)
        url = "http://laplateformedelarenovation.fr/index.php/fr/mon-espace";


    var socket = null;

    if (Boolean(url)) {
        socket = new easyXDM.Socket({
            remote: url,
            onReady: function() {

            },
            onMessage: function(message, origin) {
              

            }
        });
    }
    return socket;

};

