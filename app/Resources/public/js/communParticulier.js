var deleteRecordParticulier = function(element, element_identifier, prefix, url, store){
   
    var grid = Ext.getCmp('grid-'+prefix);
    var sm = grid.getSelectionModel();

    if (sm.hasSelection()){

        var selection = grid.getView().getSelectionModel().getSelection()[0]; 
        
        Ext.Msg.show({
            title:'Suppression '+element,
            msg: "Êtes-vous sûr de vouloir supprimer "+element_identifier ,
            buttons: Ext.Msg.YESNO,
            icon: Ext.MessageBox.WARNING,
            animateTarget: 'btn-supp'+prefix,
            fn: function(btn, text){
                if (btn == 'yes'){
                    var id = 'id-'+prefix;
                    Ext.Ajax.request({
                        params: {                                                            
                            'form[_token]' : selection.data._token,
                            'form[id]' : selection.getId()
                        },
                        url: url ,
                        success: function (response ,form ){
                            var jsonData = Ext.decode(response.responseText);
                            
                          
                          
                            //  successMsg('Suppression','la ligne a été supprimée avec succès');
                            if(!store)
                                store = grid.getStore();
                            // store.setLoaded(false);
                            store.load();                                                                                
               
                        },
                        failure: function (){
                        
                            Ext.Msg.show({
                                title:'Vous ne pouvez pas supprimer la ligne!',
                                msg: prefix+" est affecté à une autre table !" ,
                                buttons: Ext.Msg.YES,
                                icon: Ext.MessageBox.ERROR
                            });
                     

                        }


                    });


                }
            }
        });
    };

}
Ext.onReady(function() {
    if(Ext.get('etat-projet'))
    {
        var etatProjet = Ext.get('etat-projet').getValue();
        if(etatProjet == 1)
        {
            Ext.get('menu-nouveau-projet').addCls('active');
        }else{
            Ext.get('menu-projets').addCls('active');
        }
    }
});

/*function ubdateProgressBarParticilier()
{
var avancementLevel = parseFloat($('#avancement_level').val());
avancementLevel = avancementLevel + 0.014;
Ext.getCmp('progressbar').updateProgress(parseFloat(avancementLevel));
var avancementPourcentage = parseFloat($('#avancement_pourcentage').val());
var number = (avancementLevel*100)/0.42 ;
avancementPourcentage = Math.round(number);
Ext.getCmp('progressbar').updateText("Avancement "+avancementPourcentage+" %");
}*/
var updateProgressBar = function(val){

    if(typeof val != 'undefined'){
        if("string" == typeof(val)){
            val = parseFloat(val);
        }
        
        var bar = Ext.getCmp('progressbar');
        bar.updateProgress(val* 0.03);
        bar.updateText("Avancement "+val*100 +" %");
    }
}