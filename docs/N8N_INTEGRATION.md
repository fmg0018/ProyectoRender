# Integración con n8n para Automatización de Correos

## Descripción

Este CRM está integrado con n8n para automatizar el envío de correos electrónicos cuando se crean nuevas facturas. Cuando se añade una factura a un cliente, se envía automáticamente una notificación al webhook de n8n, que puede procesarla y enviar un correo al cliente.

## Configuración

### 1. Instalar n8n

Puedes instalar n8n de varias formas:

#### Opción A: Con Docker (Recomendado)
```bash
docker run -it --rm --name n8n -p 5678:5678 -v ~/.n8n:/home/node/.n8n n8nio/n8n
```

#### Opción B: Con npm
```bash
npm install n8n -g
n8n
```

#### Opción C: Con npx (sin instalación)
```bash
npx n8n
```

n8n se ejecutará en `http://localhost:5678`

### 2. Crear el Workflow en n8n

1. Accede a n8n en tu navegador: `http://localhost:5678`
2. Crea una nueva cuenta o inicia sesión
3. Crea un nuevo workflow
4. Añade los siguientes nodos:

#### Paso 1: Webhook Node
- Busca y añade el nodo **Webhook**
- Configuración:
  - **HTTP Method**: POST
  - **Path**: `factura-creada` (o el que prefieras)
  - Copia la **URL del webhook** que aparece (la necesitarás para el .env)

#### Paso 2: Send Email Node
- Añade el nodo **Send Email**
- Conéctalo al webhook
- Configuración:
  - **To Email**: `{{ $json.cliente.email }}`
  - **Subject**: `Nueva Factura #{{ $json.factura.numero_factura }}`
  - **Text**: Puedes usar este template:
    ```
    Hola {{ $json.cliente.nombre }},

    Se ha creado una nueva factura a tu nombre:

    Número de Factura: {{ $json.factura.numero_factura }}
    Fecha de Emisión: {{ $json.factura.fecha_emision }}
    Fecha de Vencimiento: {{ $json.factura.fecha_vencimiento }}
    
    Subtotal: ${{ $json.factura.subtotal }}
    Impuestos: ${{ $json.factura.impuestos }}
    Total: ${{ $json.factura.total }}
    
    Estado: {{ $json.factura.estado }}
    
    Gracias por tu confianza.
    
    Saludos,
    El equipo de {{ $json.factura.descripcion }}
    ```

**Nota**: Para que el nodo Send Email funcione, necesitas configurar las credenciales de tu servidor SMTP en n8n:
- Gmail: Usa una contraseña de aplicación
- Otros: Configura SMTP con tu proveedor de correo

#### Paso 3: Activar el Workflow
- Haz clic en **Execute Workflow** para probar
- Luego activa el workflow con el toggle en la esquina superior derecha

### 3. Configurar la Variable de Entorno

1. Copia la URL del webhook de n8n (algo como `http://localhost:5678/webhook/factura-creada`)
2. Edita tu archivo `.env` en Laravel:
   ```env
   N8N_WEBHOOK_URL=http://localhost:5678/webhook/factura-creada
   ```

3. Si n8n está en producción (servidor remoto), usa esa URL:
   ```env
   N8N_WEBHOOK_URL=https://tu-n8n.com/webhook/factura-creada
   ```

### 4. Probar la Integración

1. Asegúrate de que n8n está ejecutándose y el workflow está activo
2. En tu CRM, crea una nueva factura para un cliente
3. Verifica que:
   - El webhook de n8n recibe la petición (lo verás en el historial de ejecuciones)
   - El cliente recibe el correo electrónico

## Estructura de Datos Enviados

Cuando se crea una factura, Laravel envía el siguiente JSON al webhook de n8n:

```json
{
  "evento": "nueva_factura",
  "factura": {
    "id": 1,
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
    "id": 1,
    "nombre": "Juan Pérez",
    "email": "juan@example.com",
    "telefono": "+34123456789",
    "empresa": "Empresa Demo S.L."
  }
}
```

## Personalización

Puedes personalizar el workflow de n8n para:
- Enviar recordatorios de facturas vencidas
- Notificar cuando cambia el estado de una factura
- Enviar la factura en PDF adjunto
- Crear tareas en otros sistemas (Trello, Notion, etc.)
- Guardar registros en hojas de cálculo
- Y mucho más...

## Troubleshooting

### El correo no se envía
- Verifica que n8n esté ejecutándose
- Comprueba que el workflow esté activo
- Revisa las credenciales SMTP en n8n
- Mira los logs de ejecución en n8n

### Error de conexión al webhook
- Verifica que `N8N_WEBHOOK_URL` esté correctamente configurada en `.env`
- Si n8n está en localhost, asegúrate de usar `http://` no `https://`
- Revisa los logs de Laravel: `storage/logs/laravel.log`

### El webhook recibe los datos pero no se envía el correo
- Verifica la configuración del nodo Send Email
- Comprueba las credenciales SMTP
- Revisa que los campos del email estén correctamente mapeados

## Soporte

Para más información sobre n8n:
- Documentación oficial: https://docs.n8n.io/
- Comunidad: https://community.n8n.io/
