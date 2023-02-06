<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Static Documentation</title>
    <link rel="stylesheet" type="text/css" href="/api/public/swagger/dist/swagger-ui.css" >
    <link rel="icon" type="image/png" href="/api/public/swagger/dist/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/api/public/swagger/dist/favicon-16x16.png" sizes="16x16" />
    <style>
      html
      {
        box-sizing: border-box;
        overflow: -moz-scrollbars-vertical;
        overflow-y: scroll;
      }
      *,
      *:before,
      *:after
      {
        box-sizing: inherit;
      }

      body
      {
        margin:0;
        background: #fafafa;
      }

      .download-url-wrapper
      {
        display: none !important;
      }
    </style>
  </head>

  <body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@3.12.1/swagger-ui-standalone-preset.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@3.12.1/swagger-ui-bundle.js"></script>
    <script>
    window.onload = function() {
      // Begin Swagger UI call region
        //console.log(window.location.pathname);
      const ui = SwaggerUIBundle({
        url: window.location.protocol + "//" + window.location.hostname + window.location.pathname + "/swagger/api.json",
        dom_id: '#swagger-ui',
        supportedSubmitMethods: [],
        deepLinking: true,
          presets: [
              SwaggerUIBundle.presets.apis,
              SwaggerUIStandalonePreset
          ],
          layout: "StandaloneLayout"
      })
      // End Swagger UI call region
      window.ui = ui
    }
  </script>
  </body>
</html>
