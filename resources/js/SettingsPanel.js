Ext.ns('dvelum.import');

Ext.define('dvelum.import.SettingsModel',{
    extend:'Ext.data.Model',
    fields:[
        {
            name:"id",
            type:"integer"
        },{
            name:"name",
            type:"string"
        },{
            name:"default",
            type:"boolean"
        },{
            name:"update_date",
            type:"date",
            dateFormat:"Y-m-d H:i:s"
        }
    ],
    idProperty:'id'
});
/**
 *
 *
 * @event settingsLoaded
 * @param {Object}    {id:int,name:string,settings:{}...}
 *
 * @event settingsSaved
 * @param {Object}    {id:int,name:string,settings:{}...}
 */
Ext.define('dvelum.import.SettingsPanel',{
    extend:'Ext.panel.Panel',
    bodyCls:'formBody',
    frame:false,
    lang:null,
    border:0,
    height:40,
    /**
     * Import Panel link
     * @var {dvelum.import.Panel}
     */
    importPanel:null,

    layout: {
        type: 'hbox',
        pack: 'start',
        align: 'middle'
    },

    settingsCfg:null,

    config:{
       controllerUrl:'',
       extraParams:{}
    },

    initComponent:function(){

        this.items = [
            {
                icon:'/resources/dvelum-module-import/images/open.png',
                scope:this,
                handler:this.showLoadSettings,
                tooltip:this.lang.load_settings ,
                xtype:'button'

            },{
                tooltip:this.lang.save_settings,
                icon:'/resources/dvelum-module-import/images/saveas.png',
                xtype:'button',
                scope:this,
                handler:this.showSaveSettings
            },{
                xtype:'hidden',
                name:'settings_id',
                itemId:'settings_id'
            },{
                xtype:'tbtext',
                height:20,
                text:'',
                style: {
                    width: '100%',
                    margin:3,
                    padding:"0px 5px 0px 5px",
                    border:'1px solid #cccccc',
                    backgroundColor:'#F6F6F6'
                },
                flex:1,
                itemId:'configName'
            }
        ];
        this.callParent();
    },
    /**
     * Показать интерфейс загрузки настроек
     */
    showLoadSettings:function(){
        var win = Ext.create('dvelum.import.SettingsWindow',{
            controllerUrl:this.controllerUrl,
            extraParams:this.extraParams,
            lang:this.lang
        });

        win.on('settingsLoaded' , function(settings){
            this.fireEvent('settingsLoaded' , settings);
            this.setSettingsConfig(settings);
            win.close();
            app.msg(appLang.MESSAGE, this.lang.settings_loaded);
        },this);

        win.show();
    },
    /**
     * Show save settings window
     */
    showSaveSettings:function(){

        var params = this.importPanel.getImportSettings();

        if(!params){
            Ext.Msg.alert(appLang.MESSAGE, this.lang.msg_set_settings);
            return;
        }

        params = Ext.apply(params , this.importPanel.getForm().getValues());

        var win = Ext.create('dvelum.import.SettingsWindow',{
            controllerUrl:this.controllerUrl,
            saveMode:true,
            settingsCfg:params,
            lang:this.lang,
            extraParams:this.extraParams
        });

        win.on('settingsSaved' , function(settings){
            this.fireEvent('settingsSaved' , settings);
            this.setSettingsConfig(settings);
            win.close();
            app.msg(appLang.MESSAGE, this.lang.settings_saved);
        },this);

        win.show();
    },
    /**
     * Apply settings config
     * @var {Object} info -  settings record data
     */
    setSettingsConfig:function(info){
        this.settingsCfg = info;
        if(info){
            this.down('#settings_id').setValue(info.id);
            this.down('#configName').setText(info.name);
        }
    },
    /**
     * Get settings configuration
     */
    getSettingCfg:function(){
        return this.settingsCfg;
    }
});


/**
 * Settings window
 *
 * @event settingsLoaded
 * @param {Object}    {id:int,name:string,settings:{}...}
 *
 * @event settingsSaved
 * @param {Object}     {id:int,name:string,settings:{}...}
 *
 */
Ext.define('dvelum.import.SettingsWindow' , {
    extend:'Ext.Window',
    /**
     * Action url
     * @var string
     */
    controllerUrl:'',
    /**
     * Action params
     * @var boolean | {Object}
     */
    extraParams:false,
    /**
     * 1 - save mode 0 - load mode
     * @var boolean
     */
    saveMode:false,

    /**
     * Список настроек
     * @var {Ext.data.Grid}
     */
    dataGrid:null,
    /**
     * Хранилище настроек
     * @var {Ext.data.Store}
     */
    dataStore:null,

    /**
     * Settings data
     * @var {Object}
     */
    settingsCfg:null,
    /**
     * @protected
     */
    loadedSettings:null,
    /**
     * Url configuration
     * @var {Object}
     */
    controllerActions:null,

    config:{
        controllerActions:{
            list:'settingslist',
            load:'settingsload',
            save:'settingssave',
            delete:'settingsdelete'
        },
        extraParams:{},
        layout:'fit',
        modal:true,
        width:400,
        height:300
    },

    initComponent:function(){

        this.initStore();
        this.initGrid();

        this.buttons = [];
        var me = this;

        if(this.saveMode){
            this.title = this.lang.save_settings;
            this.tbar = [
                {
                    icon:'/resources/dvelum-module-import/images/plus.png',
                    tooltip:this.lang.save_as_new_setting,
                    text:this.lang.save_as_new_setting,
                    handler:this.saveAsNew,
                    scope:me
                },' ' + this.lang.or_rewrite_old
            ];
            this.buttons.push({
                    text:this.lang.save,
                    scope:me,
                    disabled:false,
                    itemId:true,
                    handler:function(btn){
                        var sm  = me.dataGrid.getSelectionModel();
                        if(!sm.hasSelection()){
                            btn.disable();
                            return;
                        }
                        this.saveSettings(sm.getSelection()[0]);
                    }
                }
            );
        }else{
            this.title = this.lang.load_settings;
            this.buttons.push({
                    text:this.lang.load,
                    scope:me,
                    disabled:true,
                    itemId:'loadBtn',
                    handler:function(btn){
                        var sm  = me.dataGrid.getSelectionModel();
                        if(!sm.hasSelection()){
                            btn.disable();
                            return;
                        }
                        this.loadSettings(sm.getSelection()[0]);
                    }
                }
            );

            this.dataGrid.on('selectionchange' , function(view , selected){
                if(selected.length){
                    this.down('#loadBtn').enable();
                }else{
                    this.down('#loadBtn').disable();
                }
            },this);

            this.dataGrid.on('selectionchange' , function(view , selected){
                if(selected.length){
                    this.down('#loadBtn').enable();
                }else{
                    this.down('#loadBtn').disable();
                }
            },this);

            this.dataGrid.on('celldblclick' , function(grid, td, cellIndex, record){
                this.loadSettings(record);
            },this);

        }

        this.buttons.push({
            text:this.lang.close,
            scope:this ,
            handler:this.close
        });

        this.items = [this.dataGrid];
        this.callParent();
    },
    /**
     * Init storage
     */
    initStore:function(){
        this.dataStore = Ext.create('Ext.data.Store',{
            autoLoad:true,
            model:'dvelum.import.SettingsModel',
            proxy:{
                simpleSortMode:true,
                extraParams:this.extraParams,
                url:this.controllerUrl + this.controllerActions.list,
                reader:{
                    idProperty:"id",
                    rootProperty:"data"
                },
                type:"ajax"
            }
        });
    },
    /**
     * Init settings grid
     */
    initGrid:function(){
        var me = this;
        this.dataGrid = Ext.create('Ext.grid.Panel',{
            columnLines:false,
            rowLines:false,
            multiSelect:false,
            simpleSelect:true,
            store:this.dataStore,
            hideHeaders:true,
            viewConfig:{
                enableTextSelection: true
            },
            selModel:{
                mode:'SINGLE'
            },
            columns:[
                {
                    dataIndex:'id',
                    flex:1,
                    renderer:function(value , meta , record){
                        meta.style = "font-size:14px;font-seight:bold;cursor:pointer;";

                        var result = record.get('name');
                        if(record.get('default')){
                            result += ' <span style="font-size:9px">(' + me.lang.default + ')</span>';
                        }

                        result+='<br><span style="font-size:9px">'
                            + Ext.Date.format(record.get('update_date'), 'd.m.y H:i')
                            + '</span>';

                        return result;
                    }
                },{
                    dataIndex:'default',
                    xtype:'actioncolumn',
                    width:20,
                    items:[
                        {
                            icon:'/resources/dvelum-module-import/images/delete.gif',
                            tooltip:this.lang.delete_setting,
                            handler:function(view , rowIndex ,colIndex, item , e , record){
                                this.deleteSetting(record);
                            },
                            scope:this,
                            isDisabled:function(view , rowIndex ,colIndex, item , record){
                                return record.get('default');
                            }
                        }
                    ]

                }
            ]
        });
    },
    /**
     * Load settings data
     * @var {Ext.data.Model} record
     */
    loadSettings:function(record){
        Ext.Ajax.request({
            url:this.controllerUrl + this.controllerActions.load,
            method: 'post',
            params:{
                id:record.get('id')
            },
            scope:this,
            success: function(response) {
                this.loadedSettings = null;
                response =  Ext.JSON.decode(response.responseText);
                if(!response.success){
                    Ext.Msg.alert(appLang.MESSAGE , response.msg);
                } else{
                    this.loadedSettings = response.data;
                    this.fireEvent('settingsLoaded' ,  this.loadedSettings);
                }
            },
            failure:function(){
                app.ajaxFailure(arguments);
            }
        });
    },
    /**
     * Save setting data
     * @var {Ext.data.Model} record
     */
    saveSettings:function(record){
        var requestParams = Ext.apply(this.settingsCfg,{
            'settings_id': record.get('id'),
            'settings_name': record.get('name')
        });

        Ext.Ajax.request({
            url:this.controllerUrl + this.controllerActions.save,
            method: 'post',
            params:requestParams,
            scope:this,
            success: function(response) {
                this.loadedSettings = null;
                response =  Ext.JSON.decode(response.responseText);
                if(!response.success){
                    Ext.Msg.alert(appLang.MESSAGE , response.msg);
                    this.dataStore.load();
                } else{
                    this.dataStore.remove(record);
                    this.loadedSettings = response.data;
                    this.fireEvent('settingsSaved' ,  this.loadedSettings);
                }
            },
            failure:function(){
                app.ajaxFailure(arguments);
            }
        });
    },
    /**
     * Delete setting
     * @param {Ext.data.Model} record
     */
    deleteSetting:function(record){

        if(record.get('default')){
            Ext.Msg.alert(appLang.MESSAGE , this.lang.cant_delete_default);
            return;
        }

        Ext.Ajax.request({
            url:this.controllerUrl + this.controllerActions.delete,
            method: 'post',
            params:{
                id:record.get('id')
            },
            scope:this,
            success: function(response) {
                response =  Ext.JSON.decode(response.responseText);
                if(!response.success){
                    Ext.Msg.alert(appLang.MESSAGE , response.msg);
                } else{
                    this.dataStore.remove(record);
                }
            },
            failure:function(){
                app.ajaxFailure(arguments);
            }
        });
    },
    /**
     * Create new setting
     */
    saveAsNew:function(){
        Ext.MessageBox.prompt(appLang.MESSAGE, this.lang.enter_new_setting_name, function(btn,text){
            if(btn!=='ok' || text.length<1) {
                return;
            }

            this.dataStore.insert(0,{
                'id':0,
                'name':text,
                'default':0,
                'update_date':null
            });

            this.saveSettings(this.dataStore.getAt(0));
        },this);
    },
    destroy:function(){

        var toDestroy  = [
            this.dataStore,
            this.dataGrid
        ];

        Ext.Array.each(toDestroy, function (item) {
            if(item && item.destroy){
                item.destroy();
            }
        });

        toDestroy = null;
        this.callParent(arguments);
    }
});