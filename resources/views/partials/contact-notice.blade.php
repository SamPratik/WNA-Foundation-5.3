<section id="contactNotice" class="contact-notice">
  <div class="container contact-notice-container">
    <div class="row">
      <div class="col-md-6 notice">
        <h2>
          Notices
          @if (Auth::check())
          <a style="text-transform:capitalize;letter-spacing:0px;" class="btn btn-outline-primary btn-sm pull-right" href="{{ route('notices.create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Add Notice
          </a>
          @endif
        </h2>
        <div id="notices" class="list-group notice-list">
          @foreach ($notices as $notice)
            <a href="#contactNotice" class="list-group-item list-group-item-action flex-column align-items-start notice-link">
              <div class="d-flex w-100 justify-content-between">
                <p class="mb-1">{{ $notice->title }}</p>
              </div>
              <p>
                @php
                  $time = strtotime($notice->updated_at)
                @endphp
                <small><strong>{{ date('d F, Y',$time) }}</strong></small>

                <span class="pull-right">
                  <button class="btn btn-outline-success btn-sm" type="button" onclick="window.location.href='{{ route('notices.show', $notice->id) }}'">Details</button>
                  @if (Auth::check())
                  <button class="btn btn-outline-warning btn-sm" onclick="window.location.href='{{ route('notices.edit', [$notice->id]) }}'"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>
                  <button class="btn btn-outline-danger btn-sm" type="button" onclick="deleteNotice({{ $notice->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                  @endif
                </span>
              </p>
            </a>
          @endforeach
        </div>
      </div>
      <div class="col-md-6 contact">
        <h2>
          Contact US
          @if (Auth::check())
          <button class="btn btn-sm btn-outline-primary pull-right" type="button" data-toggle="modal" data-target="#editContactModal"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>
          @endif
        </h2>
        <div id="contactus">
          <p>Contact us and we'll get back to you within 24 hours.</p>
          <p><strong><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $contactus->address }}</strong></p>
          <p><strong><i class="fa fa-phone"></i> {{ $contactus->phone }}</strong></p>
          <p><strong><i class="fa fa-envelope" aria-hidden="true"></i> {{ $contactus->email }}</strong></p>
        </div>
        <form>
          <div class="row">
            <div class="col">
              <input type="text" class="form-control" placeholder="Your Name">
            </div>
            <div class="col">
              <input type="email" class="form-control" placeholder="Your Email">
            </div>
          </div><br>
          <div class="row">
            <div class="col">
              <textarea class="form-control" name="name" rows="5" cols="80" placeholder="Your Queries"></textarea>
            </div>
          </div><br>
          <div class="row text-center">
            <div class="col">
              <input style="width:200px;" class="btn btn-outline-primary" type="submit" name="" value="Submit">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Edit Contact Modal -->
<div class="modal fade" id="editContactModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="font-weight:bold;">Edit contact information</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form class="">
            {{ csrf_field() }}
            <div class="form-group">
              <label for=""><strong>Address</strong></label>
              <input name="address" id="address" type="text" class="form-control" value="{{ $contactus->address }}">
            </div>
            <div class="form-group">
              <label for=""><strong>Phone</strong></label>
              <input name="phone" value="{{ $contactus->phone }}" type="text" class="form-control" id="phone">
            </div>
            <div class="form-group">
              <label for=""><strong>Email</strong></label>
              <input name="email" value="{{ $contactus->email }}" id="email" type="email" class="form-control">
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button onclick="updateContactUs()" type="button" name="button" class="btn btn-outline-primary">Save Changes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
      </div>
    </div>
  </div>
</div>
</div>


{{-- Success Alert --}}
<div id="snackbar">
</div>


{{-- deleting notice --}}
@push('scripts')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
    function deleteNotice(id) {
      var c = confirm("Are you sure you want to delete this notice?");
      if(c == true) {
        $.ajax({
          url: 'notices/delete/' + id,
          type: 'GET',
          contentType: false,
          processData: false,
          success: function(data) {
            console.log(data);
            if(data === "success") {
              $("#notices").load(location.href + " #notices");
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

{{-- Updating contact us --}}
@push('scripts')
  <script>
    function updateContactUs() {
      var address = $("#address").val();
      var phone = $("#phone").val();
      var email = $("#email").val();
      var _token = $("input[name='_token']").val();

      // console.log(address + '\n' + phone + '\n' + email + '\n' + _token);

      $.post(
      '{{ route('contactus.update', 1) }}',
        {
          'address': address,
          'phone': phone,
          'email': email,
          '_token': _token
        },
        function(data) {
          console.log(data);
          if(data === 'success') {
            $("#contactus").load(location.href + " #contactus");
            var x = document.getElementById("snackbar");
            x.innerHTML = "Successfully updated!";
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
          }
        }
      );
    }
  </script>
@endpush
