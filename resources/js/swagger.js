import SwaggerUI from 'swagger-ui'
import 'swagger-ui/dist/swagger-ui.css';

SwaggerUI({
    dom_id: '#swagger-api',
    url: config('app.url')+"/public/swagger/api.yaml",
});
