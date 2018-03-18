@extends('pages.main')

@push('styles')
	<style>
		.add-notice-form-container {
			padding:70px;
		}
		.error-message {
			font-weight: bold;
			color: red;
			font-size: 20px;
		}
	</style>
	{{ Html::style('css/toast.css') }}
@endpush

@section('content')
  @parent
  <div class="add-notice-form-container">
    <div class="container">
      <h2 class="text-center">Edit Notice</h2>
      <form class="" onsubmit="updateNotice(event)">
				{{ csrf_field() }}
        <div class="form-group">
          <label for=""><strong>Title</strong></label>
          <input id="title" name="title" type="text" class="form-control" value="{{ $notice->title }}">
					<p class="error-message"></p>
				</div>
        <div class="form-group">
          <label for=""><strong>Description</strong></label>
          <textarea id="description" name="description" class="form-control" rows="25" cols="80">{{ $notice->description }}</textarea>
					<p class="error-message"></p>
        </div>
				<div class="form-group text-center">
				  <input type="submit" value="Update Notice" class="btn btn-outline-primary" style="width:200px;">
					<p style="clear:both;"></p>
				</div>
      </form>
    </div>
  </div>
@endsection

@component('components.success-alert')
	Notice has been updated successfully!
@endcomponent

{{-- updating  --}}
@push('scripts')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>
		function updateNotice(e) {
			e.preventDefault();
			// use this when you are using ajax call to send tinymce content
			// to the laravel controller...
			tinyMCE.triggerSave();
			var title = $("#title").val();
			var description = $("#description").val();
			var _token = $("input[name='_token']").val();

			// console.log(title + '\n' + description);
			var fd = new FormData();
			fd.append('_token', _token);
			fd.append('title', title);
			fd.append('description', description);

			$.ajax({
				url: '{{ route('notices.update', $notice->id) }}',
				type: 'POST',
				data: fd,
				contentType: false,
				processData: false,
				success: function(data) {
					console.log(data);
					var em = document.getElementsByClassName("error-message");

					// after returning from the controller we are clearing the
					// previous error messages...
					for(i=0; i<em.length; i++) {
						em[i].innerHTML = '';
					}

					// if work is stored in database successfully, then show the
					// success toast...
					if(data === "success") {
						var x = document.getElementById("snackbar");
						x.className = "show";
						setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
					}

					// Showing error messages in the HTML...
					if(typeof data.error != 'undefined') {
						if(typeof data.title != 'undefined') {
							em[0].innerHTML = data.title[0];
						}
						if(typeof data.description != 'undefined') {
							em[1].innerHTML = data.description[0];
						}
					}
				}
			});
		}
	</script>
@endpush

@push('scripts')
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script>
	  var editor_config = {
	    path_absolute : "{{ URL::to('/') }}/",
	    selector: "textarea",
	    plugins: [
	      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
	      "searchreplace wordcount visualblocks visualchars code fullscreen",
	      "insertdatetime media nonbreaking save table contextmenu directionality",
	      "emoticons template paste textcolor colorpicker textpattern"
	    ],
	    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
	    relative_urls: false,
	    file_browser_callback : function(field_name, url, type, win) {
	      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
	      var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

	      var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
	      if (type == 'image') {
	        cmsURL = cmsURL + "&type=Images";
	      } else {
	        cmsURL = cmsURL + "&type=Files";
	      }

	      tinyMCE.activeEditor.windowManager.open({
	        file : cmsURL,
	        title : 'Filemanager',
	        width : x * 0.8,
	        height : y * 0.8,
	        resizable : "yes",
	        close_previous : "no"
	      });
	    }
	  };

	  tinymce.init(editor_config);
	</script>
@endpush
