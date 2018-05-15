if ($('#status_id').length) {

  $('#check_number, #check_type, #bank').parent().dependsOn({
    // The selector for the depenency
    '#status_id': {
      values: ['4']
    }
  });
}
