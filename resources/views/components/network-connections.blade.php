<div class="row justify-content-center mt-5">
  <div class="col-12">
    <div class="card shadow  text-white bg-dark">
      <div class="card-header">Coding Challenge - Network connections</div>
      <div class="card-body">
        <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
          <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" {{Request::path() === 'home' ? 'checked':''}} >
          <label onclick="showTab('{{url('home')}}')" class="btn btn-outline-primary" for="  " id="get_suggestions_btn">Suggestions (<span class="suggestion_count"></span>)</label>

          <input onclick="showTab('{{url('sent-requests')}}')" type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" {{Request::path() === 'sent-requests' ? 'checked':''}}>
          <label class="btn btn-outline-primary" for="btnradio2" id="get_sent_requests_btn">Sent Requests (<span class="request_count"></span>)</label>

          <input onclick="showTab('{{url('received-requests')}}')" type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off" {{Request::path() === 'received-requests' ? 'checked':''}}>
          <label class="btn btn-outline-primary" for="btnradio3" id="get_received_requests_btn">Received
            Requests(<span class="received_count"></span>)</label>

          <input onclick="showTab('{{url('connections')}}')" type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off" {{Request::path() === 'connections' ? 'checked':''}}>
          <label class="btn btn-outline-primary" for="btnradio4" id="get_connections_btn">Connections (<span class="collection_count"></span>)</label>
        </div>
        <hr>
        <div id="content">
          @if(Request::path() === 'home')
              <x-suggestion />
          @endif

          @if(Request::path() === 'sent-requests')
              <x-request :type="'send'" />
          @endif

          @if(Request::path() === 'received-requests')
              <x-request :type="'received'" />
          @endif

          @if(Request::path() === 'connections')
              <x-connection />
          @endif
        </div>
        <div id="skeleton">
          @for ($i = 0; $i < 10; $i++)
            <x-skeleton  />
          @endfor
        </div>
        <div class="d-flex justify-content-center mt-2 py-3" id="load_more_btn_parent">
            <button class="btn btn-primary load_more" id="load_more_btn">Load more</button>
        </div>
      </div>
    </div>
  </div>
</div>
