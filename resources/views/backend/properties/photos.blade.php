@extends('layouts.app')

@section('title', "{$event->name} - ". ($model->address))

@section('stylesheets')
  <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>

  <!-- Load plupload and all it's runtimes and finally the UI widget -->
  <link rel="stylesheet" href="{{ asset('js/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css') }}" type="text/css" />


  <!-- production -->
  <script type="text/javascript" src="{{ asset('js/plupload/moxie.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/plupload/plupload.dev.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/plupload/jquery.ui.plupload/jquery.ui.plupload.js') }}"></script>

  <link href="{{ asset('css/backend.css') }}?v1" rel="stylesheet">
@endsection

@section('content')
    <div id="uploader">
        <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
    </div>
@endsection

@section('footer_scripts')
  <script type="text/javascript">
    // Convert divs to queue widgets when the DOM is ready
    // jQuery(function() {
      jQuery("#uploader").plupload({
        runtimes : 'html5,flash,silverlight',
        url : 'http://<?php echo $bucket; ?>.s3.amazonaws.com/',
        multipart: true,
        multipart_params: {
          'key': '{{ $model->id }}/${filename}', // use filename as a key
          'Filename': '{{ $model->id }}/${filename}', // adding this to keep consistency across the runtimes
          'acl': 'public-read',
          'Content-Type': 'image/jpeg',
          'AWSAccessKeyId' : '<?php echo $accessKeyId; ?>',
          'policy': '<?php echo $policy; ?>',
          'signature': '<?php echo $signature; ?>'
        },
        file_data_name: 'file',
        filters : {
          max_file_size : '10mb',
          mime_types: [
            {title : "Image files", extensions : "jpg,jpeg,png"}
          ]
        },
        // Sort files
        sortable: true,

        // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
        dragdrop: true,

        // Views to activate
        views: {
          list: true,
          thumbs: true, // Show thumbs
          active: 'thumbs'
        },
        unique_names: true,

        rename: true,

        // Flash settings
        flash_swf_url : '{{ asset('js/plupload/Moxie.swf') }}',
        // Silverlight settings
        silverlight_xap_url : '{{ asset('js/plupload/Moxie.xap') }}',

        max_files_count: 10,

        init : {

          BeforeUpload: function(up, file) {
            //Change the filename
            file.name = "{{ $model->id }}/"+file.name;

            var params = up.settings.multipart_params;
            params.key = file.name;
            params.Filename = file.name;
          },

          UploadComplete: function(up, uploadedFiles) {
            console.log(files);

            var files = {};
            var count = 1;

            jQuery.each(uploadedFiles, function( index, file ) {
              files['image'+count] = file.name;
              count++;
            });

            console.log(files);

            jQuery.ajax({
              method: "POST",
              url: "{{ route('backend.properties.photos', ['event' => $event->id, 'model' => $model->id]) }}",
              data: {
                files: files,
                "_token": "{{ csrf_token() }}"
              }
            })
            .done(function() {
              alert('Photos Saved');
              window.location.href = "{{ route('backend.properties.index', ['event' => $event->id]) }}"
            });

            console.log('[UploadComplete]');
          },
        }
      });
    //});
  </script>
@endsection
