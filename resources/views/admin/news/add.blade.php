@extends('pages.main')
@section('meta-ajax')
	<meta name="_token" content="{{ csrf_token() }}" />
@endsection


@push('styles')
	<style media="screen">
		.add-news-form-container {
			padding: 70px 0px 50px 0px;
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
  <div class="add-news-form-container">
    <div class="container">
      <h2 class="text-center">Add Notice</h2>
			<form id="addNewsForm" class="" onsubmit="addNews(event)">
        <div class="form-group">
          <label for=""><strong>Title</strong></label>
          <input id="title" name="title" type="text" class="form-control">
					<p class="error-message"></p>
        </div>
				<div class="form-group">
					<label for=""><strong>Display image</strong></label>
					<label class="btn btn-outline-success" style="width:200px;cursor:pointer;margin-left:10px;margin-top:5px;"><input id="newsImage" name="newsImage" style="display:none;" type="file" />Choose File</label>
					<span style="margin-left:10px;">[Upload 640X400 image for better quality]</span>
					<p class="error-message"></p>
				</div>
				<div class="form-group">
          <label for=""><strong>Summary</strong></label>
          <textarea id="summary" name="summary" class="form-control" rows="5" cols="80"></textarea>
					<p class="error-message"></p>
        </div>
        <div class="form-group">
          <label for=""><strong>Description</strong></label>
          <textarea id="description" name="description" class="form-control" rows="25" cols="80"></textarea>
					<p class="error-message"></p>
        </div>
				<div class="form-group text-center">
				  <input type="submit" value="Add News" class="btn btn-outline-primary" style="width:200px;">
					<p style="clear:both;"></p>
				</div>
      </form>
    </div>
  </div>
@endsection

@component('components.success-alert')

@endcomponent

{{-- storing news in database --}}
@push('scripts')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>
		function addNews(e) {
				e.preventDefault();
				// use this when you are using ajax call to send tinymce content
				// to the laravel controller...
				tinyMCE.triggerSave();

				var file_data = $("#newsImage").prop('files')[0];
				var title = $("#title").val();
				var description = $("#description").val();
				var summary = $("#summary").val();
				// console.log(file_data);

				// creating form data object of the form...
				var fd = new FormData();
				// appending the image to the form data...
				fd.append('newsImage', file_data);
				fd.append('title', title);
				fd.append('description', description);
				fd.append('summary', summary);
				$.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            }
        });
				$.ajax({
					url: '{{ route('news.store') }}',
					data: fd,
					type: 'POST',
					contentType: false,
					processData: false,
					success: function(data) {
						// showing the response came from the laravel controller...
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
							document.getElementById("addNewsForm").reset();
							var x = document.getElementById("snackbar");
							x.innerHTML = "News added successfully!";
							x.className = "show";
							setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
						}

						// Showing error messages in the HTML...
						if(typeof data.error != 'undefined') {
							if(typeof data.title != 'undefined') {
								em[0].innerHTML = data.title[0];
							}
							if(typeof data.newsImage != 'undefined') {
								em[1].innerHTML = data.newsImage[0];
							}
							if(typeof data.summary != 'undefined') {
								em[2].innerHTML = data.summary[0];
							}
							if(typeof data.description != 'undefined') {
								em[3].innerHTML = data.description[0];
							}
						}
					}
				});

		}
	</script>
@endpush

{{-- tinyMCE scripts --}}
@push('scripts')
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script>
		var editor_config = {
			path_absolute : "{{ URL::to('/') }}/",
			selector: "textarea#description",
			plugins: [
				"advlist autolink lists link image charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars code fullscreen",
				"insertdatetime media nonbreaking save table contextmenu directionality",
				"emoticons template paste textcolor colorpicker textpattern"
			],
			toolbar: "insertfile undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
			textcolor_map: [
		    "000000", "Black",
		    "993300", "Burnt orange",
		    "333300", "Dark olive",
		    "003300", "Dark green",
		    "003366", "Dark azure",
		    "000080", "Navy Blue",
		    "333399", "Indigo",
		    "333333", "Very dark gray",
		    "800000", "Maroon",
		    "FF6600", "Orange",
		    "808000", "Olive",
		    "008000", "Green",
		    "008080", "Teal",
		    "0000FF", "Blue",
		    "666699", "Grayish blue",
		    "808080", "Gray",
		    "FF0000", "Red",
		    "FF9900", "Amber",
		    "99CC00", "Yellow green",
		    "339966", "Sea green",
		    "33CCCC", "Turquoise",
		    "3366FF", "Royal blue",
		    "800080", "Purple",
		    "999999", "Medium gray",
		    "FF00FF", "Magenta",
		    "FFCC00", "Gold",
		    "FFFF00", "Yellow",
		    "00FF00", "Lime",
		    "00FFFF", "Aqua",
		    "00CCFF", "Sky blue",
		    "993366", "Red violet",
		    "FFFFFF", "White",
		    "FF99CC", "Pink",
		    "FFCC99", "Peach",
		    "FFFF99", "Light yellow",
		    "CCFFCC", "Pale green",
		    "CCFFFF", "Pale cyan",
		    "99CCFF", "Light sky blue",
		    "CC99FF", "Plum"
		  ],
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
