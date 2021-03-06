/*
 * File: designer.js
 * Date: Thu Apr 05 2012 17:22:17 GMT+0100 (Afr. centrale Ouest)
 *
 * This file was generated by Ext Designer version 1.2.2.
 * http://www.sencha.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

Ext.onReady(function() {
    
    Ext.Loader.setConfig({
        enabled: true
    });

    Ext.application({
        name: 'PLATEFORME',

        config: {
            myMask : null 
        },
   
        mask : function(){
            if(!this.myMask)
                this.myMask = new Ext.LoadMask(Ext.getBody(), {
                    msg:"Veuillez patienter..."
                });
            this.myMask.show();
        },  
    
        unmask : function(){
            this.myMask.hide();
        },
        launch: function() {
            Ext.QuickTips.init();
        
            PLATEFORME = this;
        
        

            var cmp1 = Ext.create('MyApp.view.ui.MyViewport', {
                renderTo: 'principal-bloc'
            });           
            cmp1.show();
            
        }
    });
});

