# Configuración de Laravel Passport

## Visión general

Este documento describe la configuración de Laravel Passport para la autenticación basada en tokens en nuestra API de la Liga Pokémon.

## Configuración inicial

### Generación de secretos

Para mantener consistencia entre entornos de desarrollo y testing, utilizamos secretos de cliente fijos en lugar de los generados aleatoriamente por `passport:install`. Estos secretos se especifican en las variables de entorno:

```
PASSPORT_PASSWORD_GRANT_SECRET=your_password_grant_secret
PASSPORT_PERSONAL_ACCESS_SECRET=your_personal_access_secret
```

Para generar estos valores, ejecuta:

```bash
php artisan passport:generate-secrets
```

### Seeder de Passport

Hemos creado un seeder específico que configura los clientes de Passport con los secretos definidos en las variables de entorno:

```bash
php artisan db:seed --class=PassportSeeder
```

Este seeder crea:
- Un cliente de tipo "Password Grant" para autenticación de la API
- Un cliente de tipo "Personal Access" para tokens personales

## Uso en pruebas

Para tests que utilizan `RefreshDatabase`, ejecutamos el `PassportSeeder` en el método `setUp()` para asegurar que los clientes de Passport estén disponibles después de cada migración.

```php
public function setUp(): void
{
    parent::setUp();
    
    // Seed roles and permissions
    $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\RoleSeeder']);
    
    // Seed Passport clients
    $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\PassportSeeder']);
}
```

## Flujo de autenticación

1. **Registro de usuario**: `POST /api/register`
   - Crea un nuevo usuario
   - Le asigna el rol correspondiente
   - Genera un token de acceso

2. **Inicio de sesión**: `POST /api/login`
   - Valida las credenciales
   - Genera un token de acceso

3. **Uso del token**: 
   - Incluir el token en el header de las solicitudes:
   ```
   Authorization: Bearer {token}
   ```

4. **Cierre de sesión**: `POST /api/logout`
   - Revoca el token actual

## Solución de problemas

### Los tokens no se generan correctamente

- Verificar que las variables `PASSPORT_*_SECRET` estén configuradas en el `.env`
- Ejecutar `php artisan config:clear`
- Verificar que el `PassportSeeder` se haya ejecutado

### Problemas en tests

- Asegurarse de que `.env.testing` tenga los mismos valores de `PASSPORT_*_SECRET` que `.env`
- Verificar que los tests incluyan el seeding de Passport