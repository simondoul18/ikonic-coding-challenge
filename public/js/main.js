var skeletonId = 'skeleton';
var contentId = 'content';
var skipCounter = 0;
var takeAmount = 10;


function getRequests(mode,page) {
  $("#skeleton").show();
  ajax("/get-requests/" + mode + "?page=" + page, "GET", [[getRequestsSuccess, [mode, "response"]]]);
}

function getMoreRequests(mode) {
  // Optional: Depends on how you handle the "Load more"-Functionality
  // your code here...
}

function getConnections(page) {
  $("#skeleton").show();
  ajax("/get-connections?page=" + page, "GET", [[getConnectionsSuccess, ["response"]]]);
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

function sendRequest(suggestionId) {
  var form = ajaxForm([["suggestionId", suggestionId]]);
  ajax("/send_friend_request", "POST", [[sendRequestSuccessFunction, [suggestionId, "response"]]], form);
}

function deleteRequest(requestId) {
  var form = ajaxForm([["requestId", requestId]]);
  ajax("/delete-request", "POST", [[deleteRequestSuccessFunction, [requestId, "response"]]], form);
}

function acceptRequest(requestId) {
  var form = ajaxForm([["requestId", requestId]]);
  ajax("/accept-request", "POST", [[acceptRequestSuccessFunction, [requestId, "response"]]], form);
}

function removeConnection(userId) {
  var form = ajaxForm([["userId", userId]]);
  ajax("/remove-connection", "POST", [[removeConnectionSuccessFunction, [userId, "response"]]], form);
}

function getAllCounts() {
  // Get counter for tabs
  ajax("/get-counts", "GET", [[getAllCountsSuccess, ["response"]]]);
}

function showTab(url){
  window.location.href = url;
}


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
        <button onclick="sendRequest(${response.data[i].id})" id="create_request_btn_${response.data[i].id}" class="btn btn-primary me-1 send_request">Connect</button>
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

function getAllCountsSuccess(response) {
  $(".suggestion_count").text(response.suggestion);
  $(".request_count").text(response.request);
  $(".received_count").text(response.received);
  $(".collection_count").text(response.collection);
}

function sendRequestSuccessFunction(suggestionId, response) {
  $("#suggetions_" + suggestionId).remove();
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
      if (mode == "send") {
          html += `<button id="cancel_request_btn_${response.data[i].request_id}" class="btn btn-danger me-1 withdraw_request"
                      onclick="deleteRequest(${response.data[i].request_id})">Withdraw Request</button>`;
      } else {
          html += `<button id="accept_request_btn_${response.data[i].request_id}" class="btn btn-primary me-1 accept_request"
                      onclick="acceptRequest(${response.data[i].request_id})">Accept</button>`;
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

function deleteRequestSuccessFunction(requestId) {
  $("#request_" + requestId).remove();
  getAllCounts();
}

function removeConnectionSuccessFunction(id) {
  $("#connection_" + id).remove();
  getAllCounts();
}

function acceptRequestSuccessFunction(requestId, response) {
  $("#request_" + requestId).remove();
  $("#connection_" + requestId).remove();
  getAllCounts();
}

function getConnectionsSuccess(response) {
  var html = "";
  for (var i = response.data.length - 1; i >= 0; i--) {
      html += `<div class="my-2 shadow text-white bg-dark p-1" id="connection_${response.data[i].id}">
            <div class="d-flex justify-content-between">
              <table class="ms-1">
                <td class="align-middle">${response.data[i].name}</td>
                <td class="align-middle"> - </td>
                <td class="align-middle">${response.data[i].email}</td>
                <td class="align-middle">
              </table>
              <div>
                <button style="width: 220px" id="get_connections_in_common_${response.data[i].id}" class="btn btn-primary" type="button"
                  data-bs-toggle="collapse" data-bs-target="#collapse_${response.data[i].id}" aria-expanded="false" aria-controls="collapseExample">
                  Connections in common (${response.data[i].commonFriendsCount})
                </button>
                <button onclick="removeConnection(${response.data[i].id})" id="create_request_btn_" class="btn btn-danger me-1 withdraw_request" >Remove Connection</button>
              </div>

            </div>
            <div class="collapse p-2" id="collapse_${response.data[i].id}" s>`;

      for (var j = response.data[i].commonFrnds.length - 1; j >= 0; j--) {
          html += `<div id="content_" class="p-2">
                <div class="p-2 shadow rounded mt-2 text-white bg-dark">${response.data[i].commonFrnds[j].name} - ${response.data[i].commonFrnds[j].email}</div>
              </div>`;
      }

      html += `</div>
          </div>`;
  }

  setTimeout(function () {
      $("#skeleton").hide();
      $("#connection_content").append(html);
  }, 200);

  if (response.next_page_url == null) {
      $("#load_more_btn_parent").hide();
      $("#load_more_btn").hide();
  } else {
      $("#load_more_btn_parent").show();
      $("#load_more_btn").attr("page", response.current_page + 1);
      $("#load_more_btn").attr("type", "connection");
  }

  getAllCounts();
}



$(document).ready(function () {
  $(document).on("click", ".load_more", function () {
      if ($(this).attr("type") == "suggestion") {
          getSuggestions($(this).attr("page"));
      }

      if ($(this).attr("type") == "request") {
          getRequests($(this).attr("mode"), $(this).attr("page"));
      }

      if ($(this).attr("type") == "connection") {
          getConnections($(this).attr("page"));
      }
  });
});