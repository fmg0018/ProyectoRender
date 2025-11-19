# 🚀 Configuración del Workflow de n8n - Paso a Paso

## 📋 Guía Completa de Configuración

### PASO 1: Acceder a tu n8n
1. Abre tu navegador y ve a: `https://proyectocrm.app.n8n.cloud`
2. Inicia sesión con tus credenciales

---

### PASO 2: Importar el Workflow

**Opción A - Importar JSON (MÁS FÁCIL):**

1. En n8n, haz clic en el menú hamburguesa (☰) arriba a la izquierda
2. Selecciona **"Import from File"** o **"Workflows" > "Import"**
3. Selecciona el archivo: `docs/n8n-workflow-factura.json`
4. El workflow se importará con todos los nodos configurados

**Opción B - Crear Manualmente:**

Si prefieres hacerlo manual, sigue los pasos del PASO 3.

---

### PASO 3: Crear el Workflow Manualmente (Si no importaste el JSON)

#### 3.1 Crear Nuevo Workflow
1. Haz clic en **"+ Create new workflow"**
2. Nombre del workflow: `Enviar Email Nueva Factura`

#### 3.2 Añadir Nodo Webhook
1. Haz clic en el **"+"** para añadir un nodo
2. Busca y selecciona **"Webhook"**
3. Configuración del Webhook:
   - **HTTP Method**: `POST`
   - **Path**: `factura-creada`
   - **Response Mode**: `On Received`
   - **Response Data**: `All Entries`

4. **IMPORTANTE**: Copia la URL del webhook que aparece, debería ser:
   ```
   https://proyectocrm.app.n8n.cloud/webhook-test/factura-creada
   ```

#### 3.3 Añadir Nodo Send Email
1. Haz clic en el **"+"** después del Webhook
2. Busca **"Send Email"** o **"Email Send"**
3. Conéctalo al nodo Webhook
4. Configuración del Email:

**From Email:**
```
noreply@tuempresa.com
```
(O el email que quieras usar como remitente)

**To Email:** (haz clic en "Expression" o el icono ⚙️)
```
{{ $json.cliente.email }}
```

**Subject:** (haz clic en "Expression")
```
Nueva Factura #{{ $json.factura.numero_factura }}
```

**Email Type:** Selecciona `Text`

**Message:** (haz clic en "Expression")
```
Hola {{ $json.cliente.nombre }},

Se ha creado una nueva factura a tu nombre:

📋 DETALLES DE LA FACTURA:
━━━━━━━━━━━━━━━━━━━━━━━━━━
Número de Factura: {{ $json.factura.numero_factura }}
Fecha de Emisión: {{ $json.factura.fecha_emision }}
Fecha de Vencimiento: {{ $json.factura.fecha_vencimiento }}
Estado: {{ $json.factura.estado }}

💰 IMPORTES:
━━━━━━━━━━━━━━━━━━━━━━━━━━
Subtotal: ${{ $json.factura.subtotal }}
Impuestos: ${{ $json.factura.impuestos }}
TOTAL: ${{ $json.factura.total }}

📝 Descripción:
{{ $json.factura.descripcion }}

━━━━━━━━━━━━━━━━━━━━━━━━━━

Puedes acceder a tu factura completa en nuestro portal.

Gracias por tu confianza.

Saludos,
El equipo de facturación
```

---

### PASO 4: Configurar Credenciales SMTP

Para que n8n pueda enviar correos, necesitas configurar tus credenciales SMTP:

#### Opción A - Gmail (RECOMENDADO para pruebas):

1. En el nodo **"Send Email"**, haz clic en **"Credential to connect with"**
2. Selecciona **"Create New"**
3. Selecciona **"SMTP"**
4. Configuración para Gmail:
   ```
   Host: smtp.gmail.com
   Port: 587
   Security: Use STARTTLS
   User: tu-email@gmail.com
   Password: tu-contraseña-de-aplicación
   ```

**⚠️ IMPORTANTE para Gmail:**
- NO uses tu contraseña normal de Gmail
- Debes crear una "Contraseña de aplicación":
  1. Ve a tu cuenta de Google: https://myaccount.google.com/security
  2. Activa la verificación en 2 pasos si no la tienes
  3. Busca "Contraseñas de aplicaciones"
  4. Genera una nueva para "Correo" / "Otro dispositivo"
  5. Usa esa contraseña de 16 caracteres en n8n

#### Opción B - SendGrid (Para producción):

```
Host: smtp.sendgrid.net
Port: 587
Security: Use STARTTLS
User: apikey
Password: tu-api-key-de-sendgrid
```

#### Opción C - Otro proveedor SMTP:

Usa las credenciales que te proporcione tu proveedor de email.

---

### PASO 5: Probar el Workflow

#### 5.1 Probar con datos de ejemplo
1. En n8n, haz clic en el nodo **Webhook**
2. Haz clic en **"Listen for Test Event"**
3. En tu terminal o Postman, envía este JSON de prueba:

```bash
curl -X POST https://proyectocrm.app.n8n.cloud/webhook-test/factura-creada \
  -H "Content-Type: application/json" \
  -d '{
    "evento": "nueva_factura",
    "factura": {
      "id": 999,
      "numero_factura": "FAC-TEST-001",
      "fecha_emision": "2025-11-19",
      "fecha_vencimiento": "2025-12-19",
      "subtotal": 1000.00,
      "impuestos": 210.00,
      "total": 1210.00,
      "estado": "pendiente",
      "descripcion": "Prueba de integración"
    },
    "cliente": {
      "id": 1,
      "nombre": "Cliente de Prueba",
      "email": "TU-EMAIL-AQUI@gmail.com",
      "telefono": "+34123456789",
      "empresa": "Empresa Demo"
    }
  }'
```

**⚠️ IMPORTANTE**: Cambia `TU-EMAIL-AQUI@gmail.com` por tu email real para recibir la prueba.

4. Deberías ver el JSON aparecer en n8n
5. Haz clic en **"Execute Workflow"**
6. Verifica que el email se haya enviado correctamente
7. Revisa tu bandeja de entrada

---

### PASO 6: Activar el Workflow

1. Si la prueba funcionó, haz clic en el **toggle** arriba a la derecha para **ACTIVAR** el workflow
2. El toggle debe ponerse en **verde/azul** y decir **"Active"**
3. ¡Listo! Ahora el workflow está escuchando automáticamente

---

### PASO 7: Verificar la Integración con Laravel

1. Asegúrate de que tu archivo `.env` tenga la URL correcta:
   ```env
   N8N_WEBHOOK_URL=https://proyectocrm.app.n8n.cloud/webhook-test/factura-creada
   ```

2. Si acabas de editar el `.env`, reinicia tu servidor Laravel:
   ```bash
   php artisan config:clear
   php artisan serve
   ```

3. Crea una factura desde tu CRM
4. El cliente debería recibir el email automáticamente
5. Verifica la ejecución en n8n (menú **"Executions"**)

---

## 🔍 Verificación y Troubleshooting

### ✅ Checklist Final:
- [ ] n8n está accesible en https://proyectocrm.app.n8n.cloud
- [ ] Workflow importado o creado correctamente
- [ ] Webhook configurado con path `factura-creada`
- [ ] Credenciales SMTP configuradas y probadas
- [ ] Workflow está **ACTIVO** (toggle verde)
- [ ] Variable `N8N_WEBHOOK_URL` configurada en `.env`
- [ ] Servidor Laravel reiniciado después de cambiar `.env`

### 🐛 Problemas Comunes:

**1. El email no llega:**
- Revisa la carpeta de spam
- Verifica las credenciales SMTP en n8n
- Comprueba que el email del cliente sea válido
- Mira los logs de ejecución en n8n

**2. Error 404 en el webhook:**
- Verifica que la URL en `.env` sea exactamente la del webhook
- Asegúrate de que el workflow esté activo
- Comprueba que el path sea `factura-creada`

**3. Laravel no envía datos:**
- Verifica los logs: `storage/logs/laravel.log`
- Ejecuta: `php artisan config:clear`
- Comprueba que `N8N_WEBHOOK_URL` esté definida

**4. Error de SMTP en n8n:**
- Para Gmail, usa contraseña de aplicación, no la normal
- Verifica que el puerto sea 587 o 465
- Comprueba que el host sea correcto

---

## 📊 Datos que Recibe n8n

Este es el formato JSON que Laravel envía automáticamente:

```json
{
  "evento": "nueva_factura",
  "factura": {
    "id": 123,
    "numero_factura": "FAC-2025-001",
    "fecha_emision": "2025-11-19",
    "fecha_vencimiento": "2025-12-19",
    "subtotal": 1000.00,
    "impuestos": 210.00,
    "total": 1210.00,
    "estado": "pendiente",
    "descripcion": "Servicios de consultoría"
  },
  "cliente": {
    "id": 45,
    "nombre": "Juan Pérez",
    "email": "juan@example.com",
    "telefono": "+34123456789",
    "empresa": "Empresa Demo S.L."
  }
}
```

---

## 🎨 Personalización del Email

Puedes personalizar el template del email en n8n usando estas variables:

### Variables disponibles:
```
{{ $json.cliente.nombre }}         - Nombre del cliente
{{ $json.cliente.email }}          - Email del cliente
{{ $json.cliente.telefono }}       - Teléfono
{{ $json.cliente.empresa }}        - Empresa

{{ $json.factura.numero_factura }} - Número de factura
{{ $json.factura.fecha_emision }}  - Fecha emisión
{{ $json.factura.fecha_vencimiento }} - Fecha vencimiento
{{ $json.factura.subtotal }}       - Subtotal
{{ $json.factura.impuestos }}      - Impuestos
{{ $json.factura.total }}          - Total
{{ $json.factura.estado }}         - Estado
{{ $json.factura.descripcion }}    - Descripción
```

---

## 📞 Soporte

Si tienes problemas:
1. Revisa los logs de Laravel: `storage/logs/laravel.log`
2. Revisa las ejecuciones en n8n: menú "Executions"
3. Consulta la documentación de n8n: https://docs.n8n.io/

¡Todo listo! 🎉
