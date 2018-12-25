$( document ).ready(function(){

  // Vars
  var range = document.getElementById( 'Price' );
  var currency = $( 'span#minPrice' );
  var maxPrice = $( 'span#maxPrice' );

  var itemsPerPage = 25;
  var totalPages = 16;

  var filterWith = {
    category : "All",
    colection : "All",
    price : Dinero({ amount: 10000000 }).toFormat( '$0,0' )
  }

  //End Region

  //Functions

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
  })

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

  $( 'div#paginator' ).append( '<p id="paginatorInfo" class="w-bold"></p><ul id="pagination"></ul>' );
  $( 'p#paginatorInfo' ).append( 'Page <span id="paginatorCount"></span> de <span id="paginatorMaxValue"></span>' )

  $( '#pagination' ).twbsPagination({
        totalPages: totalPages,
        visiblePages: 0,
        first: '',//'<i class="fas fa-angle-double-left"></i>',
        next: '<i class="fas fa-angle-right"></i>',
        prev: '<i class="fas fa-angle-left"></i>',
        last: '',//'<i class="fas fa-angle-double-right"></i>',
        onPageClick: function (event, page) {
            $( '#itemPage' ).text('Page ' + page) + ' content here';
            $( 'span#paginatorCount' ).text( page );
            $( 'span#paginatorMaxValue' ).text( totalPages );
            $( '#pagination' ).children( 'li' ).addClass( 'ripple w-lighter' );
        }
    }).children( 'li' ).addClass( 'ripple w-lighter' );

  //End Region

  //Efects

  //End Region
})
