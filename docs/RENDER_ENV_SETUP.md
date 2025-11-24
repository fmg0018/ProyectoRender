# Configuración de Variables de Entorno en Render

## Variables Críticas que DEBEN configurarse en Render:

### 1. Logs (CRÍTICO para ver la integración n8n)
```
LOG_CHANNEL=stderr
LOG_STACK=stderr
LOG_LEVEL=debug
```

**¿Por qué?** Render solo muestra logs que van a `stderr`. Si usas `single` o `stack`, los logs se guardan en archivos que no puedes ver.

### 2. N8N Webhook
```
N8N_WEBHOOK_URL=https://proyectocrm.app.n8n.cloud/webhook/factura-creada
```

**¿Por qué?** Esta es la URL que Laravel usa para enviar notificaciones a n8n cuando se crea una factura.

### 3. Base de Datos (Ya deberías tenerlas)
```
DB_CONNECTION=pgsql
DB_HOST=ballast.proxy.rlwy.net
DB_PORT=10080
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=hEdzBpmkcNEmgOgtxfmJDUgDmPyJvmQt
DATABASE_URL=postgresql://postgres:hEdzBpmkcNEmgOgtxfmJDUgDmPyJvmQt@ballast.proxy.rlwy.net:10080/railway
```

## Cómo Configurar en Render:

1. Ve a tu dashboard de Render: https://dashboard.render.com
2. Selecciona tu servicio del CRM
3. Ve a **"Environment"** en el menú lateral
4. Para cada variable:
   - Haz clic en **"Add Environment Variable"**
   - **Key**: Nombre de la variable (ej: `LOG_CHANNEL`)
   - **Value**: Valor de la variable (ej: `stderr`)
   - Haz clic en **"Add"**
5. Una vez agregadas todas, haz clic en **"Save Changes"**
6. Render redesplegará automáticamente (2-3 minutos)

## Verificar que Funciona:

Después del redespliegue:

1. Ve a la pestaña **"Logs"** en Render
2. Crea una factura en tu CRM
3. Deberías ver logs como:
   ```
   ═══════════════════════════════════════
   🎯 STORE FACTURA - INICIO
   ═══════════════════════════════════════
   💾 FACTURA CREADA - PREPARANDO ENVÍO A N8N
   🔔 N8nWebhookService::notificarNuevaFactura llamado
   📤 Enviando payload a n8n
   ✅ Notificación enviada a n8n correctamente
   ```

## Troubleshooting:

### No veo ningún log:
- Verifica que `LOG_CHANNEL=stderr` esté configurado en Render
- Asegúrate de que Render terminó de redesplegar
- Intenta crear una factura de nuevo

### Veo los logs pero dice "N8N_WEBHOOK_URL no está configurada":
- Agrega la variable `N8N_WEBHOOK_URL` en Render
- Asegúrate de que el valor sea exactamente: `https://proyectocrm.app.n8n.cloud/webhook/factura-creada`
- Guarda y espera el redespliegue

### Los logs muestran error al enviar a n8n:
- Verifica que el workflow en n8n esté ACTIVO (toggle azul/verde)
- Verifica que la URL del webhook sea correcta
- Revisa las ejecuciones en n8n para ver el error específico
