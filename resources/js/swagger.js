import SwaggerUI from 'swagger-ui'
import 'swagger-ui/dist/swagger-ui.css';

SwaggerUI({
    dom_id: '#swagger-api',
    url: process.env.MIX_APP_BASE_URL+"api/public/swagger/api.yaml",
    supportedSubmitMethods: []
});
