var _mouseClickLeft;
var _mouseClickTop;

class RightClick{
    
    constructor(div){
        this.menu = div;
        this.items = null;
        this.submenuIndex = 1;
        this.zIndex = 50;
        
        $(div).bind("contextmenu",function(e){
            e.preventDefault();
            _mouseClickLeft = e.pageX;
            _mouseClickTop = e.pageY;
            console.log('e', e);
            // console.log('pageY', e.pageY);
            $("#cntnr").css("left",e.pageX - parseInt(document.body.scrollLeft));
            $("#cntnr").css("top",e.pageY);// - parseInt(document.body.scrollTop));       
            $("#cntnr").fadeIn(200,startFocusOut());
            $('.submenu').hide();
        });
        
    }
    
    createOptions(args){
        
        var result = '';
        
        for (var i=0;i<args.length;i++){
            result += '<ul id="items">';
            for (var item in args[i]){
                var submenu = false;
                var functionName; 
                var value = '';
                if (args[i][item].hasOwnProperty('function')){
                    if (args[i][item].function.hasOwnProperty('name')){
                        functionName = ' onclick="' + args[i][item].function.name;
                        if (args[i][item].hasOwnProperty('value')){
                            functionName += '(\''+args[i][item].value+'\')"';
                        }else{
                            functionName += '()"';
                        }
                        // console.log(args[i][item].function.name);
                    }
                    else if (Array.isArray(args[i][item].function)){
                        functionName = ' onclick="showSubmenu(' + (this.submenuIndex+1) + ', this,\''+args[i][item].id+'\',\''+args[i][item].parent+'\')"';
                        this.submenuIndex += 1;
                        this.createSubmenu(args[i][item]);
                        submenu = true;
                    }
                    else{
                        functionName = '';
                    }
                }
                var icon;
                if (args[i][item].hasOwnProperty('icon')){
                    icon = args[i][item].icon;
                }
                else
                    icon = '';
                if (args[i][item].hasOwnProperty('value')){
                    value = ':'+args[i][item].value;
                }
                else
                    value = '';
                var shortcut;
                if (args[i][item].hasOwnProperty('shortcut'))
                    shortcut = args[i][item].shortcut;
                else
                    shortcut = '';
                if (shortcut == '' && submenu) shortcut = '&#8250';
                result += '<li ' + functionName + ' class="disabled ' + (submenu ? 'submenu-li' : '') + '">';
                result += '<p>';
                result += '<span class="icon">';
                if (icon != '') result += '<img height="16" width="16" src="' + icon + '">';
                result += '</span>';
                result += args[i][item].name;
                result += value;
                result += '<span class="shortcut">';
                result += shortcut;
                result += '</span>';
                result += '</p>';
                result += '</li>';
            }
            result += '</ul>';
            if (i != args.length -1)
                result += '<hr />';
        }        
        return result;
    }
    
    addItems(){
        $('#cntnr').remove();
        $('.submenu').remove();
        this.submenuIndex = 1;

        var menuContent = '';
        menuContent += '<div id="cntnr">';
        menuContent += this.createOptions(arguments);
        menuContent += '</div>';
        $('body').append(menuContent);
        
        $('#cntnr, .submenu').bind("contextmenu", function(e){
            e.preventDefault();
        })
    
        $('#cntnr li.submenu-li').on('click', function(e){
            return false;
        })
        
        $('.submenu li.submenu-li').on('click', function(e){
            return false;
        })
    }
    
    createSubmenu(items){
        var submenuContent = '';
        submenuContent += '<div style="z-index:' + this.zIndex + '" class="submenu" id="submenu' + this.submenuIndex.toString() + '" data-parent-id="'+items.parent+'" data-id="'+items.id+'">';
        this.zIndex += 1;
        submenuContent += this.createOptions(items.function);
        submenuContent += '</div>';
        
        $('body').append(submenuContent);
        return false;
    }
    
    settings(items){
        var color = typeof items.color === 'undefined' ? 'white' : items.color;
        if (typeof items.hoverColor !== 'undefined')
            $('#cntnr li').hover(function(){
                $(this).css('background-color', items.hoverColor);
            }, function(){
                $(this).css('background-color', color);
            })
        if (typeof items.shadow !== 'undefined'){
            if (items.shadow === true)
                $('#cntnr').css('box-shadow', '4px 4px #E9E9E9');
            else $('#cntnr').css('box-shadow', '');
        }
        if (typeof items.width !== 'undefined'){
            $('#cntnr').css('width', items.width);
        }
        if (typeof items.height !== 'undefined'){
            $('#cntnr').css('height', items.height);
        }
        if (typeof items.closeOnScroll !== 'undefined'){
            if (items.closeOnScroll) this.closeOnScroll(true);
            else this.closeOnScroll(false);
        }
    }
    
    closeOnScroll(close){
        if (close){
            $(document).on('scroll', function(e){
                $('#cntnr').hide();
                $('.submenu').hide();
            })
        }
        else{
            $(document).on('scroll', function(e){
            })
        }
    }
}

function showSubmenu(index, obj, ID, parent){
    // console.log(obj);
    // if(parent)
    //     $('[data-parent-id='+parent+']').hide();
    // else
    //     $('.submenu').hide();


    hideSubMenuFrom(parent);

    var submenuName = '#submenu' + index;
    // console.log('SUBMENU NAME: ' + submenuName);
    var left = obj.getBoundingClientRect().x + obj.getBoundingClientRect().width ;
    var top = obj.getBoundingClientRect().y ;
    // console.log('LEFT: ' + left);
    // console.log('TOP: ' + top);
    $(submenuName).css('left', left);
    $(submenuName).css('top', top);
    $(submenuName).show();
    k.currentMenu = ID;
    return false;
}


function startFocusOut(){
       $(document).on("click",function(e){
           $("#cntnr, .submenu").hide();        
           $(document).off("click");
           k.contextFlag = false;
       });
   };

function hideSubMenuFrom(pID)
{
    $('[data-parent-id='+pID+']').each(function(){
        hideSubMenuFrom($(this).data('id'));
        $(this).hide();
    });
}

   