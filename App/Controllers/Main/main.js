$( document ).ready(function(){

  // Vars
  var range = document.getElementById( 'Price' );
  var currency = $( 'span#minPrice' );
  var maxPrice = $( 'span#maxPrice' );
  
  var $pagination = $( '#pagination' ),
      totalRecords = 0,
      records = [],
      displayRecords = [],
      recPerPage = 12,
      page = 1,
      totalPages = 0;

  var filterWith = {
    category : "All",
    colection : "All",
    price : Dinero({ amount: 10000000 }).toFormat( '$0,0' )
  }    

  var purchasableObj = [];


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
                  $( '#itemPage' ).text( page );
                  $( '#paginatorMaxValue' ).text( totalPages );
                  $( '#pagination' ).children( 'li' ).addClass( 'ripple w-lighter' );
                  showCardsPurchasable();

              }
            }).children( 'li' ).addClass( 'ripple w-lighter' );

        }

      function showPurchaseObj( params ) {
        
        purchasableObj.push( params );

        console.log( purchasableObj );   

        return purchasableObj;

      }
    

  //End Region

  //Efects

  //End Region
});