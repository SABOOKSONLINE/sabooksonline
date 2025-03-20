
<!DOCTYPE html>
<!--
 @license
 Copyright 2019 Google LLC. All Rights Reserved.
 SPDX-License-Identifier: Apache-2.0
-->
<html>
  <head>
    <title>Info Windows</title>
    <script src=""></script>
    <script>
      /**
       * @license
       * Copyright 2019 Google LLC. All Rights Reserved.
       * SPDX-License-Identifier: Apache-2.0
       */
      // This example displays a marker at the center of Australia.
      // When the user clicks the marker, an info window opens.
      function initMap() {
        const johannesburg = { lat: -26.2041, lng: 28.0473 };
        const sandton = { lat: -26.1076, lng: 28.0567 };
        const rosebank = { lat: -26.1438, lng: 28.0406 };
        const map = new google.maps.Map(document.getElementById("map"), {
          zoom: 11,
          center: johannesburg,
        });

        const contentString =
          '<div id="content" class="p-5">' +
          '<div id="siteNotice">' +
          "</div>" +
          '<h1 id="firstHeading" class="firstHeading">SA Books Online - Sandton</h1>' +
          '<div id="bodyContent">' +
          "<p><b>SA Books Online</b>, also referred to as <b>Ayers Rock</b>, is a large " +
          "sandstone rock formation in the southern part of the " +
          "Northern Territory, central Australia. It lies 335&#160;km (208&#160;mi) " +
          "south west of the nearest large town, Alice Springs; 450&#160;km " +
          "(280&#160;mi) by road. Kata Tjuta and Uluru are the two major " +
          "features of the Uluru - Kata Tjuta National Park. Uluru is " +
          "sacred to the Pitjantjatjara and Yankunytjatjara, the " +
          "Aboriginal people of the area. It has many springs, waterholes, " +
          "rock caves and ancient paintings. Uluru is listed as a World " +
          "Heritage Site.</p>" +
          '<p>Attribution: Uluru, <a href="https://en.wikipedia.org/w/index.php?title=Uluru&oldid=297882194">' +
          "https://en.wikipedia.org/w/index.php?title=Uluru</a> " +
          "(last visited June 22, 2009).</p>" +
          "</div>" +
          "</div>";


        const image = {
            url: "https://cdn-icons-png.flaticon.com/512/3607/3607444.png", // url
            scaledSize: new google.maps.Size(50, 50), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0, 0) // anchor
                };
                

        const infowindow = new google.maps.InfoWindow({
          content: contentString,
          ariaLabel: "Uluru",
        });

        const marker = new google.maps.Marker({
          position: sandton,
          map,
          title: "SA Books Sandton",
          icon: image,
        });

        const markerR = new google.maps.Marker({
          position: rosebank,
          map,
          title: "SA Books Rosebank",
          icon: image,
        });

        marker.addListener("click", () => {
          infowindow.open({
            anchor: marker,
            map,
          });
        });
      }

      window.initMap = initMap;
    </script>
    <style>
      /**
       * @license
       * Copyright 2019 Google LLC. All Rights Reserved.
       * SPDX-License-Identifier: Apache-2.0
       */
      /* 
       * Always set the map height explicitly to define the size of the div element
       * that contains the map. 
       */
      #map {
        height: 100%;
      }

      /* Optional: Makes the sample page fill the window. */
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjMV74EvcWHUwlYrHUIiXM9VVb0LXZzho&callback=initMap&v=weekly"
      defer
    ></script>
  </body>
</html>
    