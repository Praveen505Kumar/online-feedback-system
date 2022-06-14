<!DOCTYPE html>
<html>
 
<head>
  <meta charset="utf-8">
  <title>ZingGrid: Blank Grid</title>
  <script nonce="undefined" src="https://cdn.zingchart.com/zingchart.min.js"></script>
  <style>
    html,
    body {
      height: 100%;
      width: 100%;
      margin: 0;
      padding: 0;
    }
 
    .chart--container {
      height: 100%;
      width: 100%;
      min-height: 150px;
    }
 
    .zc-ref {
      display: none;
    }
  </style>
</head>
 
<body>
  <!-- CHART CONTAINER -->
  <div id="myChart" class="chart--container">
    <a class="zc-ref" href="https://www.zingchart.com">Powered by ZingChart</a>
  </div>
  <script>
    ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "b55b025e438fa8a98e32482b5f768ff5"]; // window:load event for Javascript to run after HTML
    // because this Javascript is injected into the document head
    window.addEventListener('load', () => {
      // Javascript code to execute after DOM content
 
      // full ZingChart schema can be found here:
      // https://www.zingchart.com/docs/api/json-configuration/
      let myConfig = {
        type: 'bar',
        title: {
          text: 'Data Basics',
          fontSize: 24,
        },
        legend: {
          draggable: true,
        },
        scaleX: {
          // set scale label
          label: {
            text: 'Days'
          },
          // convert text on scale indices
          labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        },
        scaleY: {
          // scale label with unicode character
          label: {
            text: 'Temperature (°F)'
          }
        },
        plot: {
          // animation docs here:
          // https://www.zingchart.com/docs/tutorials/design-and-styling/chart-animation/#animation__effect
          animation: {
            effect: 'ANIMATION_EXPAND_BOTTOM',
            method: 'ANIMATION_STRONG_EASE_OUT',
            sequence: 'ANIMATION_BY_NODE',
            speed: 275,
          }
        },
        series: [{
            // plot 1 values, linear data
            values: [23, 20, 27, 29, 25, 17, 15],
            text: 'Week 1',
          },
          {
            // plot 2 values, linear data
            values: [35, 42, 33, 49, 35, 47, 35],
            text: 'Week 2'
          },
          {
            // plot 2 values, linear data
            values: [15, 22, 13, 33, 44, 27, 31],
            text: 'Week 3'
          }
        ]
      };
 
      // render chart with width and height to
      // fill the parent container CSS dimensions
      zingchart.render({
        id: 'myChart',
        data: myConfig,
        height: '100%',
        width: '100%'
      });
    });
  </script>
</body>
 
</html>