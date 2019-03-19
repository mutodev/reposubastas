if ($('#status_id').length) {

  require('Select2');

  $('#check_number, #check_type, #bank, #check_amount, #optioned_by, #optioned_approved_at, #optioned_end_at, #optioned_method, #financing_bank, #financing_phone, #financing_contact').parent().dependsOn({
    // The selector for the depenency
    '#status_id': {
      values: ['4', '5']
    }
  });

  $('#optioned_price').parent().dependsOn({
    // The selector for the depenency
    '#status_id': {
      values: ['2', '3', '4', '5']
    }
  });

  $('#cancel_reason').parent().dependsOn({
    // The selector for the depenency
    '#status_id': {
      values: ['6']
    }
  });

  $('#user_number').parent().dependsOn({
    // The selector for the depenency
    '#status_id': {
      values: ['2', '3', '4', '5']
    }
  });

  $('#sold_closing_at').parent().dependsOn({
    // The selector for the depenency
    '#status_id': {
      values: ['5']
    }
  });

  // console.log($('#optioned_by').data('data'));
  //
  // $('#optioned_by').select2({
  //   width: '400',
  //   ajax : {
  //     url : '/api/users',
  //     dataType : 'json',
  //     delay : 200,
  //     data : function(params){
  //       return {
  //         q : params.term,
  //         page : params.page,
  //       };
  //     },
  //     processResults : function(data, params){
  //       params.page = params.page || 1;
  //       return {
  //         results : data.data,
  //         pagination: {
  //           more : (params.page  * 10) < data.total
  //         }
  //       };
  //     }
  //   },
  //   data: $('#optioned_by').data('data') ? [$('#optioned_by').data('data')] : [],
  //   initSelection: function(element, callback) {
  //
  //     var id = $(element).val();
  //     if(id !== "") {
  //       $.ajax("/api/users", {
  //         data: {id: id},
  //         dataType: "json"
  //       }).done(function(data) {
  //         callback(data);
  //       });
  //     }
  //   },
  //   minimumInputLength : 1,
  //   templateResult : function (repo){
  //     if(repo.loading) return repo.name;
  //     var markup = '#'+repo.id + ' ' +repo.name + (repo.phone ? ' ('+repo.phone+')' : '');
  //     return markup;
  //   },
  //   templateSelection : function(repo)
  //   {
  //     return repo.name;
  //   },
  //   escapeMarkup : function(markup){ return markup; }
  // });
  //
  // $('#optioned_by').trigger('change');
}
