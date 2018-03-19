@extends('pages.main')

@section('ActiveNews', 'active')

@push('styles')
  <style media="screen">
    ul {
      display: inline;
      float: left;
    }
  </style>
  {{ Html::style('css/news/header.css') }}
  {{ Html::style('css/news/news-container.css') }}
  {{ Html::style('css/toast.css') }}
@endpush

@section('content')
  {{-- Header section --}}
  <header>
    <img src="{{ asset('images/news-header-cropped.jpeg') }}" alt="" width="100%">
    <h1 class="header-caption">News</h1>
    @parent
  </header>

  {{-- News container section --}}
  <div class="news-container">
    <div id="news" class="container">
      <p>
        {{ $news->links() }}
        @if(Auth::check())
        <button class="btn btn-outline-primary pull-right" onclick="window.location.href='{{ route('news.create') }}'"><i class="fa fa-plus" aria-hidden="true"></i> Add News</button>
        @endif
        <p style="clear:both;"></p>
      </p>
      @php
        $i = 0;
      @endphp
      @foreach ($news as $new)
        @php
          $i++;
        @endphp
        @if ($i == 1 || ($i % 3) == 1)
        <div class="row">
        @endif
          <div class="col-md-4">
            <div class="card news">
              <img class="card-img-top" src="{{ asset('images/news-images/' . $new->image) }}" alt="Card image cap">
              <div class="card-body">
                <h5 class="card-title">{{ (strlen($new->title) > 10) ? substr($new->title, 0, 10) . '...' : $new->title }} <small class="pull-right">{{ $new->updated_at }}</small></h5>
                <p class="card-text">{{ $new->summary }}</p>
                <a href="{{ route('news.show', $new->id) }}" class="btn btn-outline-primary pull-right"><i class="fa fa-info-circle"></i> Read More</a>
                @if(Auth::check())
                <button onclick="deleteNews({{ $new->id }})" style="margin-right:5px;" class="btn btn-outline-danger pull-right" type="button" name="button"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                <button onclick="window.location.href='{{ route('news.edit', [$new->id]) }}'" style="margin-right:5px;" class="btn btn-outline-warning pull-right" type="button" name="button"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>
                @endif
              </div>
            </div>
          </div>
        @if (($i % 3) == 0)
        </div> {{-- .row --}}<br>
        @endif
      @endforeach
      @if (($i % 3) != 0)
      </div> {{-- .row --}}<br>
      @endif
      {{ $news->links() }}
    </div>
  </div>


  {{-- Contact us & Notice Section --}}
  @includeif('partials.contact-notice', ['notices' => $notices])
@endsection

{{-- Success Alert --}}
<div id="snackbar">
  News deleted successfully!
</div>

{{-- delete news ajax request to news.destroy --}}
@push('scripts')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
      function deleteNews(id) {
        var c = confirm("Are you sure you want to delete this news?");
        if(c == true) {
          $.ajax({
            url: 'news/delete/' + id,
            type: 'GET',
            contentType: false,
            processData: false,
            success: function(data) {
              if(data === 'success') {
                $("#news").load(location.href + " #news");
                var x = document.getElementById("snackbar");
                x.className = "show";
                setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
              }
            }
          });
        }
      }
  </script>
@endpush
