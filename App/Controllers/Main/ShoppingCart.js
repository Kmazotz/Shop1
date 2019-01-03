$( document ).ready(function(){


    //Vars
        
        var cartObject = [];

    //EndRegion

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

    //EndRegion

    //DOM

        function createElements( obj, notShow ){

            if ( notShow === false ) {

                for ( let i of obj ) {

            
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
                    
                    });  
                    
                    i+= 1;
                }               

            } else {

                createElements( obj, false);
    
            }

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

    //EndRegion

})