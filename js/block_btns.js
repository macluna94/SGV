// Funcion de seleccion de columna
$(document).ready(function() {
  var table = $('#example').DataTable();
  $('#example tbody').on( 'click', 'tr', function () {
    // Seleccion de columna
    if ( $(this).hasClass('selected') ) {
      $(this).removeClass('selected');
    }
    else {
      table.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
    }

    // Bloque de boton

    if (  $('#editar').hasClass('disabled') && $('#eliminar').hasClass('disabled') && $('#aprobar').hasClass('disabled') && $('#denegar').hasClass('disabled') && $('#imprimir').hasClass('disabled') ) {
      $('#editar').removeClass('disabled');
      //$('#agregar').removeClass('disabled');
      $('#eliminar').removeClass('disabled');
      $('#aprobar').removeClass('disabled');
      $('#denegar').removeClass('disabled');
      $('#imprimir').removeClass('disabled');
    }
    else{
      $('#editar').removeClass('active');
      //$('#agregar').removeClass('active');
      $('#eliminar').removeClass('active');
      $('#aprobar').removeClass('active');
      $('#denegar').removeClass('active');
      $('#imprimir').removeClass('active');

      $('#editar').addClass('disabled');
      //$('#agregar').addClass('disabled');
      $('#eliminar').addClass('disabled');
      $('#aprobar').addClass('disabled');
      $('#denegar').addClass('disabled');
      $('#imprimir').addClass('disabled');
    }
  } );

  $('#button').click( function () {
    table.row('.selected').remove().draw( false );
  } );
} );
