var skeletonId = 'skeleton';
var contentId = 'content';
var skipCounter = 0;
var takeAmount = 10;


function getRequests(mode) {
  // your code here...
}

function getMoreRequests(mode) {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getConnections() {
  // your code here...
}

function getMoreConnections() {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getConnectionsInCommon(userId, connectionId) {
  // your code here...
}

function getMoreConnectionsInCommon(userId, connectionId) {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getSuggestions(page) {
    $("#skeleton").show();
    ajax("/get-suggestions?page=" + page, "GET", [[getSuggestionsSuccess, ["response"]]]);
}

function getMoreSuggestions() {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function sendRequest(userId, suggestionId) {
  // your code here...
}

function deleteRequest(userId, requestId) {
  // your code here...
}

function acceptRequest(userId, requestId) {
  // your code here...
}

function removeConnection(userId, connectionId) {
  // your code here...
}

function getAllCounts() {
  // Get counter for tabs
  ajax("/get-counts", "GET", [[getAllCountsSuccess, ["response"]]]);
}



$(function () {
  // getSuggestions();
});


// Success Functions
function getSuggestionsSuccess(response) {
  console.log(response);
  // alert();
  var html = "";
  for (var i = response.data.length - 1; i >= 0; i--) {
      html += `<div class="my-2 shadow  text-white bg-dark p-1" id="suggetions_${response.data[i].id}">
    <div class="d-flex justify-content-between">
      <table class="ms-1">
        <td class="align-middle">${response.data[i].name}</td>
        <td class="align-middle"> - </td>
        <td class="align-middle">${response.data[i].email}</td>
        <td class="align-middle">
      </table>
      <div>
        <button id="create_request_btn_${response.data[i].id}" data-id="${response.data[i].id}" class="btn btn-primary me-1 send_request">Connect</button>
      </div>
    </div>
  </div>`;
  }

  setTimeout(function () {
      $("#skeleton").hide();
      $("#suggestion_content").append(html);
  }, 200);

  if (response.next_page_url == null) {
      $("#load_more_btn_parent").hide();
      $("#load_more_btn").hide();
  } else {
      $("#load_more_btn_parent").show();
      $("#load_more_btn").attr("page", response.current_page + 1);
      $("#load_more_btn").attr("type", "suggestion");
  }

  getAllCounts();
}

function getRequestsSuccess(mode, response) {
  var html = "";
  for (var i = response.data.length - 1; i >= 0; i--) {
      html += `<div class="my-2 shadow text-white bg-dark p-1" id="request_${response.data[i].request_id}">
              <div class="d-flex justify-content-between">
                <table class="ms-1">
                  <td class="align-middle">${response.data[i].name}</td>
                  <td class="align-middle"> - </td>
                  <td class="align-middle">${response.data[i].email}</td>
                  <td class="align-middle">
                </table>
                <div>`;
      if (mode == "sent") {
          html += `<button id="cancel_request_btn_${response.data[i].request_id}" data-id="${response.data[i].request_id}" class="btn btn-danger me-1 withdraw_request"
                      onclick="">Withdraw Request</button>`;
      } else {
          html += `<button id="accept_request_btn_${response.data[i].request_id}" data-id="${response.data[i].request_id}" class="btn btn-primary me-1 accept_request"
                      onclick="">Accept</button>`;
      }
      html += `</div>
              </div>
            </div>`;
  }

  setTimeout(function () {
      $("#skeleton").hide();
      $("#request_content").append(html);
  }, 200);

  if (response.next_page_url == null) {
      $("#load_more_btn_parent").hide();
      $("#load_more_btn").hide();
  } else {
      $("#load_more_btn_parent").show();
      $("#load_more_btn").attr("page", response.current_page + 1);
      $("#load_more_btn").attr("type", "request");
      $("#load_more_btn").attr("mode", mode);
  }

  getAllCounts();
}

function getAllCountsSuccess(response) {
  $(".suggestion_count").text(response.suggestion);
  $(".request_count").text(response.request);
  $(".received_count").text(response.received);
  $(".collection_count").text(response.collection);
}