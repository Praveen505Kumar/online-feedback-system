<?php 
    @session_start();
    if(!empty($_POST['regulation']) && !empty($_POST['year']) && !empty($_POST['sem']) && !empty($_POST['fdtime'])){
        require('header.php');
        
        // connection
        require("Operations.php");
        $opt = new Operations();

        $reg = $_POST['regulation'];
        $year = $_POST['year'];
        $sem = $_POST['sem'];
        $feeds = explode('_', $_POST['fdtime']);
        $feed_id = $feeds[0];
        $br_code = $_SESSION['br_code'];
        $cr_code = "A";

        // getting report details
        $reportdetails = $opt->getReportDetails($br_code, $year, $sem, $reg, $cr_code, $feed_id);
        $facnames = $reportdetails['facname'];
        $subnames = $reportdetails['subname'];
        $avgs = $reportdetails['avg'];
      
?>
<div class="container ms-0">
    <div class="row">
        <div class="col-5 mt-3 me-5" style="max-width:400px;">
            <div class="list-group">
                    <?php
                    if($_SESSION['priv'] == "admin"){
                        $menu_id = 10;
                        require_once("menu.php");
                    }else{
                        $menu_id = 2;
                        require_once("hodmenu.php");
                    }
                    ?>
            </div>
        </div>
        <div class="col-7 mx-5 my-2">
            <div class="container text-center">
                <?php
                    if($_SESSION['priv'] == "admin"){
                        echo "<h4>Selected Department: &emsp;";
                        if(!empty($_SESSION['branch']) && $_SESSION['branch']=="all"){
                            echo "None";
                        }else{
                            echo $_SESSION['branch'];
                        }
                        echo "</h4>";
                    }
                ?>
            </div>
            <div class="container text-center mt-5">
                <a href="" class="btn btn-primary">Download Individual Reports</a>
            </div>
            
        </div>
        
    </div>
</div>
<!-- <script>
    zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
    ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "b55b025e438fa8a98e32482b5f768ff5"]; // window:load event for Javascript to run after HTML
    // because this Javascript is injected into the document head
    window.addEventListener('load', function() {
      // Javascript code to execute after DOM content
 
      // full ZingChart schema can be found here:
      // https://www.zingchart.com/docs/api/json-configuration/
      const myConfig = {
        type: 'bar',
        title: {
          text: 'Hello World Demo',
          fontSize: 24,
          color: '#5d7d9a'
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
            backgroundColor: '#4d80a6'
          },
          {
            // plot 2 values, linear data
            values: [35, 42, 33, 49, 35, 47, 35],
            text: 'Week 2',
            backgroundColor: '#70cfeb'
          },
          {
            // plot 2 values, linear data
            values: [15, 22, 13, 33, 44, 27, 31],
            text: 'Week 3',
            backgroundColor: '#8ee9de'
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
  </script> -->
<?php 
        require('footer.php');
    }else{
        header('Location: index.php');
    }
?>