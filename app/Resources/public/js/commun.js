
var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
   
 

var managerNextHandler = function(URL_NEXT_STEP, message){
    
    var buttons = ['top-next','bottom-next'];
    
    Ext.Array.each(buttons, function(button, index, countriesItSelf) {
        var next1 = Ext.getCmp(button);
        next1.setHandler(function(){
            if(message){
                Ext.MessageBox.show({
                    // cls:'popup-confirm',
                    width:500,
                    height:270,                                               
                    title:'Status',
                    msg: message,
                    buttons: Ext.MessageBox.YES,
                    buttonText:{ 
                        yes: "Continuer"

                    },

                    closable:true,
                    //buttonText:'Continuer',
                    icon:false,
                    fn:function(buttonId){
                        if (buttonId === "yes") {
                            openUrl(URL_NEXT_STEP);
                        }
                    }
                });
            }else{
                openUrl(URL_NEXT_STEP);
            }
                                               
        });
    });
    
}


var managerPreviewsHandler = function(URL_PREV_STEP){
    
    var buttons = ['top-preview','bottom-preview'];
    
    Ext.Array.each(buttons, function(button, index, countriesItSelf) {
        var next1 = Ext.getCmp(button);
        next1.setHandler(function(){
            
            openUrl(URL_PREV_STEP); 
        });
    
    });
}

 
 
var openUrl = function(url, params){
    var form = new Ext.form.Panel({
        hidden :true,
        standardSubmit: true
    }); 
    var basic = form.getForm();
    basic.submit({
        url: url,
        params: params
       
    });
}

var showMask = function(show){
    
/* var tmpMask = new Ext.LoadMask(Ext.getBody());
      tmpMask.show();*/
    
// Ext.getBody().mask();
}

var updateProgressBar = function(val){

    if(typeof val != 'undefined'){
        if("string" == typeof(val)){
            val = parseFloat(val);
        }
        
        var bar = Ext.getCmp('progressbar');
        bar.updateProgress(val* 0.42);
        bar.updateText("Avancement "+val*100 +" %");
    }
}

var disabledForm = function(form, state){
    var fields = form.getForm().getFields(); 
    
    
           
    fields.each( function(){
        
    

        //if state is true --> disabled else enabled
        this.setReadOnly(state);
    })
}


var changeMessge= function(text) {
    
    var message = Ext.getCmp('message-accompagnant');
    message.update("<div id='message-boble' class='message-boble'>"+text+"<img class='queu' src='"+URL_IMAGE_BOBLE+" '></div>");
 
// var m = Ext.fly('message-boble').setHTML(text+"<img class='queu' src= "+URL_IMAGE_BOBLE +">");
    
}

var hideNavigationBtn = function(hide){
    if(hide){
        Ext.select('.suivant-btn-bleu').each(function(el){
            el.hide();
        }); 
        Ext.select('.precedent-btn-bleu').each(function(el){
            el.hide();
        });
    }else{
        Ext.select('.suivant-btn-bleu').each(function(el){
            el.show();
        }); 
        Ext.select('.precedent-btn-bleu').each(function(el){
            el.show();
        }); 
    }
        
}

var deleteRecord = function(element, element_identifier, prefix, url, store){
   
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
                            'form[_token]' : selection.data['Form[_token]'],
                            'form[id]' : selection.getId()
                        },
                        url: url ,
                        success: function (response ,form ){
                            var jsonData = Ext.decode(response.responseText);
                            if(jsonData.success){
                            
                                if(jsonData.valider){                
                                    Ext.get('menu-'+prefix).addCls('valide');                                    
                                }else  if(jsonData.valider == false){
                                    Ext.get('menu-'+prefix).removeCls('valide');                                  
                                }
                                VALIDER=jsonData.valider;
                                updateProgressBar(jsonData.avancement);
                            }
                          
                          
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

var deleteNode = function(element, element_identifier, prefix, url){

    var grid = Ext.getCmp('treepanel-'+prefix);

    var sm = grid.getSelectionModel();

    if (sm.hasSelection()){

        var node = sm.getLastSelected();        
 
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
                            //  'form[_token]' : node.data._token,
                            'id' : node.getId()
                        },
                        url: url ,
                        success: function (response ,form ){
                            var jsonData = Ext.decode(response.responseText);
                            if(jsonData.success){
                            
                                if(jsonData.valider)                
                                    Ext.get('menu-'+prefix).addCls('valide');
                                else  if(jsonData.valider == false)
                                    Ext.get('menu-'+prefix).removeCls('valide');  
                                
                                updateProgressBar(jsonData.avancement);
                            
                                var root = grid.getRootNode();
                                var parent_id = node.data.parentId;
                                node.remove(false);
                                var parent = root.findChild('id',parent_id);
                                if(parent && !parent.hasChildNodes( ))
                                    parent.remove(false);
                            
                                Ext.example.msg('Suppression ','Elément supprimé.');
                                
                            }
                            
                            
               
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




