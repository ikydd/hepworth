
(function($){

  function makeDraggable(table) {
    $(table).find('tbody tr').draggable({
      handle : 'td span.tabledrag-handle',
      helper: 'clone',
      axis : 'y',
      revert : 'invalid'
    });
  }
  
  function makeDroppable(table) {
    $(table).find('tbody').droppable({
    });
  }

  $(document).ready(function(){
    $('.tabledrag-table').each(function(index, element){
      $(element).tableDnD({
        onDrop : function(table, row){
          $(table).find('caption').css({'visibility': 'visible'});
          var weight = 0;
          $(table).find('tbody tr td.tabledrag-weight input').each(function(index, element){
            element.setAttribute('value', weight);
            weight++;
          });
        }
      });
    });
  });

})(jQuery);