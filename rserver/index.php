<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>云麓谷</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="jumbotron-narrow.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>
    <script src="assets/js/vendor/jquery.min.js"></script>
    <link href="assets/plugins/Ladda/css/demo.css" rel="stylesheet" />
    <link href="assets/plugins/Ladda/dist/ladda.min.css" rel="stylesheet" />
    <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
      <div class="header clearfix">

        <h3 class="text-muted">demo</h3>
      </div>

      <div class="jumbotron">
        <h3>不会写前端</h3>
        <p class="lead">We haven't learnt the dead hard Javascript well</p>
        <!-- <p><a class="btn btn-lg btn-success" href="#" role="button">Sign up today</a></p> -->
          <select id="memberSlt" class="js-example-data-array-selected">
            <option value="3620194" selected="selected">选择绑定的员工</option>
          </select>
          <!-- <button  type="submit" name="submit">绑定</button> -->
          <button id="submitBtn" class="ladda-button" data-color="mint" data-style="contract"><span class="ladda-label">Submit</span></button>

      </div>

      <div class="row marketing">
        <div class="col-lg-6">
          <img id="fingerPrint" src="" alt="" />
        </div>

        <div id="list" class="col-lg-6">

        </div>
      </div>

      <footer class="footer">
        <p>&copy; 2016 云麓谷.</p>
      </footer>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="assets/plugins/select2/dist/js/select2.min.js"></script>
    <script src="assets/plugins/Ladda/dist/spin.min.js"></script>
    <script src="assets/plugins/Ladda/dist/ladda.min.js"></script>

    <script src="assets/plugins/Ladda/dist/ladda.jquery.min.js"></script>

    <script>
    $(function(){
      data = $.get('http://192.168.43.124/fserver/get_data.php?status=2', function (data){
        // console.log(data);
      })
    });
    $( '#submitBtn' ).ladda( 'bind', { timeout: 4000 } );
    $( '#submitBtn' ).click( function () {
      $.get('http://192.168.43.124/fserver/res.php?mid=' + $('#memberSlt').val(), function(data){
        console.log(data);
        data = JSON.parse(data);
        $('#fingerPrint').attr('src', 'data:image/png;base64,' + data.img)
      })
    } );
    var data = $.get('http://192.168.43.124/fserver/get_data.php?status=1', function(data) {
      var arr = $.map(JSON.parse(data), function(el) { return [{id: el.mid, text: el.name}] });
      console.log(arr);
      $(".js-example-data-array-selected").select2({
          data: arr
      });
    });

    var msg = $("#list");
    var wsServer = 'ws://192.168.43.151:9502';
    //var wsServer = 'ws://192.168.43.124:9502';
    //调用websocket对象建立连接：
    //参数：ws/wss(加密)：//ip:port （字符串）
    var websocket = new WebSocket(wsServer);
    //onopen监听连接打开
    websocket.onopen = function (evt) {
        //websocket.readyState 属性：
        /*
        CONNECTING    0    The connection is not yet open.
        OPEN    1    The connection is open and ready to communicate.
        CLOSING    2    The connection is in the process of closing.
        CLOSED    3    The connection is closed or couldn't be opened.
        */
        // msg.innerHTML = websocket.readyState;
        console.log(websocket.readyState);
    };

    //onmessage 监听服务器数据推送
    websocket.onmessage = function (evt) {
      var data = JSON.parse(evt.data);
      if(!data.mid){
        console.log('no person');
        return;
      }
      // data = JSON.parse(evt.data);
      var l = '<h4>' + data.time + '</h4><p>' + data.name + '</p>';
      msg.append(l);
      console.log(data);
//        console.log('Retrieved data from server: ' + evt.data);
    };
    </script>

  </body>
</html>
