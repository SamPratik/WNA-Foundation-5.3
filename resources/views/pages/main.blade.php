<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    @yield('meta-ajax')
    <title></title>
    {{-- Bootstrap 4 CSS --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Oswald:200,300,regular,500,600,700&subset=latin-ext,vietnamese,latin,cyrillic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- Custom CSS for Home page --}}
    {{ Html::style('css/nav-bar.css') }}
    {{ Html::style('css/contact-notice.css') }}
    {{ Html::style('css/scroll-bar.css') }}
    {{ Html::style('css/footer.css') }}
    @stack('styles')

    {{-- Bootstrap 4 JS --}}
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if( target.length ) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top
                    }, 1000);
                }
            });
        });
    </script>
    {{-- Header slider JS --}}
    @stack('scripts')
  </head>
  <body>

    @section('content')
      {{-- Naviagation bar --}}
      @includeif('partials.nav')
    @show

    {{-- Contact us & Notice Section --}}
    {{-- @includeif('partials.contact-notice') --}}


    {{-- Footer Section --}}
    @includeif('partials.footer')



    {{-- Javascript Codes --}}
    {{-- Parallex effect for header image and about us --}}
    <script type="text/javascript">
      window.onscroll = function() {
        var jssor_1 = document.getElementById('jssor_1');
        jssor_1.style.top = (window.pageYOffset)*.3 + 'px';
      };
    </script>
  </body>
</html>
