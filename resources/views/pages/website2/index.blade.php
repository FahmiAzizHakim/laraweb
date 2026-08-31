@extends('layout.website2.hero')
@section('content')


{{-- The landing page is assembled from the web_sections table
     (Website > Sections): it decides which blocks appear and in what order.
     Each row's section_key names the partial under sections/. --}}
@foreach(($sections ?? collect()) as $section)
  @includeIf('pages.website2.sections.' . $section->section_key)
@endforeach

@endsection

@section('script')
<script>
  $(document).ready(function() {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $('#from_province').select2({
      ajax: {
        type: 'POST', // Use POST method
        url: '/provinces', // Replace with your API endpoint
        dataType: 'json',
        delay: 250,
        headers: {
          'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
        data: function (params) {
          return {
            prov: params.term, // Use 'name' as the search term parameter
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data.data, function (item) {
              return {
                id: item.PROVINCE_NAME, // Use 'PROVINCE_NAME' as the id
                text: item.PROVINCE_NAME // Display 'PROVINCE_NAME' as the text
              };
            })
          };
        },
        cache: true
      },
      placeholder: 'Search for a province',
      minimumInputLength: 4,
    });

    $('#from_city').select2({
      ajax: {
        type: 'POST', // Use POST method
        url: '/cities', // Replace with your API endpoint
        dataType: 'json',
        delay: 250,
        headers: {
          'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
        data: function (params) {
          return {
            prov: $("#from_province").val(), // Use 'name' as the search term parameter
            city: params.term, // Use 'name' as the search term parameter
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data.data, function (item) {
              return {
                id: item.CITY_NAME, // Use 'CITY_NAME' as the id
                text: item.CITY_NAME // Display 'CITY_NAME' as the text
              };
            })
          };
        },
        cache: true
      },
      placeholder: 'Search for a city',
      minimumInputLength: 4,
    });

    $('#origin').select2({
      ajax: {
        type: 'POST', // Use POST method
        url: '/geo', // Replace with your API endpoint
        dataType: 'json',
        delay: 250,
        headers: {
          'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
        data: function (params) {
          return {
            prov: $("#from_province").val(), // Use 'name' as the search term parameter
            city: $("#from_city").val(), // Use 'name' as the search term parameter
            name: params.term, // Use 'name' as the search term parameter
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data.data, function (item) {
              return {
                id: item.CODE, // Use 'CITY_NAME' as the id
                text: item.NAME // Display 'CITY_NAME' as the text
              };
            })
          };
        },
        cache: true
      },
      placeholder: 'Search for Address',
      minimumInputLength: 4,
    }); 


    $('#to_province').select2({
      ajax: {
        type: 'POST', // Use POST method
        url: '/provinces', // Replace with your API endpoint
        dataType: 'json',
        delay: 250,
        headers: {
          'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
        data: function (params) {
          return {
            prov: params.term, // Use 'name' as the search term parameter
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data.data, function (item) {
              return {
                id: item.PROVINCE_NAME, // Use 'PROVINCE_NAME' as the id
                text: item.PROVINCE_NAME // Display 'PROVINCE_NAME' as the text
              };
            })
          };
        },
        cache: true
      },
      placeholder: 'Search for a province',
      minimumInputLength: 4,
    });

    $('#to_city').select2({
      ajax: {
        type: 'POST', // Use POST method
        url: '/cities', // Replace with your API endpoint
        dataType: 'json',
        delay: 250,
        headers: {
          'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
        data: function (params) {
          return {
            prov: $("#to_province").val(), // Use 'name' as the search term parameter
            city: params.term, // Use 'name' as the search term parameter
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data.data, function (item) {
              return {
                id: item.CITY_NAME, // Use 'CITY_NAME' as the id
                text: item.CITY_NAME // Display 'CITY_NAME' as the text
              };
            })
          };
        },
        cache: true
      },
      placeholder: 'Search for a city',
      minimumInputLength: 4,
    });

    $('#destination').select2({
      ajax: {
        type: 'POST', // Use POST method
        url: '/geo', // Replace with your API endpoint
        dataType: 'json',
        delay: 250,
        headers: {
          'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
        data: function (params) {
          return {
            prov: $("#to_province").val(), // Use 'name' as the search term parameter
            city: $("#to_city").val(), // Use 'name' as the search term parameter
            name: params.term, // Use 'name' as the search term parameter
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data.data, function (item) {
              return {
                id: item.CODE, // Use 'CITY_NAME' as the id
                text: item.NAME // Display 'CITY_NAME' as the text
              };
            })
          };
        },
        cache: true
      },
      placeholder: 'Search for Address',
      minimumInputLength: 4,
    }); 

    function fetchPriceData() {
      $('#price-result').empty();
      $.ajax({
        url: '/rates', // Your Laravel endpoint
        method: 'POST',
        contentType: 'application/json',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        data: JSON.stringify({
          from: $("#origin").val(), // Use 'name' as the search term parameter
          to: $("#destination").val(), // Use 'name' as the search term parameter
          weight: $("#weight").val(), // Use 'name' as the search term parameter
        }),
        success: function(response) {
          $('#price-result').empty(); // Clear existing results
          if(response.data && response.data != undefined) {
            let data = response.data
            // Assume the response is an array of price data
            // Function to format the rate as IDR currency
            function formatCurrency(rate) {
              return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
              }).format(rate);
            }

            // Assume the response is an array of price data
            data.forEach(function(item) {
              var priceHtml = `
                <div class="col-md-4 col-sm-12 price-tag">
                  <div class="price-card">
                    <label class="price-service">${item.service_name}</label>
                    <label class="price-rate">${formatCurrency(item.rate)}</label>
                    <label class="price-leadtime">estimate: ${item.leadtime} days</label>
                  </div>
                </div>
              `;
              $('#price-result').append(priceHtml);
            });
          } else {
            alert("Price not Available in System")
          }
        },
        error: function(xhr, status, error) {
          alert("price not found")
        }
      });
    }

      $('#fetch-price').click(function() {
        fetchPriceData();
      });

      $('#track-button').click(function() {
        var u_id = $("#cn_no").val(); // Get the user ID from data attribute
        var url = '/tracking?cn_no=' + u_id; // Construct the URL

        // Open a new tab and focus on it
        var newTab = window.open(url, '_blank');
        newTab.focus();
      });
  });
</script>
@endsection