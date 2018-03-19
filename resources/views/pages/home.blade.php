@extends('pages.main')

@section('ActiveHome', 'active')

{{-- Custom external CSS --}}
@push('styles')
    {{-- {{ Html::style('css/home/styles.css') }} --}}
    {{ Html::style('css/home/header.slider.css') }}
    {{ Html::style('css/home/about-us.css') }}
    {{ Html::style('css/home/our-work.css') }}
    {{ Html::style('css/toast.css') }}
@endpush

{{-- Header slider JS --}}
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{ asset('js/jssor.slider-27.0.3.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/header.slider.js') }}" type="text/javascript"></script>
@endpush

{{-- Content --}}
@section('content')
    {{-- background image --}}
    <img class="our-work-bg" src="{{ asset('images/our_work_bg.jpg') }}" alt="">
    {{-- Slider & Navigation Bar --}}
    <header>
      {{-- Slider --}}
      @includeif('partials.slider')
      {{-- Navigation Bar --}}
      @parent
    </header>



    {{-- About US Section --}}
    <section id="aboutus" class="about-us">
      <div class="container">
        <h2>
            About US
            @if (Auth::check())
            <button onclick="showAboutUsText()" type="button" class="btn btn-outline-warning btn-lg pull-right" data-toggle="modal" data-target="#aboutUsUpdateModal"><i class="fa fa-pencil" aria-hidden="true"></i> Update</button>
            @endif
        </h2>
        <p id="aboutUsParagraph">{{ $aboutUs[0]->description }}</p>
      </div>
    </section>



    {{-- Our Work Section --}}
    <div id="ourWork" class="our-work">
      <div class="cover"></div>
      <div class="our-work-panels">
        <h2 class="text-center">
          OUR WORK
          <span class="pull-right">
            @if (Auth::check())
            <button onclick="window.location.href='{{ route('works.create') }}'" type="button" name="button" class="btn btn-outline-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Work</button>
            @endif
          </span>
        </h2><br><br>
        <div id="works" class="row">
            @foreach ($works as $work)
            <div class="col-md-4">
              <div class="card work-panel">
                <img class="card-img-top" src="{{ asset('images/work-images/' . $work->image )}}" alt="Card image cap">
                <div class="card-body">
                  <h5 class="card-title card-caption">
                    <span>{{ (strlen($work->title) > 15) ? substr($work->title, 0, 15) . '...' : $work->title }}</span>
                    @if (Auth::check())
                    <span class="pull-right">
                      <button type="button" class="btn btn-outline-warning btn-sm" onclick="window.location.href='{{ route('works.edit', [$work->id]) }}'"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>
                      <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteWork({{ $work->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                    </span>
                    @endif
                  </h5>

                  <p class="card-text">{!! $work->summary !!}</p>
                  <a href="{{ route('works.show', [$work->id]) }}" class="btn btn-outline-primary"><i class="fa fa-info-circle"></i> Read More</a>
                </div>
              </div>
            </div>
            @endforeach
        </div> {{-- .row --}}
      </div>
    </div>

    {{-- Contact us & Notice Section --}}
    @includeif('partials.contact-notice', ['notices' => $notices])
@endsection


@if(Auth::check())
    <!-- About us update modal -->
    <div id="aboutUsUpdateModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" style="font-weight:bold;text-transform:uppercase;">Edit About US</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form id="aboutUsForm">
                {{ csrf_field() }}
                <div class="form-group">
                  <textarea id="aboutUsTextArea" class="form-control" name="name" rows="8" cols="80"></textarea>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" name="button" class="btn btn-outline-warning" onclick="updateAboutUs()"><i class="fa fa-save"></i> Save Changes</button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
          </div>
        </div>

      </div>
    </div>
@endif


@push('scripts')
    <script>
        // showing about us description when clicked on update button...
        function showAboutUsText() {
            $.get(
                'aboutus-text',
                function(data) {
                    document.getElementById('aboutUsTextArea').value = data.description;
                    console.log(data);
                }
            );
        }

        // updating about us text...
        function updateAboutUs() {
            var aboutUs = document.getElementById("aboutUsTextArea").value;
            var token = $("input[name='_token']").val();
            $.post(
                'about-us',
                {
                    'description': aboutUs,
                    '_token': token
                },
                function(data) {
                    // refresh the section after updating so that you can
                    //  see the change in just after uddating the about us
                    $("#aboutUsParagraph").load(location.href + " #aboutUsParagraph");
                    // document.getElementById("aboutUsForm").reset();
                    // fades in a toast for 3 seconds...
                    var x = document.getElementById("snackbar");
                    x.innerHTML = "Successfully updated!";
                    x.className = "show";
                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
                }
            );
        }

        // deleting work...
        function deleteWork(id) {
            var c = confirm("Are you sure you want to delete this item?");

            if(c == true) {
                $.ajax({
                    url: 'works/delete/' + id,
                    type: 'GET',
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        if(data === "success") {
                            $("#works").load(location.href + " #works");
                            var x = document.getElementById("snackbar");
                            x.innerHTML = "Successfully deleted!";
      						x.className = "show";
      						setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
                        }
                    }
                });
            }

        }
    </script>
@endpush

{{-- Toast component fires after about us section update --}}
@if(Auth::check())
  {{-- Success Alert --}}
  <div id="snackbar">
  </div>
@endif
