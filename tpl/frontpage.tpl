<!DOCTYPE html>
<html>
  <head>
    <title>{{title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <div id="content" class="container">
      <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8 col-lg-offset-3 col-lg-6">
        <script type="text/javascript">
            var filelink = "{{link}}";
        </script>

        <div class="page-header">
          <h1 class="text-center">
            <span class="glyphicon glyphicon-cloud"></span>
            Jyraphe <small>share files freely</small>
          </h1>
        </div>

        <div id="uploaded" class="well">
            <p class="text-primary">
              <span class="glyphicon glyphicon-ok"></span>
              You can access your file at the following URL:
            </p>
            <form role="form">
              <input type="text" taborder="1" class="form-control" id="link" value="{{link}}" />
            </form>
        </div>

        <div id="dropbox" class="well" style="height: 100px;">
            <div class="text-center">
                <span class="glyphicon glyphicon-move"></span> Drop your file here...
            </div>
        </div>

        <h3 class="text-center">or</h3>

        <div id="upload" class="well">
          <form enctype="multipart/form-data" role="form" action="" method="post">
            <div class="form-group">
              <label for="inputFile">Upload a file</label>
              <input type="file" id="inputFile" class="form-control" name="file" size="30" />
            </div>
            <p class="help-block">Maximum file size: {{max_file_size}}MB</p>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-cloud-upload"></span>
                Upload and share
              </button>
            </div>
          </form>
        </div>

        <div class="text-center">
            Powered by <a href="https://github.com/jendib/jyraphe/">Jyraphe</a><br>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="jyraphe.js"></script>
  </body>
</html>
