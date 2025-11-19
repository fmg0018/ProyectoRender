# 🧪 GUÍA DE PRUEBAS - Integración n8n con Laravel

## ✅ PRE-REQUISITOS (Verifica que tengas todo):

- [ ] n8n ejecutándose y accesible
- [ ] Workflow activo en n8n (toggle verde/azul)
- [ ] Variable `N8N_WEBHOOK_URL` configurada en `.env`
- [ ] Credenciales SMTP configuradas en n8n
- [ ] Servidor Laravel ejecutándose

---

## 🔍 PASO 1: Verificar la Configuración

### 1.1 Verificar n8n:
1. Abre tu navegador: `https://proyectocrm.app.n8n.cloud`
2. Ve a tu workflow "Enviar Email Nueva Factura"
3. **Verifica que el toggle esté ACTIVO** (verde/azul arriba a la derecha)
4. Haz clic en el nodo **Webhook**
5. Copia la URL exacta del webhook (debe ser algo como):
   ```
   https://proyectocrm.app.n8n.cloud/webhook-test/factura-creada
   ```

### 1.2 Verificar Laravel .env:
1. Abre tu archivo `.env` (NO el .env.example)
2. Busca la línea `N8N_WEBHOOK_URL=`
3. **Verifica que la URL sea exactamente igual** a la del webhook de n8n
4. Debería verse así:
   ```env
   N8N_WEBHOOK_URL=https://proyectocrm.app.n8n.cloud/webhook-test/factura-creada
   ```

### 1.3 Limpiar caché de Laravel:
```powershell
php artisan config:clear
php artisan cache:clear
```

---

## 🚀 PASO 2: Prueba Manual del Webhook (Probar n8n directamente)

Antes de probar desde Laravel, vamos a verificar que n8n funciona correctamente.

### 2.1 Preparar n8n para recibir datos:
1. En n8n, abre tu workflow
2. Haz clic en el nodo **Webhook**
3. Haz clic en **"Listen for Test Event"** (aparecerá "Waiting for test event...")

### 2.2 Enviar datos de prueba con PowerShell:

Abre una terminal PowerShell y ejecuta este comando (CAMBIA el email):

```powershell
$body = @{
    evento = "nueva_factura"
    factura = @{
        id = 999
        numero_factura = "PRUEBA-001"
        fecha_emision = "2025-11-19"
        fecha_vencimiento = "2025-12-19"
        subtotal = 1000.00
        impuestos = 210.00
        total = 1210.00
        estado = "pendiente"
        descripcion = "Esta es una factura de prueba"
    }
    cliente = @{
        id = 1
        nombre = "Cliente de Prueba"
        email = "TU-EMAIL-AQUI@gmail.com"
        telefono = "+34123456789"
        empresa = "Empresa Test"
    }
} | ConvertTo-Json

Invoke-RestMethod -Uri "https://proyectocrm.app.n8n.cloud/webhook-test/factura-creada" -Method POST -Body $body -ContentType "application/json"
```

**⚠️ IMPORTANTE**: Cambia `TU-EMAIL-AQUI@gmail.com` por tu email real.

### 2.3 Verificar resultado:
1. En n8n deberías ver los datos aparecer en el nodo Webhook
2. Haz clic en **"Execute Workflow"**
3. Todos los nodos deberían ejecutarse en verde ✅
4. **Revisa tu bandeja de entrada** - deberías recibir el email

**Si NO funciona aquí**, el problema está en n8n (probablemente las credenciales SMTP).
**Si funciona**, continuamos al siguiente paso.

---

## 🎯 PASO 3: Prueba Real desde Laravel

Ahora vamos a crear una factura real desde tu CRM.

### 3.1 Iniciar el servidor Laravel:
```powershell
php artisan serve
```

### 3.2 Abrir tu CRM:
Abre el navegador: `http://localhost:8000`

### 3.3 Iniciar sesión:
Inicia sesión con tu usuario del CRM

### 3.4 Verificar que tienes un cliente con email válido:
1. Ve a **"Clientes"** en el menú
2. **Verifica que tengas al menos un cliente con un EMAIL VÁLIDO**
3. Si no tienes ninguno, crea uno:
   - Nombre: `Cliente Prueba`
   - Email: `TU-EMAIL@gmail.com` (usa tu email real)
   - Guarda

### 3.5 Crear una factura de prueba:
1. Ve a **"Facturas"** en el menú
2. Haz clic en **"Nueva Factura"** o **"Crear Factura"**
3. Rellena el formulario:
   - **Cliente**: Selecciona el cliente que tiene tu email
   - **Fecha de Emisión**: Hoy (2025-11-19)
   - **Fecha de Vencimiento**: Dentro de un mes
   - **Subtotal**: 1000
   - **Impuestos**: 21 (o 0)
   - **Estado**: Pendiente
   - **Descripción**: "Prueba de integración n8n"
4. Haz clic en **"Crear"** o **"Guardar"**

### 3.6 Verificar el resultado:
1. **Inmediatamente** después de crear la factura, ve a n8n
2. Haz clic en el menú **"Executions"** (arriba a la izquierda)
3. Deberías ver una nueva ejecución con:
   - ✅ Status: Success
   - Fecha y hora de hace unos segundos
4. **Revisa tu bandeja de entrada** - deberías tener el email de la factura

---

## 📊 PASO 4: Verificar Logs de Laravel (Si algo falla)

Si no funciona, vamos a ver los logs:

### 4.1 Ver logs en tiempo real:
```powershell
Get-Content storage/logs/laravel.log -Tail 50 -Wait
```

### 4.2 Buscar errores relacionados con n8n:
```powershell
Select-String -Path storage/logs/laravel.log -Pattern "n8n" -Context 2,2
```

---

## 🐛 TROUBLESHOOTING - Solución de Problemas

### ❌ Problema 1: "El webhook no recibe datos desde Laravel"

**Síntomas**: 
- La prueba manual funciona
- Al crear factura en Laravel no llega nada a n8n

**Solución**:
```powershell
# 1. Verificar que la variable está bien
php artisan tinker
# Dentro de tinker, ejecuta:
env('N8N_WEBHOOK_URL')
# Debería mostrar tu URL completa
exit

# 2. Si no aparece o es null, edita tu .env (NO .env.example)
# Añade o corrige:
N8N_WEBHOOK_URL=https://proyectocrm.app.n8n.cloud/webhook-test/factura-creada

# 3. Limpia la caché:
php artisan config:clear
php artisan cache:clear

# 4. Reinicia el servidor:
# Ctrl+C para detener
php artisan serve
```

---

### ❌ Problema 2: "Error 404 en el webhook"

**Síntomas**: 
- Error en logs de Laravel: "404 Not Found"

**Solución**:
1. Ve a n8n
2. Verifica que el workflow esté **ACTIVO** (toggle verde)
3. Copia nuevamente la URL exacta del webhook
4. Actualiza tu `.env` con esa URL exacta
5. Ejecuta: `php artisan config:clear`

---

### ❌ Problema 3: "El email no llega"

**Síntomas**: 
- n8n recibe los datos (ves la ejecución)
- Pero no llega el email

**Solución**:
1. En n8n, haz clic en la ejecución fallida
2. Haz clic en el nodo "Enviar Email"
3. Revisa el error
4. Problemas comunes:
   - **"Authentication failed"**: Credenciales SMTP incorrectas
     - Para Gmail, necesitas contraseña de aplicación (no la normal)
   - **"Invalid email"**: El cliente no tiene email válido
   - **"Connection timeout"**: Verifica host y puerto SMTP

---

### ❌ Problema 4: "No veo logs ni errores"

**Síntomas**: 
- No pasa nada al crear la factura
- No hay logs de n8n en Laravel

**Solución**:
```powershell
# Verificar que el servicio está importado correctamente
Select-String -Path app/Http/Controllers/FacturaControlador.php -Pattern "N8nWebhookService"

# Debería aparecer en los "use" al inicio del archivo
# Si no aparece, necesitas verificar la integración
```

---

## 📝 Checklist de Verificación Completa

Marca cada punto después de verificarlo:

### En n8n:
- [ ] Workflow está activo (toggle verde/azul)
- [ ] URL del webhook copiada correctamente
- [ ] Credenciales SMTP configuradas y probadas
- [ ] Prueba manual con PowerShell funciona
- [ ] Email de prueba recibido

### En Laravel:
- [ ] Archivo `.env` (no .env.example) tiene `N8N_WEBHOOK_URL=`
- [ ] URL en `.env` es exactamente igual a la de n8n
- [ ] Ejecutado `php artisan config:clear`
- [ ] Servidor Laravel ejecutándose
- [ ] Existe al menos un cliente con email válido

### Prueba Final:
- [ ] Creada factura desde el CRM
- [ ] Ejecución aparece en n8n (menú Executions)
- [ ] Email recibido en la bandeja de entrada
- [ ] Email contiene datos correctos de la factura

---

## 🎉 Si Todo Funciona

¡Felicidades! La integración está completa. Ahora:

1. Cada vez que crees una factura, el cliente recibirá un email automáticamente
2. Puedes ver todas las ejecuciones en n8n → Executions
3. Puedes personalizar el template del email en el nodo "Enviar Email"

---

## 💡 Próximos Pasos (Opcional)

Una vez que funcione, puedes:
- Personalizar el diseño del email (usar HTML en lugar de texto)
- Añadir el PDF de la factura como adjunto
- Crear recordatorios automáticos para facturas vencidas
- Enviar notificaciones cuando cambie el estado de la factura
- Integrar con otros servicios (Slack, WhatsApp, etc.)

---

## 📞 ¿Necesitas Ayuda?

Si sigues teniendo problemas:
1. Revisa los logs: `storage/logs/laravel.log`
2. Revisa las ejecuciones en n8n con el botón "Executions"
3. Asegúrate de que todos los checklist estén marcados
