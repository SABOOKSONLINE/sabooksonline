<!DOCTYPE html>
<html>
<head>
  <title>Heatmap by Country</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-geo@4.3.0/build/index.umd.min.js"></script>
  <script src="https://d3js.org/d3.v7.min.js"></script>
  <style>
    body {
      background: #111;
      color: #fff;
      font-family: sans-serif;
      text-align: center;
    }
    canvas {
      max-width: 1000px;
      margin: 2rem auto;
      display: block;
    }
  </style>
</head>
<body>

<h2>🌍 Views by Country (Heatmap)</h2>
<canvas id="geoMap" width="1000" height="600"></canvas>

<script>
  // Fetch the world map data (Top JSON)
  fetch('https://unpkg.com/world-atlas@2.0.2/countries-110m.json')
    .then(res => res.json())
    .then(world => {
      // Get the countries features
      const countries = ChartGeo.topojson.feature(world, world.objects.countries).features;

      // Mapping country IDs to ISO-3 country codes
      const idToISO3 = {
        710: 'ZAF', // South Africa
        840: 'USA', // United States
        356: 'IND', // India
        528: 'NLD', // Netherlands
        76:  'BRA', // Brazil
        826: 'GBR'  // United Kingdom
      };

      // Views data for specific countries
      const viewsByCountry = {
        ZAF: 1200,
        USA: 800,
        IND: 650,
        NLD: 400,
        BRA: 300,
        GBR: 200
      };

      // Create dataset for the map, with a fallback to 0 views if no data
      const dataset = countries.map(feature => {
        const iso3 = idToISO3[feature.id];
        return {
          feature,
          value: viewsByCountry[iso3] || 0
        };
      });

      // Initialize the chart
      new Chart(document.getElementById('geoMap'), {
        type: 'choropleth',
        data: {
          datasets: [{
            label: 'Views by Country',
            data: dataset,
            outline: {
              display: true,
              color: '#444',
              lineWidth: 0.5
            }
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: function (ctx) {
                  const label = ctx.chart.data.datasets[0].data[ctx.dataIndex].feature.properties.name;
                  const value = ctx.raw.value;
                  return `${label}: ${value} views`;
                }
              }
            }
          },
          scales: {
            projection: {
              type: 'equalEarth'
            },
            color: {
              axis: 'color',
              quantize: 5,
              interpolate: d3.interpolateYlOrRd
            }
          }
        }
      });
    });
</script>

</body>
</html>
