(function(){
    var E = window.wangEditor;
    var $ = window.jQuery;

    E.createMenu(function(check){
        var menuId = "chooseImg";
        if(!check(menuId)){
            return ;
        }
        // 创建 menu 对象
        var menu = new E.Menu({
            editor: editor,  // 编辑器对象
            id: menuId,  // 菜单id
            title: '图片', // 菜单标题

            // 正常状态和选中状态下的dom对象，样式需要自定义
            $domNormal: $('<a href="#" tabindex="-1"><i class="wangeditor-menu-img-picture"></i></a>'),
            $domSelected: $('<a href="#" tabindex="-1" class="selected"><i class="wangeditor-menu-img-picture"></i></a>')
        });
         // 菜单正常状态下，应该激活modal框
        menu.clickEvent = function (e) {
            $('#wangEditorChooseImg').click();
        };

        // 增加到editor对象中
        editor.menus[menuId] = menu;

    });
})();