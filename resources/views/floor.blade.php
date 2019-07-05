@extends('layouts.app')

@section('content')
<style>
      body {
        margin: 0;
        padding: 0;
        /*overflow: hidden;*/
        background-color: #f0f0f0;
      }

      #container {
        position: absolute;
        width: 100%;
        height: 800px;
        overflow: hidden;
        opacity: 0.7;
      }

      .context-menu.dropdown-menu {
  display: block;
  left: 0px;
  opacity: 0;
  position: absolute;
  top: 0px;
  transition: visibility 0s 0.1s, opacity 0.1s linear;
  visibility: hidden;
}

.context-menu.dropdown-menu.open {
  visibility: visible;
  opacity: 1;
  transition: opacity 0.1s linear;
}

.context-menu.dropdown-menu a { cursor: pointer; }

.dropdown-submenu .dropdown-toggle:after {
  content: "\f0da";
  display: inline-block;
  float: right;
  font: normal normal normal 14px/1 FontAwesome;
  font-size: inherit;
  padding-top: 3px;
  text-rendering: auto;
  -webkit-font-smoothing: antialiased;
}

.dropdown-submenu .dropdown-menu {
  top: 0;
  left: 100%;
}

#cnxt-cursor {
  height: 0px;
  opacity: 0;
  position: absolute;
  visibility: hidden;
  width: 0px;
}

    </style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Select a Floor Plan</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <button id="addRoom">Add Room</button>
                    <button id="saveFloor">Save Floor</button>
                    <div id="container" style="border: 1px solid red;"></div>
                    <img src="/storage/{{$floor->image}}" />
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSubMenu">
  Launch demo modal
</button> -->

<!-- Modal -->
<div class="modal fade" id="addSubMenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Sub Menu To Active Box</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" name="" id="subMenuName">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submitSubMenu">Save changes</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel2">Add Item To Active Box</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <label>Name</label>
      <div class="modal-body">
        <input type="text" name="" id="itemName">
      </div>
      <label>Value</label>
      <div class="modal-body">
        <input type="text" name="" id="itemValue">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submitItem">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editItems" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel3">Edit Items</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      

      <div class="modal-body">
        <div  id="items" style="width: 400px; height: 400px;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveItems">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>
var data = {

};

function myClone(obj)
{
  return JSON.parse(JSON.stringify(obj));

}

function Foo(){
alert('Foo');
}
function copyItem(){
alert('Copy');
}
function Paste(){
alert('Paste');
}

function addSubMenu()
{
  k.addSubMenu();
}

function addItem()
{
  k.addItem();
}

function editItems()
{
  k.editItems();
}

function saveItems()
{
  k.saveItems();
}


var menu = '';
var k = '';
var Jeditor = '';

var menuSettings = {
                    hoverColor: 'grey',
                    hoverTextColor: 'white',
                    color: 'white',
                    textColor: 'red',
                    shadow: true,
                    width: 250,
                    closeOnScroll: false
                };

// var submenu = [
//     [
//       {name: 'Option 1', function: Foo},
//       {name: 'Option 2', function: Foo}
//     ]
//   ];

function defMenu()
{
  // console.log('DefMenu:', defMenuStuff);

  return myClone(defMenuStuff);
  // return Object.assign({}, defMenuStuff);
}
var defMenuStuff = [
  // {name: 'Submenu', function: [
  //   [
  //     {name: 'Option 1', function: Foo},
  //   ],[
  //     {name: 'Option 2', function: Foo}
  //   ]
  // ]},
  // {name: 'Insert', function: Foo},
  // {name: 'Update', function: Foo},
  // {name: 'Delete', function: Foo}

];


//--------- Page Load ---------
$(document).ready(function(){

    $("#addRoom").click(function(){
        k.startDraw();
    });

    $('#submitSubMenu').click(function(){
        k.insertSubMenu();
        $('#addSubMenu').modal('hide');
    });

    $('#submitItem').click(function(){
        k.insertItem();
        $('#addItem').modal('hide');
    });

    $('#saveItems').click(function(){
        k.saveItems();
        $('#editItems').modal('hide');
    });
    $('#saveFloor').click(function(){
        k.save();
    });

  var width = window.innerWidth;
  var height = window.innerHeight;

    k = new myCanvas();
  @if($floor->data == '{}' || $floor->data == '')
    k.init_fresh(width, height);
  @else
    k.init_with(<?php echo json_encode($floor->data) ?>);
  @endif




  menu = new RightClick('#container');
  
  menu.settings(menuSettings);

});
      // add the layer to the stage
      // stage.draw();

      class myCanvas {

        layer = '';
        stage = '';
        contextFlag = false;
        contextMenu = '';

        currentRoom = '';
        currentMenu = '';

        lastEvent = '';

        drawFlag = false;
        drawPoints = [];
        drawPointLayer = '';


        constructor() 
        {
            
        }

        init_with(data)
        {
          this.stage = Konva.Node.create(data, 'container');

            this.layer = this.stage.find('#mainLayer');//getLayers()[1];//new Konva.Layer();

            var me = this;
            this.layer.children.each(function(a){

//Mouse over
              a.on('mouseover', (e) => {
                  if(me.contextFlag) return false;
                  me.lastEvent = e;

                  var items = myClone(e.target.getAttr('items'));

                  me.addFunctionButtons(items);
                  menu.addItems(items);
              });

            });

            var me = this;
            this.stage.on('click', (e) => 
            {
              if(!me.drawFlag) return;

              me.addRoomPoint(e.evt.layerX,e.evt.layerY);
            });
        }

        init_fresh(width, height)
        {
          this.stage = new Konva.Stage({
                container: 'container',
                width: width,
                height: height
              });

            this.layer = new Konva.Layer({
                id: 'mainLayer'
            });
            

            this.stage.add(this.layer);

            var me = this;
            this.stage.on('click', (e) => {
// console.log(e);
              if(!me.drawFlag) return;

              me.addRoomPoint(e.evt.layerX,e.evt.layerY);
            });
            // this.stage.on('contentContextmenu', (e) => {
            //   e.evt.preventDefault();
            // });

            this.contextMenu = new Konva.Group({
                draggable: true,
                id: 'contextMenu'
              });

            this.contextMenu.add(new Konva.Rect({
                name: 'box',
                fill: 'white',
                stroke: 'black',
                strokeWidth: 1

              }));
        }


        transform(target)
        {
            this.stage.find('Transformer').destroy();
            var tr = new Konva.Transformer();
            this.layer.add(tr);
            tr.attachTo(target);
            this.layer.draw();
        }

        addSubMenu()
        {
            $('#addSubMenu').modal();
        }

        addItem()
        {
            $('#addItem').modal();
        }

        insertItem()
        {
          var name = $('#itemName').val();
          var value = $('#itemValue').val();

          if(this.currentMenu == '')
            {
              var obj = this.lastEvent.target;
              var items = obj.getAttr('items');

              items.push({
                  name: name,
                  value: value,
                  function: { name: 'copyItem' }
              });

              obj.setAttr('items', items);

              // this.currentMenu = '';

              return;

            }else{
              var attrs = this.lastEvent.target.getAttrs();
              var items = this.findById(attrs, this.currentMenu); //attrs.items.find(item => item.id === this.currentMenu);

              var item = {
                  name: name,
                  value: value,
                  function: { name: 'copyItem' }
              };
              
                items.function[0].push(item);
              
              this.currentMenu = '';
            }

        }

        insertSubMenu()
        {
            var name = $('#subMenuName').val();
// console.log(this.currentMenu);
            if(this.currentMenu == '')
            {
              var obj = this.lastEvent.target;
              var items = obj.getAttr('items');

              items.push({
                  name: name,
                  function: [[]],
                  id: getUniqID(),
                  parent: '000'
              });

              obj.setAttr('items', items);

              // this.currentMenu = '';

              return;

            }else{
              var attrs = this.lastEvent.target.getAttrs();
              var items = this.findById(attrs, this.currentMenu); //attrs.items.find(item => item.id === this.currentMenu);

              var item = {
                  name: name,
                  function: [[]],
                  id: getUniqID(),
                  parent: this.currentMenu
              };
              
                items.function[0].push(item);
              // if(items.function.length == 0)
              // {
              //   items.function.push(item);

              // }else{
              //   // items.function.push([]);
                
              // }

              this.currentMenu = '';
            }
// console.log('Orig Items: ',items);

            
// console.log('Pushing to ',this.lastEvent.target);

            // this.currentMenu.setAttr('items', items);

// console.log('After Items: ',items);
            // this.showContext(this.lastEvent);

        }

        editItems()
        {
          $('#editItems').modal();
          // $('#items').val(JSON.stringify(this.lastEvent.target.getAttrs()));

          if(Jeditor)
            Jeditor.destroy();
          var container = document.getElementById("items");
        var options = {};
        Jeditor = new JSONEditor(container, options);


        Jeditor.set(this.lastEvent.target.getAttrs().items);

        // get json
        // var json = editor.get();


        }

        saveItems()
        {
          var newValue = Jeditor.get();
          this.lastEvent.target.setAttr('items',newValue);

          Jeditor.destroy();

          // $('#editItems').modal('hide');
        }

        addSubFunctionButtons(items)
        {
console.log('--adding SUB',myClone(items));

            $(items).each(function(i){
console.log('--Loop SUB:',i,this, typeof this.function, Array.isArray(this.function));
              if(this.function && this.function.length > 0)
              // if(typeof this.function == "object")
              {
                this.function = k.addSubFunctionButtons(this.function);
              }
              else if(this.function && typeof this.function[0] == "object")
              {
                this.function[0] = k.addSubFunctionButtons(this.function);
              }
            });


            // items.push([
            //   {name: 'Add Item..', function: addSubMenu}, 
            //   {name: 'Add Sub-Menu..', function: addSubMenu}
            // ]);

// items = JSON.parse(JSON.stringify(items));
console.log('--returning SUB',myClone(items));
            return items;
        }

        addFunctionButtons(items)
        {
// console.log('adding',myClone(items));

            $(items).each(function(i){
// console.log('Loop:',i,this, typeof this.function);
              if(typeof this.function == "object")
              {
                this.function = k.addSubFunctionButtons(this.function);
              }
            });


            items.push({name: 'Edit', function: editItems});
            items.push({name: 'Add Item', function: addItem});
            items.push({name: 'Add Sub-Menu', function: addSubMenu});
// items = JSON.parse(JSON.stringify(items));
// console.log('returning',myClone(items));
            return items;
        }



        addRoomPoint(x, y)
        {
          if(this.drawFlag == false) return;

          var circleSettings = {
            x: x,
            y: y,
            radius: 20,
            fill: 'green',
            stroke: 'black',
            strokeWidth: 1
          };

          if(this.drawPointLayer.children.length > 0)
          {
            circleSettings.fill = 'red';
            circleSettings.radius = 5;
          }
          var circle = new Konva.Circle(circleSettings);

          if(this.drawPointLayer.children.length == 0)
          {

            circle.on('click', (e) => {
// console.log('finished!');
              this.finishDraw();
            });
          }

          this.drawPointLayer.add(circle);
          this.drawPointLayer.draw();

          // this.drawPoints.push({x: x, y: y});
          this.drawPoints.push(x);
          this.drawPoints.push(y);
        }

        finishDraw()
        {
          this.drawFlag = false;

          var poly = new Konva.Line({
            points: this.drawPoints,
            fill: '#00D2FF',
            stroke: 'black',
            strokeWidth: 5,
            closed: true,
            items: defMenu()
          });

          poly.on('click', (e) => {
            if (e.evt.button === 2) {
              this.contextFlag = true;
            }
          });

          poly.on('mouseover', (e) => {
              if(this.contextFlag) return false;

// console.log('ID:', e.target._id);
// console.log('over1', rect.getAttrs('items'));
// console.log('Set Last Event',e);
              this.lastEvent = e;

              var items = e.target.clone().getAttr('items');
              var items = JSON.parse(JSON.stringify(items));
              this.addFunctionButtons(items);
              menu.addItems(items);
              
// console.log('over2', rect.getAttrs('items'));
          });

          this.layer.add(poly);
          this.drawPointLayer.destroy();

          this.stage.draw();


        }

        startDraw()
        {
          this.drawFlag = true;
          this.drawPointLayer = new Konva.Layer();
          this.stage.add(this.drawPointLayer);

          this.drawPoints = [];

        }

        addRoom()
        {
            var rect = new Konva.Rect({
                x: 50,
                y: 50,
                width: 100,
                height: 50,
                fill: 'green',
                stroke: 'black',
                strokeWidth: 2,
                draggable: true,
                items: defMenu()
              });

            rect.on('click', (e) => {
              if (e.evt.button === 2) {
                this.contextFlag = true;
                // this.currentMenu = 1;
              }
            });

            rect.on('mouseover', (e) => {
                if(this.contextFlag) return false;

// console.log('ID:', e.target._id);
// console.log('over1', rect.getAttrs('items'));
// console.log('Set Last Event2',e);
                this.lastEvent = e;

                var items = e.target.clone().getAttr('items');
                var items = JSON.parse(JSON.stringify(items));
                this.addFunctionButtons(items);
                menu.addItems(items);
                
// console.log('over2', rect.getAttrs('items'));
            });
//             rect.on('mouseout', (e) => {
              
// // console.log('out',e, myMenu);

//             });

              // add the shape to the layer
              this.layer.add(rect);
              // this.stage.draw();

              this.transform(rect);



      // add the layer to the stage
        }


        findById(o, id) {
            //Early return
            if( o.id === id ){
              return o;
            }
            var result, p; 
            for (p in o) {
                if( o.hasOwnProperty(p) && typeof o[p] === 'object' ) {
                    result = this.findById(o[p], id);
                    if(result){
                        return result;
                    }
                }
            }
            return result;
        }

        save()
        {

          $.ajax({
              url: '{{ route('saveFloorData') }}',
              headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
              type: 'POST',
              data: {
                d: this.stage.toJSON(),
                id: {{ $floor->id }}
              },
              // datatype: 'json',
              success: function(response) { console.log(response); },
              error: function (jqXHR, textStatus, errorThrown) { console.log(jqXHR, textStatus, errorThrown) }
          });
        }

    }


    </script>
@stop
