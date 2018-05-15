if ($('#broker_name').length) {

  $('#broker_name, #company, #license, #phone2').parent().dependsOn({
    // The selector for the depenency
    '#type': {
      // The dependency qualifiers
      values: ['Broker']
    }
  });

  $('#spouse_name').parent().dependsOn({
    // The selector for the depenency
    '#martial_status': {
      // The dependency qualifiers
      values: ['Married']
    }
  });
}
