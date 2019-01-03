$( document ).ready(function(){

  // Vars
  var range = document.getElementById( 'Price' );
  var currency = $( 'span#minPrice' );
  var maxPrice = $( 'span#maxPrice' );
  
  var totalRecords = 0,
      records = [],
      displayRecords = [],
      recPerPage = 12,
      totalPages = 0;

  var filterWith = {
    category : "All",
    colection : "All",
    price : Dinero({ amount: 10000000 }).toFormat( '$0,0' )
  }    

  var purchasableObj = [], cartObject = [];

  var x = cartObject.length;


  //End Region

  //Functions

  /**
         * Summary of convertToDineroFormat
         * @param val
         * @return number
         */
        function convertToDineroFormat( val ){

          var resul = val * 100;

          return resul;

      }
      
      /**
       * Summary of removeDineroFormat
       * @param val
       * @return number
       */

      function removeDineroFormat( val ){

          resul = val / 100;

          return resul;

      }

      /**Sumary of getItems 
       * @return Json
      */

    $.ajax({

      url: "/App/Test/Products.json",
      async: true,
      dataType: 'json',

      success: function (data) {

        records = data['data'];
        totalRecords = records.length;
        totalPages = Math.ceil(totalRecords / recPerPage);
        applyPagination();

      }

    });

      $( 'input[type="checkbox"]' ).on('change', function() {
        $( 'input[name="' + this.name + '"]' ).not( this ).prop( 'checked', false );
        switch ( this.name ) {
          case 'group1[]':
              filterWith.category = this.value;

            break;
          case 'group2[]':
              filterWith.colection = this.value;
            break;
          default:
        }
      });

      $( '#openSearch' ).click(function(){
        $( '.searchBar' ).addClass( 'active' );
      });

      $( '#closeSearch' ).click(function(){
        $( '.searchBar' ).removeClass( 'active' );
        $('input#Search').val( '' );
      });

      $( 'a#OpenFilter' ).click(function(){
        $( 'div#filterOptions' ).addClass( 'active' );
      });

      $( 'a#closeFilter' ).click(function(){
        $( 'div#filterOptions' ).removeClass( 'active' );
      });

      $( 'a#openCart' ).click(function(){
        $( 'div#shoppingCart' ).addClass( 'active' );
        $( 'body' ).addClass( 'active' );
      });

      $( 'a#closeShoppingCart' ).click(function(){
          $( 'div#shoppingCart' ).removeClass( 'active' );
          $( 'body' ).removeClass( 'active' );
      });

      currency.text( Dinero({ amount: parseInt( range.value ) }).toFormat( '$0,0' ) );
      maxPrice.text( Dinero({ amount: 100000000 }).toFormat( '$0,0' ) );

      range.oninput = function(){
        var money = parseInt( this.value );
        currency.text( Dinero({ amount: money }).toFormat( '$0,0' ) );
        filterWith.price = Dinero({ amount: money }).toFormat( '$0,0' );
      }

      $( 'button#addFilter' ).click(function( ){


        console.log( 'category: ' + filterWith.category + ' colection: ' + filterWith.colection + ' price: ' + filterWith.price );

      });
  
    //End Region
  
    //DOM

        function showCardsPurchasable(){

        $( '#itemPage' ).text( '' );

          for (var i = 0; i < displayRecords.length; i++) {

            $( '#itemPage' ).append(
        
              '<div class="purchasable">'+
                '<div class="contentPurchasable">'+
                  '<div class="imgPurchasable">'+

                  '</div>'+
                  '<div class="infoPurchasable">'+
                    '<span class="w-bold emphasis infoContent title">'+displayRecords[i].name+'</span>'+
                    '<span>'+
                      '<span class="w-bold infoContent">Ref:</span>'+
                      '<span class="w-light" id="productReference">'+displayRecords[i].productID+'</span>'+
                    '</span>'+
                    '<span class="w-bold emphasis infoContent price">'+Dinero({ amount: convertToDineroFormat( displayRecords[i].unitPrice ) }).toFormat( '$0,0' )+'</span>'+
                  '</div>'+
                  '<div class="actionPurchasable">'+
                    '<button id="addPurchase"><i class="fas fa-cart-plus"></i></button>'+
                  '</div>'+
                '</div>'+
              '</div>'

            );

          }

          $( 'button#addPurchase' ).click(function(){

            var child = $( this ).parent();

            $( child ).each(function(){

              var parent = $(this).parent();

              var getChildren = parent.children();

              var childPosition = getChildren.get(1);

              var showReference = childPosition.children;

              var getChildElement = showReference.item(1);

              var children = getChildElement.children.item(1).innerText;

              for (let i = 0; i < displayRecords.length; i++) {              
                
                if ( displayRecords[i].productID == children ) {                  
                  
                  showPurchaseObj( displayRecords[i].productID );                  
                  
                }
                
              }

            });             

          }); 

        }

        $( 'div#paginator' ).append( '<p id="paginatorInfo" class="w-bold"></p><ul id="pagination"></ul>' );
        $( 'p#paginatorInfo' ).append( 'Pagina <span id="paginatorCount"></span> de <span id="paginatorMaxValue"></span>' )
        
        function applyPagination(){

            $( '#pagination' ).twbsPagination({
              totalPages: totalPages,
              visiblePages: 0,
              first: '',//'<i class="fas fa-angle-double-left"></i>',
              next: '<i class="fas fa-angle-right"></i>',
              prev: '<i class="fas fa-angle-left"></i>',
              last: '',//'<i class="fas fa-angle-double-right"></i>',

              onPageClick: function ( event, page ) { 

                  displayRecordsIndex = Math.max(page - 1, 0) * recPerPage;
                  endRec = (displayRecordsIndex) + recPerPage;
                  displayRecords = records.slice(displayRecordsIndex, endRec);
                  $( 'span#paginatorCount' ).text( page );
                  $( '#paginatorMaxValue' ).text( totalPages );
                  $( '#pagination' ).children( 'li' ).addClass( 'ripple w-lighter' );
                  showCardsPurchasable();

              }
            }).children( 'li' ).addClass( 'ripple w-lighter' );

        }

      function addCartObj( value ){

        function pushObject( val, status ){

            switch (status) {
                case true:                 

                    for (let i = 0; i < records.length; i++) {
                            
                        if ( value === records[i].productID ) {
            
                            cartObject.push( records[i] );
            
                            cartObject[x].id = x;
                            cartObject[x].total =  cartObject[x].cant * cartObject[x].unitPrice;
                            
                            x++;
                        }
                    }

                    break;
            
                default:

                    var compare = '';

                    compare = incrementRepeat( val );
                    

                    if( compare === true ){
                    
                        createElements( cartObject, false );
    
                    }else{
                        
                        newelement( val );
        
                        createElements( cartObject, false );
        
                    }

                    break;
            }
            
        }

        function incrementRepeat( key ){

            for (let i = 0; i < cartObject.length; i++) {
                    
                if ( key === cartObject[i].productID ) {
                    
                    cartObject[i].cant += 1;

                    return true;

                }                   
    
            } 
            
            return false;

        }

        function newelement( key ){

            for (let i = 0; i < records.length; i++) {
                            
                if ( key === records[i].productID ) {
    
                    cartObject.push( records[i] );
    
                    cartObject[x].id = x;
                    cartObject[x].total =  cartObject[x].cant * cartObject[x].unitPrice;
                    
                    x++;
                }
            }

        }

        if ( cartObject.length === 0 && value.length > 0) {
            
            pushObject( value, true );
            
            createElements( cartObject, false );

        } else { 

            pushObject( value, false );
            
            createElements( cartObject, false );

        }

        

      }

      function searchObject( val ){

        for (let i = 0; i < purchasableObj.length; i++) {

            if (val === purchasableObj[i]) {

                return true;
            }

        }

        return false;
      }

      function showPurchaseObj( params ) {

        if (purchasableObj.length === 0) {

            purchasableObj.push( params );

            addCartObj( params );

        } else if( searchObject( params ) === true ){

            addCartObj( params );
            
        }else{

            addCartObj( params );

        }

      }

      
      function createElements( obj, notShow ){

        if ( notShow === false ) {

            $( 'div.cartItems' ).text( '' );

            for ( var i = 0; i < obj.length; i++ ) {

        
                $( 'div.cartItems' ).each( function(){
            
                    $( this ).append( 
                
                        '<div class="item">'+
                            '<div class="itemProperty">'+
                            '<div class="ImageIcon">'+
                
                            '</div>'+
                            '<div class="itemInfo">'+
                                '<div class="infoCart">'+
                                    '<span class="w-bold" id="productName">'+ obj[i].name + ' </span>'+
                                    '<span class="productReference"> Ref: <span class="w-bold" id="productReference">'+ obj[i].productID +'</span></span>'+
                                '</div>'+
                                '<div class="infoCart">'+
                                    '<div class="inputCart">'+
                                    '<button type="button" id="subtractCant"><i class="fas fa-minus"></i></button>'+
                                    '<input type="text" id="itemCant" class="w-bold" maxlength="3" value="'+ obj[i].cant +'"/>'+
                                    '<button type="button" id="addCant"><i class="fas fa-plus"></i></button>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="infoCart">'+
                                    '<span id="productPrice">'+ Dinero({ amount: convertToDineroFormat( obj[i].unitPrice ) }).multiply( obj[i].cant ).toFormat( '$0,0' ) +'</span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="removeItem">'+
                                '<i class="fas fa-trash-alt removeItem"></i>'+
                            '</div>'+
                            '</div>'+
                        '</div>'
                
                    );
                
                    calcTotalPay();
                });               
                
            }               

        } else {

            createElements( obj, false);

        }

        function calcTotalPay(){

            $( 'span#cantProduct' ).each(function(){

                var getParent = $( this ).parent();
    
                $( getParent ).each(function(){
                    
                    var offsetParent = $( this ).parent();
    
                    $( offsetParent ).each(function(){
    
                        var parentNode = $( this ).parent().get(0).previousElementSibling;
    
                        var getChildren = parentNode;
    
                        var childCollection = getChildren.children;// Show Colection Items.                
    
                        totalToPay = 0;
    
                        $( 'span#cantProduct' ).text( childCollection.length );
                        $( 'p#shoppingCuantity' ).text( childCollection.length );
    
                        for (var i = 0; i < childCollection.length; i++) {
                                
                            totalToPay+= convertToDineroFormat( obj[i].total );
    
                        }
    
                        $( 'span#totalPrice' ).text( Dinero({ amount: totalToPay }).toFormat( '$0,0' ) );
                        
                    });
                    
                });           
                
            });

        }

        calcTotalPay();

        $( 'button#addCant' ).click(function(){
    
            var contentcurrentValue = $( this ).parent( '.inputCart' ).get( 0 );
            var currentValue = contentcurrentValue.children.item( 1 );
            var maxValue = 999;            
        
            if ( !currentValue.value )
            {
        
            console.log( 'No posee un valor numérico para incrementar.' );
            
        
            }else{
            
        
            $( currentValue ).each(function(){
        
                if( $( this ).val() <= maxValue - 1  && $( this ).val() > 0 )
                {
        
                var newVal = (parseInt( currentValue.value ) + 1);
        
                var getObjElement = $( this ).parent( '.inputCart' ).get(0).parentElement.previousElementSibling.lastElementChild.children[0];
        
                $( this ).val( newVal );
                
        
                for (var i = 0; i < obj.length; i++) {
            
                    if (obj[i].productID === getObjElement.innerText) {
                    
                    obj[i].cant = newVal;
        
                    var getCurrencyText = $( this ).parent( '.inputCart' ).get( 0 ).parentElement.nextElementSibling;
        
                    $( getCurrencyText ).each(function(){
        
                        $( this ).text( Dinero({ amount: convertToDineroFormat( obj[i].unitPrice ) }).multiply( obj[i].cant ).toFormat( '$0,0' ) );
                        
                        var replacePrice = convertToDineroFormat( obj[i].unitPrice ) * obj[i].cant;

                        obj[i].total = removeDineroFormat( replacePrice );
                        
                        var currencyParent = $( this ).parent();

                        $( currencyParent ).each(function(){

                            var currencyParentNode = $( this ).parent();

                            $( currencyParentNode ).each(function(){

                                var currencyParentWrap = $( this ).parent();

                                $( currencyParentWrap ).each(function(){

                                    var currencyParentContent = $( this ).parent();

                                    var currencyParentChildren = currencyParentContent.children();

                                    var getTotalPay = 0;
                                    
                                    for (var i = 0; i < currencyParentChildren.length; i++) {
                                        
                                        getTotalPay += convertToDineroFormat( obj[i].total );
      
                                    }
                                    
                                    $( 'span#totalPrice' ).text( Dinero({ amount: getTotalPay }).toFormat( '$0,0' ) );                                    

                                });

                            });

                        });
                        
                    });
        
                    }
                    
                }
        
                }
        
            });
        
            }    
        
        });

        $( 'button#subtractCant' ).click(function(){
    
            var contentcurrentValue = $( this ).parent( '.inputCart' ).get( 0 );
            var currentValue = contentcurrentValue.children.item( 1 );
        
            if ( !currentValue.value )
            {
        
            console.log( 'No posee un valor numérico para incrementar.' );
            
        
            }else{
        
            $( currentValue ).each(function(){
        
                if( $( this ).val() > 1 )
                {
                var newVal = (parseInt( currentValue.value ) - 1);
        
                var getObjElement = $( this ).parent( '.inputCart' ).get(0).parentElement.previousElementSibling.lastElementChild.children[0];
        
                $( this ).val( newVal );
                
        
                for (var i = 0; i < obj.length; i++) {
            
                    if ( obj[i].productID === getObjElement.innerText ) {
                    
                    obj[i].cant = newVal;
        
                    var getCurrencyText = $( this ).parent( '.inputCart' ).get( 0 ).parentElement.nextElementSibling;
        
                    $( getCurrencyText ).each(function(){
        
                        $( this ).text( Dinero({ amount: convertToDineroFormat( obj[i].unitPrice ) }).multiply( obj[i].cant ).toFormat( '$0,0' ) );

                        var replacePrice = convertToDineroFormat( obj[i].unitPrice ) * obj[i].cant;

                        obj[i].total = removeDineroFormat( replacePrice );
                        
                        var currencyParent = $( this ).parent();

                        $( currencyParent ).each(function(){

                            var currencyParentNode = $( this ).parent();

                            $( currencyParentNode ).each(function(){

                                var currencyParentWrap = $( this ).parent();

                                $( currencyParentWrap ).each(function(){

                                    var currencyParentContent = $( this ).parent();

                                    var currencyParentChildren = currencyParentContent.children();

                                    var getTotalPay = 0;
                                    
                                    for (var i = 0; i < currencyParentChildren.length; i++) {
                                        
                                        getTotalPay += convertToDineroFormat( obj[i].total );
      
                                    }
                                    
                                    $( 'span#totalPrice' ).text( Dinero({ amount: getTotalPay }).toFormat( '$0,0' ) );                                    

                                });

                            });

                        });
                        
        
                    });
        
                    }
                    
                }
        
                }
        
            });
        
            }    
        
        });

        $( 'i.removeItem' ).click(function(){

            var getLocalParent = $( this ).parent();

            $( getLocalParent ).each(function(){

                var itemParent = $( this ).parent();

                var getParentChild = itemParent.children().get(1);

                var getParentChildItems = getParentChild.children.item(0);

                var getItemsChildren = getParentChildItems.children.item(1).firstElementChild;

                for (var i = 0; i < obj.length; i++) {
                    
                    if ( obj[i].productID === getItemsChildren.innerText ) {

                        obj.splice(i, 1);                  

                        $( 'div.cartItems' ).text('');

                        createElements( obj, true );

                    }

                }               

            });       

        });

    };
    
    createElements( cartObject, false );
  //End Region

  //Efects

  //End Region
});