@extends('pages.main')

@push('styles')
  <style media="screen">
    .notice-show-container {
      padding: 70px 0px 50px 0px;
    }
    h1, h2, h3, h4, h5, h6 {
      color: #333333;
      font-weight: bold;
    }
  </style>
@endpush

@section('content')
  @parent
  <div class="notice-show-container">
    <div class="container">
      <div class="card card-block bg-faded text-center">
           <h1>{{ $notice->title }}</h1>
      </div><br>
      {!! $notice->description !!}
    </div>
  </div>
  {{-- Contact us & Notice Section --}}
  @includeif('partials.contact-notice', ['notices' => $notices])
@endsection
