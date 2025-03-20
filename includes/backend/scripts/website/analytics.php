<!DOCTYPE html>
<html>
<head>
  <title>Google Analytics Data</title>
  <!-- Load the Google Analytics Reporting API library -->
  <script src="https://apis.google.com/js/platform.js"></script>
</head>
<body>
  <h1>Google Analytics Data</h1>
  <div id="data-container">
    <!-- The fetched data will be displayed here -->
  </div>

  <script>
    // Replace with your own client ID and API key
    const CLIENT_ID = '109357127420543273415';
    const API_KEY = 'AIzaSyAjMV74EvcWHUwlYrHUIiXM9VVb0LXZzho';

    // The API discovery URL
    const DISCOVERY_URL = 'https://analyticsreporting.googleapis.com/$discovery/rest';

    // Create a new Google Analytics Reporting API instance
    gapi.analytics.ready(function() {
      gapi.analytics.auth.authorize({
        container: 'auth-button',
        clientid: CLIENT_ID,
      });

      const report = new gapi.analytics.report.Data({
        query: {
          ids: 'ga:299570991',
          metrics: 'ga:pageviews',
          'start-date': '30daysAgo',
          'end-date': 'today',
        },
      });

      report.on('success', function(response) {
        const data = response.data.rows;
        const totalPageviews = data[0][0]; // Total pageviews
        
        const dataContainer = document.getElementById('data-container');
        dataContainer.innerHTML = `<p>Total Pageviews: ${totalPageviews}</p>`;
      });

      report.execute();
    });
  </script>
</body>
</html>
